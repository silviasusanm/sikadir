@extends('layouts.app')

@section('title', 'Kelola Master Produk')

@section('content')

    {{-- SUB TAB DATA MASTER --}}
    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex items-center justify-between max-w-4xl mx-auto">

        <a href="{{ route('products.index') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl bg-indigo-600 text-white shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-box"></i>
            <span>Master Produk / Barang</span>
        </a>

        <a href="{{ Route::has('categories.index') ? route('categories.index') : url('/categories') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 flex items-center justify-center gap-2">
            <i class="fa-solid fa-tags"></i>
            <span>Master Kategori</span>
        </a>

        <a href="{{ Route::has('users.index') ? route('users.index') : url('/users') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 flex items-center justify-center gap-2">
            <i class="fa-solid fa-users"></i>
            <span>Master Pengguna (User)</span>
        </a>

    </div>


    {{-- CONTAINER PRODUK --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Kelola Master Produk
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    Kelola daftar barang, harga modal, harga jual, dan stok minimal.
                </p>
            </div>

            {{-- TOMBOL TAMBAH --}}
            <button
                type="button"
                onclick="openProductModal()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition"
            >
                <i class="fa-solid fa-plus"></i>
                Tambah Produk Baru
            </button>

        </div>


        {{-- PENCARIAN --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-5 flex gap-2">
            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400"><i class="fa-solid fa-magnifying-glass text-xs"></i></span>
                <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Cari nama produk atau kode barcode..." class="w-full h-10 pl-9 pr-3 text-xs rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none">
            </div>
            <button type="submit" class="px-4 h-10 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold">Cari</button>
            @if(!empty($search))
                <a href="{{ route('products.index') }}" class="px-4 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold flex items-center">Reset</a>
            @endif
        </form>

        {{-- ALERT --}}
        @if(session('success'))

            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold flex items-center gap-2">

                <i class="fa-solid fa-circle-check text-emerald-600"></i>

                <span>{{ session('success') }}</span>

            </div>

        @endif

        @if($errors->any())
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold">
                {{ $errors->first() }}
            </div>
        @endif


        {{-- TABEL --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse text-xs">

                <thead>

                    <tr class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">

                        <th class="py-3.5 px-4">
                            Foto
                        </th>

                        <th class="py-3.5 px-4">
                            Kode/Barcode
                        </th>

                        <th class="py-3.5 px-4">
                            Nama Produk
                        </th>

                        <th class="py-3.5 px-4">
                            Kategori
                        </th>

                        <th class="py-3.5 px-4">
                            Harga Beli (Modal)
                        </th>

                        <th class="py-3.5 px-4">
                            Harga Jual
                        </th>

                        <th class="py-3.5 px-4">
                            Stok Saat Ini
                        </th>

                        <th class="py-3.5 px-4">
                            Min. Stok
                        </th>

                        <th class="py-3.5 px-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">

                    @forelse($products as $product)

                        <tr class="hover:bg-slate-50/50 transition">

                            <td class="py-3.5 px-4">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy" class="w-12 h-12 rounded-xl object-cover border border-slate-200">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-300 flex items-center justify-center">
                                        <i class="fa-regular fa-image"></i>
                                    </div>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                {{ $product->barcode }}
                            </td>


                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $product->name }}
                            </td>


                            <td class="py-3.5 px-4">

                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-[11px] font-medium">

                                    {{ $product->category->name ?? 'Lainnya' }}

                                </span>

                            </td>


                            <td class="py-3.5 px-4">

                                Rp
                                {{ number_format($product->cost_price, 0, ',', '.') }}

                            </td>


                            <td class="py-3.5 px-4 font-bold text-indigo-600">

                                Rp
                                {{ number_format($product->selling_price, 0, ',', '.') }}

                            </td>


                            <td class="py-3.5 px-4 font-bold
                                {{ $product->stock <= ($product->min_stock ?? 5)
                                    ? 'text-amber-500'
                                    : 'text-slate-800' }}">

                                {{ $product->stock }} pcs

                            </td>


                            <td class="py-3.5 px-4 text-slate-400">

                                {{ $product->min_stock ?? 5 }} pcs

                            </td>


                            <td class="py-3.5 px-4 text-center">

                                <div class="flex items-center justify-center space-x-2">

                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('products.edit', $product->id) }}"
                                        class="text-indigo-600 hover:text-indigo-800 p-1.5 transition"
                                        title="Edit"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>


                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('products.destroy', $product->id) }}"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-rose-500 hover:text-rose-700 p-1.5 transition"
                                            title="Hapus"
                                        >
                                            <i class="fa-regular fa-trash-can text-sm"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="9" class="text-center py-12 text-slate-400">

                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300 block"></i>

                                <span>
                                    Belum ada data produk tersimpan.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($products->hasPages())
            <div class="mt-5">{{ $products->links() }}</div>
        @endif

    </div>



    {{-- =====================================================
         MODAL TAMBAH PRODUK
    ====================================================== --}}

    <div
        id="productModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4"
    >

        <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-7">


            {{-- HEADER MODAL --}}
            <div class="flex justify-between items-center">

                <h2 class="text-xl font-bold text-slate-800">
                    Tambah Produk Baru
                </h2>

                <button
                    type="button"
                    onclick="closeProductModal()"
                    class="text-2xl text-slate-400 hover:text-slate-600"
                >
                    &times;
                </button>

            </div>


            <div class="border-b border-slate-200 mt-4 mb-5"></div>


            {{-- FORM --}}
            <form
                action="{{ route('products.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf


                {{-- KODE --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kode Barang / Barcode ID
                    </label>

                    <input
                        type="text"
                        name="barcode"
                        value="{{ old('barcode') }}"
                        placeholder="misal: BRG-008"
                        required
                        class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                    >

                </div>


                {{-- NAMA PRODUK --}}
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Nama lengkap barang"
                        required
                        class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                    >

                </div>


                {{-- KATEGORI + URL GAMBAR --}}
                <div class="mb-4">


                    {{-- KATEGORI --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Kategori
                        </label>

                        <select
                            name="category_id"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                            <option value="">
                                Pilih Kategori
                            </option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>{{ $category->name }}</option>
                            @endforeach

                        </select>

                    </div>

                </div>



                {{-- FOTO PRODUK --}}
                <div class="mb-4 md:col-span-2">
                    <label for="image" class="block text-sm font-semibold text-slate-700 mb-2">Foto Produk (opsional)</label>
                    <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm text-slate-600 file:mr-3 file:rounded-lg file:border-0 file:bg-indigo-50 file:px-3 file:py-2 file:font-semibold file:text-indigo-600">
                    <p class="mt-1 text-xs text-slate-400">JPG / PNG / WEBP, maksimal 2 MB.</p>
                    @error('image')<p class="mt-1 text-sm text-rose-600">{{ $message }}</p>@enderror
                </div>

                {{-- HARGA --}}
                <div class="grid grid-cols-2 gap-2.5 mb-4">


                    {{-- HARGA BELI --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga Beli (Modal)
                        </label>

                        <input
                            type="number"
                            name="cost_price"
                            value="{{ old('cost_price', 0) }}"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>


                    {{-- HARGA JUAL --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga Jual
                        </label>

                        <input
                            type="number"
                            name="price"
                            value="{{ old('price', 0) }}"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>

                </div>


                {{-- STOK --}}
                <div class="grid grid-cols-2 gap-2.5">


                    {{-- STOK AWAL --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Stok Awal
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="{{ old('stock', 0) }}"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>


                    {{-- MINIMAL STOK --}}
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Minimal Stok Warning
                        </label>

                        <input
                            type="number"
                            name="min_stock"
                            value="{{ old('min_stock', 5) }}"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>

                </div>


                {{-- BUTTON --}}
                <div class="flex justify-end gap-2.5 mt-6">

                    <button
                        type="button"
                        onclick="closeProductModal()"
                        class="px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-semibold"
                    >
                        Batal
                    </button>


                    <button
                        type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-semibold"
                    >

                        <i class="fa-solid fa-floppy-disk mr-1"></i>

                        Simpan Produk

                    </button>

                </div>

            </form>

        </div>

    </div>



    {{-- =====================================================
         JAVASCRIPT MODAL
    ====================================================== --}}

    <script>

        function openProductModal() {

            const modal = document.getElementById('productModal');

            modal.classList.remove('hidden');

            modal.classList.add('flex');

            document.body.classList.add('overflow-hidden');

        }


        function closeProductModal() {

            const modal = document.getElementById('productModal');

            modal.classList.add('hidden');

            modal.classList.remove('flex');

            document.body.classList.remove('overflow-hidden');

        }


        // Klik area luar modal untuk menutup
        document.getElementById('productModal').addEventListener('click', function(event) {

            if (event.target === this) {

                closeProductModal();

            }

        });


        // Tekan ESC untuk menutup
        document.addEventListener('keydown', function(event) {

            if (event.key === 'Escape') {

                closeProductModal();

            }

        });

        @if($errors->any() && old('barcode'))
            openProductModal();
        @endif

    </script>

@endsection