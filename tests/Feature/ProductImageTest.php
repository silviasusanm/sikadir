<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductImageTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['role' => 'admin']);
    }

    private function payload(array $extra = []): array
    {
        $category = Category::create(['name' => 'Minuman']);

        return array_merge([
            'barcode' => 'IMG-001', 'name' => 'Es Jeruk', 'category_id' => $category->id,
            'cost_price' => 2000, 'selling_price' => 5000, 'stock' => 10, 'min_stock' => 3,
        ], $extra);
    }

    public function test_admin_can_create_product_with_image(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('jeruk.jpg', 300, 300);

        $this->actingAs($this->admin())
            ->post(route('products.store'), $this->payload(['image' => $file]))
            ->assertRedirect(route('products.index'));

        $product = Product::first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
        $this->assertStringContainsString('storage/'.$product->image, $product->image_url);
    }

    public function test_product_can_be_created_without_image(): void
    {
        $this->actingAs($this->admin())
            ->post(route('products.store'), $this->payload())
            ->assertRedirect(route('products.index'));

        $this->assertNull(Product::first()->image);
    }

    public function test_update_replaces_old_image_and_keeps_it_when_not_uploaded(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('products.store'), $this->payload(['image' => UploadedFile::fake()->image('a.png')]));
        $product = Product::first();
        $old = $product->image;

        // update tanpa gambar -> gambar lama tetap
        $this->actingAs($admin)->put(route('products.update', $product), [
            'barcode' => 'IMG-001', 'name' => 'Es Jeruk Besar', 'category_id' => $product->category_id,
            'cost_price' => 2000, 'selling_price' => 6000, 'stock' => 10, 'min_stock' => 3,
        ])->assertRedirect(route('products.index'));
        $this->assertSame($old, $product->fresh()->image);

        // update dengan gambar baru -> gambar lama dihapus
        $this->actingAs($admin)->put(route('products.update', $product), [
            'barcode' => 'IMG-001', 'name' => 'Es Jeruk Besar', 'category_id' => $product->category_id,
            'cost_price' => 2000, 'selling_price' => 6000, 'stock' => 10, 'min_stock' => 3,
            'image' => UploadedFile::fake()->image('b.webp'),
        ])->assertRedirect(route('products.index'));

        Storage::disk('public')->assertMissing($old);
        Storage::disk('public')->assertExists($product->fresh()->image);
    }

    public function test_invalid_image_is_rejected(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin())
            ->post(route('products.store'), $this->payload(['image' => UploadedFile::fake()->create('x.pdf', 10, 'application/pdf')]))
            ->assertSessionHasErrors('image');
    }

    public function test_delete_removes_image_file(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('products.store'), $this->payload(['image' => UploadedFile::fake()->image('a.jpg')]));
        $product = Product::first();
        $path = $product->image;

        $this->actingAs($admin)->delete(route('products.destroy', $product))->assertRedirect(route('products.index'));

        Storage::disk('public')->assertMissing($path);
    }

    public function test_index_shows_image_search_and_pagination(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('products.store'), $this->payload(['image' => UploadedFile::fake()->image('a.jpg')]));
        $category = Category::first();
        foreach (range(1, 12) as $i) {
            Product::create(['barcode' => "P-$i", 'name' => "Barang $i", 'category_id' => $category->id,
                'cost_price' => 1, 'selling_price' => 2, 'stock' => 5, 'min_stock' => 1]);
        }

        $this->actingAs($admin)->get(route('products.index'))
            ->assertOk()->assertSee('storage/products/', false)->assertSee('page=2', false);

        $this->actingAs($admin)->get(route('products.index', ['q' => 'Barang 7']))
            ->assertOk()->assertSee('Barang 7')->assertDontSee('Barang 8');
    }

    public function test_forms_render_and_pos_receives_image(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $this->actingAs($admin)->post(route('products.store'), $this->payload(['image' => UploadedFile::fake()->image('a.jpg')]));
        $product = Product::first();

        $this->actingAs($admin)->get(route('products.create'))->assertOk()->assertSee('name="image"', false);
        $this->actingAs($admin)->get(route('products.edit', $product))->assertOk()->assertSee('multipart/form-data', false)->assertSee($product->image, false);
        $this->actingAs($admin)->get(route('pos'))->assertOk()->assertSee(basename($product->image), false);
    }

    public function test_checkout_links_to_receipt_and_receipt_renders(): void
    {
        $kasir = User::factory()->create(['role' => 'kasir']);
        $other = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Snack']);
        $product = Product::create(['barcode' => 'R-1', 'name' => 'Keripik', 'category_id' => $category->id,
            'cost_price' => 1000, 'selling_price' => 3000, 'stock' => 5, 'min_stock' => 1]);

        $response = $this->actingAs($kasir)->post(route('pos.checkout'), [
            'cart' => [['product_id' => $product->id, 'quantity' => 2]],
            'payment_method' => 'cash', 'discount_percent' => 0, 'amount_paid' => 10000,
        ]);
        $response->assertRedirect(route('pos'))->assertSessionHas('receipt_id');

        $id = session('receipt_id');
        $this->actingAs($kasir)->get(route('pos.receipt', $id))->assertOk()->assertSee('Keripik')->assertSee('Terima kasih');
        $this->actingAs($other)->get(route('pos.receipt', $id))->assertForbidden();
    }
}
