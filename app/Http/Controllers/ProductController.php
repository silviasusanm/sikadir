<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar semua produk.
     */
    public function index()
{
    $products = Product::with('category')->get();

    $categories = Category::all();

    return view('products.index', compact('products', 'categories'));
}

    /**
     * Menyimpan produk baru ke database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'          => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'barcode'        => 'nullable|string|max:255',
        ]);

        // Ambil nilai harga baik dari input name="price" maupun "selling_price"
        $sellingPrice = $request->input('price', $request->input('selling_price', 0));

        Product::create([
            'name'          => $validated['name'],
            'selling_price' => $sellingPrice,
            'stock'         => $validated['stock'],
            'barcode'       => $validated['barcode'] ?? null,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
    }

    /**
     * Memperbarui data produk yang ada.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'price'          => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'barcode'        => 'nullable|string|max:255',
        ]);

        // Ambil nilai harga dari input form
        $sellingPrice = $request->input('price', $request->input('selling_price', $product->selling_price));

        $product->update([
            'name'          => $validated['name'],
            'selling_price' => $sellingPrice,
            'stock'         => $validated['stock'],
            'barcode'       => $validated['barcode'] ?? $product->barcode,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
    }
}