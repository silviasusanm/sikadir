<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::with('category')->latest()->get(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->normalizeFormInput($request);
        $validated = $request->validate($this->rules());

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product): View
    {
        return view('products.edit', [
            'product' => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->normalizeFormInput($request);
        $validated = $request->validate($this->rules($product));

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->transactionDetails()->exists()) {
            return redirect()->route('products.index')
                ->withErrors(['product' => 'Produk yang sudah tercatat dalam transaksi tidak dapat dihapus.']);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Accept the field names used by the existing product forms.
     */
    private function normalizeFormInput(Request $request): void
    {
        $request->merge([
            'barcode' => $request->input('barcode', $request->input('code')),
            'cost_price' => $request->input('cost_price', $request->input('buy_price')),
            'selling_price' => $request->input(
                'selling_price',
                $request->input('sell_price', $request->input('price'))
            ),
            'category_id' => $request->input('category_id', $request->input('category')),
        ]);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    private function rules(?Product $product = null): array
    {
        return [
            'barcode' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'barcode')->ignore($product?->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'cost_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'selling_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
