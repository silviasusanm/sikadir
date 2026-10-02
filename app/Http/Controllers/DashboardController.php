<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $todayTransactions = Transaction::query()->whereDate('created_at', $today);

        $totalOmzet = (clone $todayTransactions)->sum('total_amount');
        $totalTransaksi = (clone $todayTransactions)->count();
        $totalLaba = TransactionDetail::query()
            ->whereHas('transaction', fn ($query) => $query->whereDate('created_at', $today))
            ->sum(DB::raw('subtotal - (cost_price * quantity)'));

        return view('dashboard', [
            'totalOmzet' => $totalOmzet,
            'totalTransaksi' => $totalTransaksi,
            'rataRataTransaksi' => $totalTransaksi > 0 ? $totalOmzet / $totalTransaksi : 0,
            'totalLaba' => $totalLaba,
            'lowStockProducts' => Product::query()
                ->with('category')
                ->whereColumn('stock', '<=', 'min_stock')
                ->orderBy('stock')
                ->get(),
            'recentTransactions' => Transaction::query()
                ->with('user')
                ->whereDate('created_at', $today)
                ->latest()
                ->limit(8)
                ->get(),
        ]);
    }
}
