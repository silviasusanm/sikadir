@extends('layouts.app')

@section('title', 'Master Kategori')

@section('content')

    <!-- Sub-Tab Navigasi Data Master -->
    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex items-center justify-between max-w-4xl mx-auto">
        <!-- 1. Master Produk -->
        <a href="{{ Route::has('products.index') ? route('products.index') : url('/products') }}" 
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 {{ request()->routeIs('products*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-box"></i>
            <span>Master Produk / Barang</span>
        </a>

        <!-- 2. Master Kategori -->
        <a href="{{ Route::has('categories.index') ? route('categories.index') : url('/categories') }}" 
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 {{ request()->routeIs('categories*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-tags"></i>
            <span>Master Kategori</span>
        </a>

        <!-- 3. Master Pengguna -->
        <a href="{{ Route::has('users.index') ? route('users.index') : url('/users') }}" 
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 {{ request()->routeIs('users*') ? 'bg-indigo-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            <i class="fa-solid fa-users"></i>
            <span>Master Pengguna (User)</span>
        </a>
    </div>

    <!-- Container Utama Kategori Barang -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        
        <!-- Header Halaman & Tombol Tambah -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-xl font-bold text-slate-800">Kategori Barang</h2>
                <p class="text-xs text-slate-500 mt-1">Pengelompokkan produk untuk memudahkan pencarian kasir.</p>
            </div>
            @if(Route::has('categories.create'))
                <a href="{{ route('categories.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Tambah Kategori
                </a>
            @else
                <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Tambah Kategori
                </button>
            @endif
        </div>

        <!-- Alert Pesan Sukses -->
        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold flex items-center gap-2">
                <i class="fa-solid fa-circle-check text-emerald-600"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Grid Kartu Kategori -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            @forelse($categories as $category)
                <div class="bg-slate-50/60 border border-slate-200/80 p-4 rounded-2xl flex items-center justify-between hover:bg-slate-100/60 transition cursor-pointer group relative">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">{{ $category->name }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            {{ $category->products_count ?? $category->products->count() ?? 0 }} Produk Terdaftar
                        </p>
                    </div>
                    
                    <div class="flex items-center gap-1.5">
                        <!-- Tombol Edit & Hapus Opsional jika diarahkan lewat route -->
                        @if(Route::has('categories.edit'))
                            <a href="{{ route('categories.edit', $category->id) }}" class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition" title="Edit Kategori">
                                <i class="fa-regular fa-pen-to-square text-xs"></i>
                            </a>
                        @endif

                        @if(Route::has('categories.destroy'))
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:border-rose-200 transition" title="Hapus Kategori">
                                    <i class="fa-regular fa-trash-can text-xs"></i>
                                </button>
                            </form>
                        @else
                            <div class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:border-indigo-200 transition">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-slate-400 bg-slate-50/50 rounded-2xl border border-dashed border-slate-200">
                    <i class="fa-solid fa-tags text-3xl mb-2 text-slate-300 block"></i>
                    <span>Belum ada kategori terdaftar. Klik tombol <strong>Tambah Kategori</strong> di atas.</span>
                </div>
            @endforelse

        </div>

    </div>

@endsection