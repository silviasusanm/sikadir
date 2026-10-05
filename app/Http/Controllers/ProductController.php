<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('q', ''));

        $products = Product::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('barcode', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
            'categories' => Category::orderBy('name')->get(),
            'search' => $search,
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

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

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

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        } else {
            unset($validated['image']);
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->transactionDetails()->exists()) {
            return redirect()->route('products.index')
                ->withErrors(['product' => 'Produk yang sudah tercatat dalam transaksi tidak dapat dihapus.']);
        }

        if ($product->image) {
            Storage::disk('public')->delete($product->image);
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
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'cost_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'selling_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'stock' => ['required', 'integer', 'min:0'],
            'min_stock' => ['required', 'integer', 'min:0'],
        ];
    }
}
