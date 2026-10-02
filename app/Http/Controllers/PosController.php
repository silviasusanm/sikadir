<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PosController extends Controller
{
    public function index(): View
    {
        $products = Product::with('category')->orderBy('name')->get();

        return view('pos.index', [
            'products' => $products,
            'posProducts' => $products->map(fn (Product $product): array => [
                'id' => $product->id,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'price' => (float) $product->selling_price,
                'stock' => $product->stock,
                'category' => $product->category?->name ?? 'Lainnya',
            ])->values(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function checkout(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cart' => ['required', 'array', 'min:1'],
            'cart.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'cart.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'payment_method' => ['required', 'in:cash,qris,transfer'],
            'discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'amount_paid' => ['nullable', 'numeric', 'min:0'],
        ]);

        $transaction = DB::transaction(function () use ($validated): Transaction {
            $quantities = collect($validated['cart'])
                ->groupBy('product_id')
                ->map(fn (Collection $items): int => $items->sum('quantity'))
                ->sortKeys();

            $products = collect();
            foreach ($quantities as $productId => $quantity) {
                $product = Product::query()->whereKey($productId)->lockForUpdate()->firstOrFail();

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'cart' => "Stok {$product->name} tidak mencukupi. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $products->put($productId, $product);
            }

            $subtotal = round($products->sum(
                fn (Product $product, int|string $productId): float => (float) $product->selling_price * $quantities->get($productId)
            ), 2);
            $discountAmount = round($subtotal * (float) $validated['discount_percent'] / 100, 2);
            $total = round($subtotal - $discountAmount, 2);
            $amountPaid = $validated['payment_method'] === 'cash'
                ? (float) ($validated['amount_paid'] ?? 0)
                : $total;

            if ($validated['payment_method'] === 'cash' && $amountPaid < $total) {
                throw ValidationException::withMessages([
                    'amount_paid' => 'Uang yang diterima tidak boleh kurang dari total pembayaran.',
                ]);
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'],
                'amount_paid' => $amountPaid,
                'change_due' => round($amountPaid - $total, 2),
            ]);

            $transaction->update([
                'transaction_number' => sprintf('TRX-%s-%06d', now()->format('Ymd'), $transaction->id),
            ]);

            $remainingDiscount = $discountAmount;
            $lastProductId = $quantities->keys()->last();

            foreach ($products as $productId => $product) {
                $quantity = $quantities->get($productId);
                $lineSubtotal = round((float) $product->selling_price * $quantity, 2);
                $lineDiscount = (string) $productId === (string) $lastProductId
                    ? $remainingDiscount
                    : ($subtotal > 0 ? round($discountAmount * $lineSubtotal / $subtotal, 2) : 0);
                $remainingDiscount = round($remainingDiscount - $lineDiscount, 2);

                $transaction->details()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->selling_price,
                    'subtotal' => round($lineSubtotal - $lineDiscount, 2),
                    'cost_price' => $product->cost_price,
                ]);

                $product->decrement('stock', $quantity);
            }

            return $transaction;
        });

        return redirect()
            ->route('pos')
            ->with('success', "Transaksi {$transaction->transaction_number} berhasil disimpan.");
    }
}
