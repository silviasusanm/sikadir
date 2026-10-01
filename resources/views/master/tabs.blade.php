<div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex justify-between items-center text-sm font-medium">
    <a href="{{ route('products.index') }}" class="flex-1 text-center py-2.5 rounded-xl transition flex items-center justify-center gap-2 {{ request()->routeIs('products*') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
        <i class="fa-solid fa-box font-normal"></i> Master Produk / Barang
    </a>
    <a href="{{ route('categories.index') }}" class="flex-1 text-center py-2.5 rounded-xl transition flex items-center justify-center gap-2 {{ request()->routeIs('categories*') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
        <i class="fa-solid fa-tag font-normal"></i> Master Kategori
    </a>
    <a href="{{ route('users.index') }}" class="flex-1 text-center py-2.5 rounded-xl transition flex items-center justify-center gap-2 {{ request()->routeIs('users*') ? 'bg-indigo-600 text-white font-semibold shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
        <i class="fa-solid fa-user-group font-normal"></i> Master Pengguna (User)
    </a>
</div>