<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk {{ $transaction->transaction_number }} - {{ config('app.name', 'Sikadir') }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Courier New', monospace; font-size: 12px; color: #111; background: #f1f5f9; margin: 0; padding: 16px; }
        .receipt { width: 300px; margin: 0 auto; background: #fff; padding: 16px; border-radius: 8px; }
        .center { text-align: center; }
        .row { display: flex; justify-content: space-between; gap: 8px; }
        .muted { color: #555; }
        hr { border: 0; border-top: 1px dashed #999; margin: 10px 0; }
        .actions { width: 300px; margin: 12px auto 0; display: flex; gap: 8px; }
        .actions button, .actions a { flex: 1; text-align: center; padding: 10px; border-radius: 8px; border: 0; font-size: 13px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-print { background: #4f46e5; color: #fff; }
        .btn-back { background: #e2e8f0; color: #334155; }
        @media print {
            body { background: #fff; padding: 0; }
            .receipt { width: 100%; border-radius: 0; padding: 0; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="center">
            <strong style="font-size:16px;">{{ config('app.name', 'Sikadir') }}</strong><br>
            <span class="muted">Sistem Kasir Digital Terpadu</span>
        </div>
        <hr>
        <div class="row"><span>No</span><span>{{ $transaction->transaction_number }}</span></div>
        <div class="row"><span>Tanggal</span><span>{{ $transaction->created_at->format('d/m/Y H:i') }}</span></div>
        <div class="row"><span>Kasir</span><span>{{ $transaction->user->name ?? '-' }}</span></div>
        <hr>

        @foreach($transaction->details as $detail)
            <div>{{ $detail->product->name ?? 'Produk dihapus' }}</div>
            <div class="row muted">
                <span>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</span>
                <span>{{ number_format($detail->subtotal, 0, ',', '.') }}</span>
            </div>
        @endforeach

        <hr>
        <div class="row"><span>Subtotal</span><span>Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</span></div>
        @if($transaction->discount_amount > 0)
            <div class="row"><span>Diskon</span><span>- Rp {{ number_format($transaction->discount_amount, 0, ',', '.') }}</span></div>
        @endif
        <div class="row"><strong>Total</strong><strong>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</strong></div>
        <div class="row"><span>Bayar ({{ strtoupper($transaction->payment_method) }})</span><span>Rp {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span></div>
        <div class="row"><span>Kembali</span><span>Rp {{ number_format($transaction->change_due, 0, ',', '.') }}</span></div>
        <hr>
        <div class="center muted">Terima kasih sudah berbelanja!</div>
    </div>

    <div class="actions">
        <a href="{{ route('pos') }}" class="btn-back">Kembali</a>
        <button type="button" class="btn-print" onclick="window.print()">Cetak</button>
    </div>
</body>
</html>
