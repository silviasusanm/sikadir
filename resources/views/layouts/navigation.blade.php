<nav class="bg-white border-b border-slate-200/80 px-6 py-2 flex items-center space-x-2 text-xs font-semibold flex-none z-0 relative">
    
    <!-- DASHBOARD -->
    <a href="{{ route('dashboard') }}" 
       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-[#4F46E5] font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
        <i data-lucide="layout-grid" class="w-4 h-4 {{ request()->routeIs('dashboard') ? 'text-[#4F46E5]' : 'text-slate-400' }}"></i>
        <span>Dashboard</span>
    </a>

    <!-- POS / TRANSAKSI -->
    <a href="{{ route('pos') }}" 
       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition {{ request()->routeIs('pos') ? 'bg-indigo-50 text-[#4F46E5] font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
        <i data-lucide="shopping-cart" class="w-4 h-4 {{ request()->routeIs('pos') ? 'text-[#4F46E5]' : 'text-slate-400' }}"></i>
        <span>POS / Transaksi</span>
    </a>

    <!-- DATA MASTER -->
    <a href="{{ route('products.index') }}" 
       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition {{ request()->routeIs('products.*') ? 'bg-indigo-50 text-[#4F46E5] font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
        <i data-lucide="database" class="w-4 h-4 {{ request()->routeIs('products.*') ? 'text-[#4F46E5]' : 'text-slate-400' }}"></i>
        <span>Data Master</span>
    </a>

    <!-- LAPORAN & EKSPOR -->
    <a href="{{ route('reports.index') }}" 
       class="flex items-center space-x-2 px-4 py-2 rounded-xl transition {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-[#4F46E5] font-bold border border-indigo-100 shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
        <i data-lucide="file-text" class="w-4 h-4 {{ request()->routeIs('reports.*') ? 'text-[#4F46E5]' : 'text-slate-400' }}"></i>
        <span>Laporan & Ekspor</span>
    </a>

</nav>