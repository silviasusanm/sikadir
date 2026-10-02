<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sikadir - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style> 
        body { font-family: 'Inter', sans-serif; } 
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-[#F3F4F8] text-slate-800 h-screen flex flex-col overflow-hidden">

    <!-- TOPBAR / NAVBAR ATAS -->
    <header class="bg-[#322F88] text-white px-6 py-3 flex items-center justify-between shadow-md flex-none z-10 relative">
        <div class="flex items-center space-x-3">
            <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-md">
                <i data-lucide="shopping-bag" class="w-5 h-5 text-white"></i>
            </div>
            <div>
                <h1 class="font-bold text-base tracking-wide leading-none">Sikadir</h1>
                <p class="text-[10px] text-indigo-200 mt-0.5">Sistem Kasir Digital Terpadu</p>
            </div>
        </div>

        <div class="flex items-center space-x-4 text-xs">
            <!-- Switcher Role Mode -->
            <div class="flex items-center bg-[#26236B] px-3 py-1.5 rounded-xl border border-indigo-400/30 gap-2.5">
                <span class="text-indigo-200 text-[11px] font-medium">Role Mode:</span>
                <div class="bg-[#4F46E5] text-white font-bold text-xs px-2.5 py-1 rounded-lg flex items-center space-x-1 shadow-sm">
                    <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                    <span>Admin / Pemilik</span>
                </div>
                <span class="text-indigo-500/50">|</span>
                <a href="{{ route('pos') }}" class="text-indigo-300 hover:text-white transition flex items-center space-x-1">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i>
                    <span>Kasir</span>
                </a>
            </div>

            <!-- Profile Info -->
            <div class="relative" x-data="{ openProfile: false }">
                <button @click="openProfile = !openProfile" @click.outside="openProfile = false" type="button" class="flex items-center space-x-2 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-xl border border-white/10 transition cursor-pointer">
                    <div class="w-6 h-6 rounded-full bg-indigo-300 text-[#322F88] flex items-center justify-center font-bold text-xs uppercase">
                        {{ auth()->check() ? substr(auth()->user()->name, 0, 1) : 'A' }}
                    </div>
                    <span class="font-semibold">{{ auth()->check() ? auth()->user()->name : 'Ahmad (Admin)' }}</span>
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-indigo-200 transition-transform duration-200" :class="openProfile ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="openProfile" x-transition class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-slate-700" x-cloak>
                    <div class="px-4 py-2 border-b border-slate-100">
                        <p class="font-bold text-xs text-[#4F46E5]">{{ auth()->check() ? auth()->user()->name : 'Ahmad' }}</p>
                        <p class="text-[11px] text-slate-400 font-medium truncate">{{ auth()->check() ? auth()->user()->email : 'admin@sikadir.com' }}</p>
                    </div>
                    <div class="border-t border-slate-100 pt-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2.5 px-4 py-2 text-xs font-bold text-rose-500 hover:bg-rose-50 transition text-left">
                                <i data-lucide="log-out" class="w-4 h-4 text-rose-500"></i>
                                <span>Keluar (Logout)</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- SUB-HEADER NAVIGASI -->
    <nav class="bg-white border-b border-slate-200/80 px-6 py-2 flex items-center space-x-2 text-xs font-semibold flex-none z-0 relative">
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2 rounded-xl bg-indigo-50 text-[#4F46E5] font-bold border border-indigo-100 transition shadow-sm">
            <i data-lucide="layout-grid" class="w-4 h-4 text-[#4F46E5]"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('pos') }}" class="flex items-center space-x-2 px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
            <i data-lucide="shopping-cart" class="w-4 h-4 text-slate-400"></i>
            <span>POS / Transaksi</span>
        </a>
        <a href="{{ route('products.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
            <i data-lucide="database" class="w-4 h-4 text-slate-400"></i>
            <span>Data Master</span>
        </a>
        <a href="{{ route('reports.index') }}" class="flex items-center space-x-2 px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 transition">
            <i data-lucide="file-text" class="w-4 h-4 text-slate-400"></i>
            <span>Laporan & Ekspor</span>
        </a>
    </nav>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 overflow-y-auto p-6 space-y-6 z-0 relative">
        
        <!-- KARTU STATISTIK -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">OMZET HARI INI</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h3>
                    <div class="flex items-center text-xs font-medium text-emerald-600 mt-2">
                        <i data-lucide="trending-up" class="w-4 h-4 mr-1"></i>
                        <span>Omzet hari ini</span>
                    </div>
                </div>
                <div class="bg-emerald-50 text-emerald-600 p-3.5 rounded-2xl flex items-center justify-center">
                    <i data-lucide="dollar-sign" class="w-6 h-6 stroke-2"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">TOTAL TRANSAKSI</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($totalTransaksi) }} Penjualan</h3>
                    <div class="flex items-center text-xs font-medium text-indigo-500 mt-2">
                        <i data-lucide="receipt" class="w-4 h-4 mr-1"></i>
                        <span>Rata-rata Rp {{ number_format($rataRataTransaksi, 0, ',', '.') }}/trx</span>
                    </div>
                </div>
                <div class="bg-indigo-50 text-indigo-600 p-3.5 rounded-2xl flex items-center justify-center">
                    <i data-lucide="ticket" class="w-6 h-6 stroke-2"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">LABA BERSIH</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalLaba, 0, ',', '.') }}</h3>
                    <div class="flex items-center text-xs font-medium text-emerald-600 mt-2">
                        <i data-lucide="percent" class="w-4 h-4 mr-1 stroke-[2.5]"></i>
                        <span>Laba kotor hari ini</span>
                    </div>
                </div>
                <div class="bg-blue-50 text-blue-600 p-3.5 rounded-2xl flex items-center justify-center">
                    <i data-lucide="clock-3" class="w-6 h-6 stroke-2"></i>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 flex justify-between items-center transition hover:shadow-md">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">STOK MENIPIS</p>
                    <h3 class="text-2xl font-bold text-amber-600 mt-1">{{ $lowStockProducts->count() }} Produk</h3>
                    <div class="flex items-center text-xs font-medium text-amber-600 mt-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4 mr-1"></i>
                        <span>Perlu Restock Segera</span>
                    </div>
                </div>
                <div class="bg-amber-50 text-amber-600 p-3.5 rounded-2xl flex items-center justify-center">
                    <i data-lucide="package-search" class="w-6 h-6 stroke-2"></i>
                </div>
            </div>
        </div>

        <!-- TABEL TRANSAKSI & STOK -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md">
                <div>
                    <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100">
                        <div class="flex items-center space-x-2 text-indigo-950 font-semibold text-lg">
                            <i data-lucide="history" class="w-5 h-5 text-indigo-600"></i>
                            <h2>Transaksi Penjualan Terakhir (Hari Ini)</h2>
                        </div>
                        <a href="{{ route('reports.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center transition">
                            Lihat Semua <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs font-semibold text-gray-400 bg-gray-50/50 rounded-lg">
                                    <th class="p-3">ID Transaksi</th>
                                    <th class="p-3">Waktu</th>
                                    <th class="p-3">Kasir</th>
                                    <th class="p-3">Metode</th>
                                    <th class="p-3">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 text-sm">
                                @forelse($recentTransactions as $transaction)
                                    <tr class="hover:bg-gray-50/50 transition">
                                        <td class="p-3 font-semibold text-indigo-600">{{ $transaction->transaction_number ?? 'TRX-' . $transaction->id }}</td>
                                        <td class="p-3 text-gray-500">{{ $transaction->created_at->format('H:i') }}</td>
                                        <td class="p-3 font-medium text-gray-700">{{ $transaction->user?->name ?? 'Kasir' }}</td>
                                        <td class="p-3"><span class="px-2.5 py-1 text-xs font-medium bg-indigo-50 text-indigo-700 rounded-full">{{ ['cash' => 'Tunai', 'qris' => 'QRIS', 'transfer' => 'Transfer'][$transaction->payment_method] ?? $transaction->payment_method }}</span></td>
                                        <td class="p-3 font-bold text-gray-800">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="p-8 text-center text-sm text-gray-400">Belum ada transaksi.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md">
                <div>
                    <div class="flex items-center space-x-2 text-indigo-950 font-semibold text-lg pb-4 mb-4 border-b border-gray-100">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-amber-500 stroke-[2.5]"></i>
                        <h2>Peringatan Stok Minimal</h2>
                    </div>

                    <div class="space-y-3">
                        @forelse($lowStockProducts as $product)
                            <div class="p-3.5 bg-amber-50 rounded-xl border border-amber-100 flex justify-between items-center gap-3">
                                <div>
                                    <h4 class="font-semibold text-gray-800 text-sm">{{ $product->name }}</h4>
                                    <p class="text-xs text-amber-700 mt-0.5">Sisa: <strong class="font-bold text-sm">{{ $product->stock }}</strong> (Min: {{ $product->min_stock }})</p>
                                </div>
                                <a href="{{ route('products.edit', $product) }}" class="px-3 py-1.5 bg-amber-500 text-white rounded-lg text-xs font-semibold hover:bg-amber-600 transition shadow-sm whitespace-nowrap">Tambah Stok</a>
                            </div>
                        @empty
                            <p class="rounded-xl bg-emerald-50 p-4 text-center text-sm text-emerald-700">Semua stok produk mencukupi.</p>
                        @endforelse
                    </div>
                </div>

                <div class="pt-4 mt-6 border-t border-gray-100 bg-gray-50/50 -mx-6 -mb-6 p-6 rounded-b-2xl">
                    <a href="{{ route('products.index') }}" class="block w-full text-center py-2.5 bg-gray-100 text-gray-600 hover:bg-gray-200 rounded-xl text-xs font-semibold transition">
                        Kelola Stok di Data Master
                    </a>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => { lucide.createIcons(); });
    </script>
</body>
</html>