<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_product_with_its_required_category_and_prices(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Minuman']);

        $this->actingAs($admin)->get(route('products.index'))->assertOk();
        $this->actingAs($admin)->get(route('products.create'))->assertOk();

        $response = $this->actingAs($admin)->post(route('products.store'), [
            'barcode' => 'SKU-100',
            'name' => 'Teh',
            'category_id' => $category->id,
            'cost_price' => 2000,
            'selling_price' => 5000,
            'stock' => 12,
            'min_stock' => 2,
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', [
            'barcode' => 'SKU-100',
            'category_id' => $category->id,
            'cost_price' => 2000,
            'selling_price' => 5000,
            'stock' => 12,
        ]);
    }

    public function test_product_creation_rejects_a_duplicate_barcode(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Snack']);
        Product::create([
            'barcode' => 'SKU-101',
            'name' => 'Keripik',
            'category_id' => $category->id,
            'cost_price' => 3000,
            'selling_price' => 5000,
            'stock' => 8,
            'min_stock' => 2,
        ]);

        $response = $this->actingAs($admin)
            ->from(route('products.create'))
            ->post(route('products.store'), [
                'barcode' => 'SKU-101',
                'name' => 'Keripik Baru',
                'category_id' => $category->id,
                'cost_price' => 3000,
                'selling_price' => 5000,
                'stock' => 8,
                'min_stock' => 2,
            ]);

        $response->assertRedirect(route('products.create'))->assertSessionHasErrors('barcode');
        $this->assertDatabaseCount('products', 1);
    }

    public function test_cashiers_cannot_access_administration_routes_but_can_use_the_pos(): void
    {
        $cashier = User::factory()->create(['role' => 'kasir']);

        $this->actingAs($cashier)->get(route('products.index'))->assertForbidden();
        $this->actingAs($cashier)->get(route('pos'))->assertOk();
    }
}
