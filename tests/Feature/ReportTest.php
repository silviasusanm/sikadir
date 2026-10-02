<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_totals_and_top_products_use_saved_sales_in_the_selected_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Snack']);
        $product = Product::create([
            'barcode' => 'RPT-001',
            'name' => 'Keripik',
            'category_id' => $category->id,
            'cost_price' => 60,
            'selling_price' => 100,
            'stock' => 3,
            'min_stock' => 1,
        ]);
        $transaction = Transaction::create([
            'user_id' => $admin->id,
            'transaction_number' => 'TRX-REPORT-001',
            'subtotal' => 200,
            'discount_amount' => 20,
            'total_amount' => 180,
            'payment_method' => 'cash',
            'amount_paid' => 200,
            'change_due' => 20,
        ]);
        $transaction->details()->create([
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100,
            'subtotal' => 180,
            'cost_price' => 60,
        ]);
        $today = Carbon::today()->toDateString();

        $response = $this->actingAs($admin)->get(route('reports.index', [
            'start_date' => $today,
            'end_date' => $today,
        ]));

        $response->assertOk()
            ->assertViewHas('totalOmzet', 180)
            ->assertViewHas('totalTransaksi', 1)
            ->assertViewHas('totalLaba', 60)
            ->assertViewHas('produkTerjual', 2)
            ->assertViewHas('topProducts', fn ($products): bool => $products->first()->total_qty === 2);
    }

    public function test_report_export_downloads_csv_for_the_selected_range(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $today = Carbon::today()->toDateString();

        $this->actingAs($admin)
            ->get(route('reports.export', ['start_date' => $today, 'end_date' => $today]))
            ->assertDownload("sikadir-laporan-{$today}-{$today}.csv");
    }

    public function test_report_rejects_a_date_range_that_ends_before_it_starts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('reports.index'))
            ->get(route('reports.index', [
                'start_date' => '2026-10-02',
                'end_date' => '2026-10-01',
            ]))
            ->assertRedirect(route('reports.index'))
            ->assertSessionHasErrors('end_date');
    }

    public function test_dashboard_shows_live_low_stock_products_instead_of_sample_data(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::create(['name' => 'Minuman']);
        Product::create([
            'barcode' => 'DASH-001',
            'name' => 'Stok Menipis',
            'category_id' => $category->id,
            'cost_price' => 1000,
            'selling_price' => 2000,
            'stock' => 1,
            'min_stock' => 5,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Stok Menipis')
            ->assertDontSee('Semua stok produk mencukupi.');
    }
}
