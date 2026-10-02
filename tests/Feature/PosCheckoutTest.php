<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PosCheckoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cash_checkout_saves_transaction_discount_change_and_stock(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Makanan']);
        $product = Product::create([
            'barcode' => 'POS-001',
            'name' => 'Roti',
            'category_id' => $category->id,
            'cost_price' => 60,
            'selling_price' => 100,
            'stock' => 5,
            'min_stock' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('pos.checkout'), [
            'cart' => [
                ['product_id' => $product->id, 'quantity' => 2],
            ],
            'payment_method' => 'cash',
            'discount_percent' => 10,
            'amount_paid' => 200,
        ]);

        $response->assertRedirect(route('pos'));
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'subtotal' => 200,
            'discount_amount' => 20,
            'total_amount' => 180,
            'payment_method' => 'cash',
            'amount_paid' => 200,
            'change_due' => 20,
        ]);
        $this->assertDatabaseHas('transaction_details', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
            'subtotal' => 180,
            'cost_price' => 60,
        ]);
        $this->assertSame(3, $product->refresh()->stock);
    }

    public function test_checkout_rejects_insufficient_stock_without_creating_a_transaction(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Minuman']);
        $product = Product::create([
            'barcode' => 'POS-002',
            'name' => 'Air Mineral',
            'category_id' => $category->id,
            'cost_price' => 2,
            'selling_price' => 5,
            'stock' => 1,
            'min_stock' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('pos'))
            ->post(route('pos.checkout'), [
                'cart' => [
                    ['product_id' => $product->id, 'quantity' => 2],
                ],
                'payment_method' => 'qris',
                'discount_percent' => 0,
            ]);

        $response->assertRedirect(route('pos'))->assertSessionHasErrors('cart');
        $this->assertDatabaseCount('transactions', 0);
        $this->assertSame(1, $product->refresh()->stock);
    }

    public function test_cash_checkout_rejects_insufficient_payment(): void
    {
        $user = User::factory()->create(['role' => 'kasir']);
        $category = Category::create(['name' => 'Snack']);
        $product = Product::create([
            'barcode' => 'POS-003',
            'name' => 'Keripik',
            'category_id' => $category->id,
            'cost_price' => 3,
            'selling_price' => 10,
            'stock' => 3,
            'min_stock' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('pos'))
            ->post(route('pos.checkout'), [
                'cart' => [
                    ['product_id' => $product->id, 'quantity' => 1],
                ],
                'payment_method' => 'cash',
                'discount_percent' => 0,
                'amount_paid' => 9,
            ]);

        $response->assertRedirect(route('pos'))->assertSessionHasErrors('amount_paid');
        $this->assertSame(0, Transaction::count());
        $this->assertSame(3, $product->refresh()->stock);
        $this->assertSame(0, TransactionDetail::count());
    }
}
