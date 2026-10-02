<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Tampilkan daftar kategori
     */
    public function index(): View
    {
        $categories = Category::withCount('products')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori
     */
    public function create(): View
    {
        return view('categories.create', ['category' => new Category]);
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit(Category $category): View
    {
        return view('categories.create', compact('category'));
    }

    /**
     * Update data kategori
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori
     */
    public function destroy(Category $category): RedirectResponse
    {
        if ($category->products()->exists()) {
            return redirect()->route('categories.index')
                ->withErrors(['category' => 'Kategori yang masih memiliki produk tidak dapat dihapus.']);
        }

        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}
