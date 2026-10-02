<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        ['startDate' => $startDate, 'endDate' => $endDate, 'start' => $start, 'end' => $end] = $this->dateRange($request);

        $transactions = Transaction::query()->whereBetween('created_at', [$start, $end]);
        $transactionIds = (clone $transactions)->select('id');
        $details = TransactionDetail::query()
            ->whereIn('transaction_id', $transactionIds)
            ->with('product');

        $paymentMethods = (clone $transactions)
            ->select('payment_method', DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $topProducts = (clone $details)
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->limit(3)
            ->get();

        $totalLaba = (clone $details)
            ->sum(DB::raw('subtotal - (cost_price * quantity)'));

        return view('reports.index', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalOmzet' => (clone $transactions)->sum('total_amount'),
            'totalTransaksi' => (clone $transactions)->count(),
            'totalLaba' => $totalLaba,
            'produkTerjual' => (clone $details)->sum('quantity'),
            'tunai' => $paymentMethods['cash'] ?? 0,
            'qris' => $paymentMethods['qris'] ?? 0,
            'bank' => $paymentMethods['transfer'] ?? 0,
            'topProducts' => $topProducts,
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        ['startDate' => $startDate, 'endDate' => $endDate, 'start' => $start, 'end' => $end] = $this->dateRange($request);

        return response()->streamDownload(function () use ($start, $end): void {
            $file = fopen('php://output', 'w');

            if ($file === false) {
                throw new \RuntimeException('Gagal membuka aliran ekspor laporan.');
            }

            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'ID Transaksi', 'Tanggal', 'Kasir', 'Metode Pembayaran', 'Produk', 'Barcode',
                'Jumlah', 'Harga Jual', 'Harga Modal', 'Subtotal Bersih',
            ], ',', '"', '');

            TransactionDetail::query()
                ->with(['product', 'transaction.user'])
                ->whereHas('transaction', fn ($query) => $query->whereBetween('created_at', [$start, $end]))
                ->orderBy('id')
                ->chunkById(500, function ($details) use ($file): void {
                    foreach ($details as $detail) {
                        fputcsv($file, [
                            $this->safeSpreadsheetText($detail->transaction->transaction_number ?? ''),
                            $detail->transaction->created_at->format('Y-m-d H:i:s'),
                            $this->safeSpreadsheetText($detail->transaction->user?->name ?? 'Kasir'),
                            $this->safeSpreadsheetText($detail->transaction->payment_method),
                            $this->safeSpreadsheetText($detail->product?->name ?? 'Produk dihapus'),
                            $this->safeSpreadsheetText($detail->product?->barcode ?? ''),
                            $detail->quantity,
                            $detail->price,
                            $detail->cost_price,
                            $detail->subtotal,
                        ], ',', '"', '');
                    }
                });

            fclose($file);
        }, "sikadir-laporan-{$startDate}-{$endDate}.csv", [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array{startDate: string, endDate: string, start: Carbon, end: Carbon}
     */
    private function dateRange(Request $request): array
    {
        $dates = $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ]);

        $startDate = $dates['start_date'] ?? Carbon::today()->toDateString();
        $endDate = $dates['end_date'] ?? Carbon::today()->toDateString();

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'start' => Carbon::parse($startDate)->startOfDay(),
            'end' => Carbon::parse($endDate)->endOfDay(),
        ];
    }

    private function safeSpreadsheetText(string $value): string
    {
        return preg_match('/^[=+\-@\t\r]/', $value) === 1 ? "'".$value : $value;
    }
}
