@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Edit Produk</h2>

        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="barcode" class="block text-sm font-semibold text-slate-700 mb-1">Kode / Barcode</label>
                    <input id="barcode" name="barcode" value="{{ old('barcode', $product->barcode) }}" required class="w-full rounded-xl border-slate-300">
                    @error('barcode')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Produk</label>
                    <input id="name" name="name" value="{{ old('name', $product->name) }}" required class="w-full rounded-xl border-slate-300">
                    @error('name')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                {{-- FOTO PRODUK --}}
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-semibold text-slate-700 mb-2">Foto Produk (opsional)</label>
        @if($product->image_url)
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-24 h-24 rounded-xl object-cover border border-slate-200 mb-2">
        @endif

                    <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-indigo-600">
                    <p class="mt-1 text-xs text-slate-400">JPG / PNG / WEBP, maksimal 2 MB.</p>
                    @error('image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-1">Kategori</label>
                    <select id="category_id" name="category_id" required class="w-full rounded-xl border-slate-300">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="cost_price" class="block text-sm font-semibold text-slate-700 mb-1">Harga Modal</label>
                    <input id="cost_price" name="cost_price" type="number" min="0" step="0.01" value="{{ old('cost_price', $product->cost_price) }}" required class="w-full rounded-xl border-slate-300">
                    @error('cost_price')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="selling_price" class="block text-sm font-semibold text-slate-700 mb-1">Harga Jual</label>
                    <input id="selling_price" name="selling_price" type="number" min="0" step="0.01" value="{{ old('selling_price', $product->selling_price) }}" required class="w-full rounded-xl border-slate-300">
                    @error('selling_price')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="stock" class="block text-sm font-semibold text-slate-700 mb-1">Stok</label>
                    <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" required class="w-full rounded-xl border-slate-300">
                    @error('stock')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="min_stock" class="block text-sm font-semibold text-slate-700 mb-1">Stok Minimum</label>
                    <input id="min_stock" name="min_stock" type="number" min="0" value="{{ old('min_stock', $product->min_stock) }}" required class="w-full rounded-xl border-slate-300">
                    @error('min_stock')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-sm">Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
