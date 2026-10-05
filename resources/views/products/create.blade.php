<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru - Sikadir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-4">

    <div class="bg-white w-full max-w-xl rounded-3xl shadow-xl overflow-hidden border border-slate-100">
        
        <!-- Header -->
        <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-lg text-slate-900">Tambah Produk Baru</h3>
            <a href="{{ route('products.index') }}" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </a>
        </div>

        <!-- Form -->
        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            @if($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif
            
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Kode Barang / Barcode ID</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="misal: BRG-008" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                </div>
                
                <div>
                    <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama lengkap barang" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                </div>


                {{-- FOTO PRODUK --}}
                <div class="">
                    <label for="image" class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Foto Produk (opsional)</label>
                    <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-indigo-600">
                    <p class="mt-1 text-xs text-slate-400">JPG / PNG / WEBP, maksimal 2 MB.</p>
                    @error('image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Kategori</label>
                        <select name="category_id" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Harga Beli (Modal)</label>
                        <input type="number" name="cost_price" value="{{ old('cost_price', 0) }}" min="0" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Harga Jual</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', 0) }}" min="0" step="0.01" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Stok Awal</label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider text-slate-500 mb-1.5">Minimal Stok Warning</label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" min="0" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition" required>
                    </div>
                </div>
            </div>

            <!-- Footer Aksi -->
            <div class="pt-4 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm hover:bg-slate-50 transition text-center">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold text-sm shadow-md shadow-indigo-200 transition">Simpan Produk</button>
            </div>
        </form>

    </div>

</body>
</html>