<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_edit_and_delete_an_unused_category(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)->get(route('categories.index'))->assertOk();
        $this->actingAs($admin)->get(route('categories.create'))->assertOk();

        $this->actingAs($admin)->post(route('categories.store'), ['name' => 'Makanan'])
            ->assertRedirect(route('categories.index'));
        $category = Category::where('name', 'Makanan')->firstOrFail();

        $this->actingAs($admin)->get(route('categories.edit', $category))->assertOk();
        $this->actingAs($admin)->put(route('categories.update', $category), ['name' => 'Makanan Ringan'])
            ->assertRedirect(route('categories.index'));
        $this->assertSame('Makanan Ringan', $category->refresh()->name);

        $this->actingAs($admin)->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_category_with_products_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Minuman']);
        Product::create([
            'barcode' => 'CAT-001',
            'name' => 'Air',
            'category_id' => $category->id,
            'cost_price' => 1000,
            'selling_price' => 2000,
            'stock' => 4,
            'min_stock' => 1,
        ]);

        $this->actingAs($admin)->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHasErrors('category');
        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }
}
