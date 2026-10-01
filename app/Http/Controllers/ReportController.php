<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate   = $request->input('end_date', Carbon::today()->toDateString());

        // 1. Deteksi Kolom Total di Tabel transactions
        $amountColumn = null;
        foreach (['total_price', 'grand_total', 'total_amount', 'total_bayar', 'total'] as $col) {
            if (Schema::hasColumn('transactions', $col)) {
                $amountColumn = $col;
                break;
            }
        }

        $transactionsQuery = Transaction::whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        $totalOmzet     = $amountColumn ? (clone $transactionsQuery)->sum($amountColumn) : 0;
        $totalTransaksi = (clone $transactionsQuery)->count();

        // 2. Rekap Metode Pembayaran
        $paymentMethods = collect();
        if ($amountColumn && Schema::hasColumn('transactions', 'payment_method')) {
            $paymentMethods = (clone $transactionsQuery)
                ->select('payment_method', DB::raw("SUM({$amountColumn}) as total"))
                ->groupBy('payment_method')
                ->pluck('total', 'payment_method');
        }

        $tunai = $paymentMethods['cash'] ?? $paymentMethods['tunai'] ?? 0;
        $qris  = $paymentMethods['qris'] ?? 0;
        $bank  = $paymentMethods['bank'] ?? $paymentMethods['transfer'] ?? 0;

        $produkTerjual = 0;
        $totalLaba     = 0;
        $topProducts   = collect();

        // 3. Deteksi apakah ada tabel detail transaksi
        $detailModel = new TransactionDetail();
        $detailTable = $detailModel->getTable();

        if (Schema::hasTable($detailTable)) {
            $transactionIds = (clone $transactionsQuery)->pluck('id');

            $details = TransactionDetail::whereIn('transaction_id', $transactionIds)
                ->with('product')
                ->get();

            $produkTerjual = $details->sum('quantity');

            if ($totalOmzet == 0 && $details->isNotEmpty()) {
                $totalOmzet = $details->sum(function ($item) {
                    return $item->subtotal ?? (($item->price ?? 0) * ($item->quantity ?? 0));
                });
            }

            $totalLaba = $details->sum(function ($item) {
                $itemPrice = $item->price ?? 0;
                $costPrice = $item->product->cost_price ?? ($itemPrice * 0.7);
                return ($itemPrice - $costPrice) * ($item->quantity ?? 0);
            });

            $topProducts = TransactionDetail::whereIn('transaction_id', $transactionIds)
                ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
                ->groupBy('product_id')
                ->orderByDesc('total_qty')
                ->take(3)
                ->with('product')
                ->get();
        }

        return view('reports.index', compact(
            'startDate',
            'endDate',
            'totalOmzet',
            'totalTransaksi',
            'totalLaba',
            'produkTerjual',
            'tunai',
            'qris',
            'bank',
            'topProducts'
        ));
    }
}