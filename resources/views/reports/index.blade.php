@extends('layouts.app')

@section('content')

    <!-- Bar Toolbar Filter & Ekspor -->
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
        
        <!-- Filter Rentang Tanggal -->
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3 text-xs text-slate-600 font-medium">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-calendar text-indigo-600 text-sm"></i>
                <span class="font-semibold text-slate-700">Rentang Tanggal:</span>
            </div>

            <input type="date" name="start_date" value="{{ $startDate ?? '2026-09-01' }}" 
                class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            
            <span class="text-slate-400 font-normal">s/d</span>

            <input type="date" name="end_date" value="{{ $endDate ?? '2026-09-23' }}" 
                class="bg-slate-50 border border-slate-200 text-slate-700 text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-xl transition shadow-sm">
                Filter
            </button>
        </form>

        <!-- Tombol Ekspor -->
        <div class="flex items-center gap-2.5">
            <a href="#" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-file-excel text-sm"></i> Ekspor Excel (.xlsx)
            </a>
            <a href="#" class="bg-rose-600 hover:bg-rose-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-file-pdf text-sm"></i> Ekspor PDF
            </a>
        </div>

    </div>

    <!-- Ringkasan Stat (3 Cards) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        
        <!-- Card 1: Total Omzet Penjualan -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-2">Total Omzet Penjualan</p>
                <h3 class="text-2xl font-black text-slate-800 tracking-tight">
                    Rp {{ number_format($totalOmzet ?? 18250000, 0, ',', '.') }}
                </h3>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-3">{{ $totalTransaksi ?? 342 }} Transaksi Selesai</p>
        </div>

        <!-- Card 2: Total Laba Bersih -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-2">Total Laba Bersih</p>
                <h3 class="text-2xl font-black text-emerald-600 tracking-tight">
                    Rp {{ number_format($totalLaba ?? 6120000, 0, ',', '.') }}
                </h3>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-3">Laba = Harga Jual - Modal</p>
        </div>

        <!-- Card 3: Produk Terjual -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80 flex flex-col justify-between">
            <div>
                <p class="text-xs font-semibold text-slate-400 mb-2">Produk Terjual</p>
                <h3 class="text-2xl font-black text-indigo-600 tracking-tight">
                    {{ $produkTerjual ?? 512 }} Items
                </h3>
            </div>
            <p class="text-xs text-slate-400 font-medium mt-3">Dari 12 Kategori Produk</p>
        </div>

    </div>

    <!-- Grid Konten Bawah (Metode Pembayaran & Top Selling) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Rekapitulasi Metode Pembayaran -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-2 mb-6">
                <i class="fa-solid fa-wallet text-indigo-600 text-base"></i>
                <h3 class="text-sm font-bold text-slate-800">Rekapitulasi Metode Pembayaran</h3>
            </div>

            @php
                $valTunai = $tunai ?? 10950000;
                $valQris  = $qris ?? 5475000;
                $valBank  = $bank ?? 1825000;
                
                $grandTotal = $valTunai + $valQris + $valBank;
                $pctTunai   = $grandTotal > 0 ? round(($valTunai / $grandTotal) * 100) : 0;
                $pctQris    = $grandTotal > 0 ? round(($valQris / $grandTotal) * 100) : 0;
                $pctBank    = $grandTotal > 0 ? round(($valBank / $grandTotal) * 100) : 0;
            @endphp

            <div class="space-y-5 text-xs">
                
                <!-- Tunai (Cash) -->
                <div>
                    <div class="flex justify-between items-center mb-1.5 font-medium">
                        <span class="text-slate-700">Tunai (Cash)</span>
                        <span class="font-bold text-slate-800">
                            Rp {{ number_format($valTunai, 0, ',', '.') }} 
                            <span class="text-slate-500 font-normal">({{ $pctTunai }}%)</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-emerald-500 h-2.5 rounded-full transition-all" style="width: {{ $pctTunai }}%"></div>
                    </div>
                </div>

                <!-- QRIS (Non-Tunai) -->
                <div>
                    <div class="flex justify-between items-center mb-1.5 font-medium">
                        <span class="text-slate-700">QRIS (Non-Tunai)</span>
                        <span class="font-bold text-slate-800">
                            Rp {{ number_format($valQris, 0, ',', '.') }} 
                            <span class="text-slate-500 font-normal">({{ $pctQris }}%)</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-indigo-600 h-2.5 rounded-full transition-all" style="width: {{ $pctQris }}%"></div>
                    </div>
                </div>

                <!-- Transfer Bank -->
                <div>
                    <div class="flex justify-between items-center mb-1.5 font-medium">
                        <span class="text-slate-700">Transfer Bank</span>
                        <span class="font-bold text-slate-800">
                            Rp {{ number_format($valBank, 0, ',', '.') }} 
                            <span class="text-slate-500 font-normal">({{ $pctBank }}%)</span>
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                        <div class="bg-sky-500 h-2.5 rounded-full transition-all" style="width: {{ $pctBank }}%"></div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Produk Terlaris (Top Selling) -->
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
            <div class="flex items-center gap-2 mb-6">
                <i class="fa-solid fa-ribbon text-amber-500 text-base"></i>
                <h3 class="text-sm font-bold text-slate-800">Produk Terlaris (Top Selling)</h3>
            </div>

            <div class="space-y-3">
                @if(isset($topProducts) && count($topProducts) > 0)
                    @foreach($topProducts as $item)
                        <div class="bg-slate-50/70 p-3.5 rounded-xl flex items-center justify-between">
                            <div>
                                <h4 class="text-xs font-bold text-slate-800">{{ $item->product->name ?? 'Nama Produk' }}</h4>
                                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ $item->total_qty }} pcs terjual</p>
                            </div>
                            <span class="text-xs font-bold text-indigo-600">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                @else
                    <!-- Fallback Dummy Data jika data dari DB belum dikirim -->
                    <div class="bg-slate-50/70 p-3.5 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Kopi Susu Gula Aren</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">124 pcs terjual</p>
                        </div>
                        <span class="text-xs font-bold text-indigo-600">Rp 2.232.000</span>
                    </div>

                    <div class="bg-slate-50/70 p-3.5 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Nasi Goreng Spesial</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">88 pcs terjual</p>
                        </div>
                        <span class="text-xs font-bold text-indigo-600">Rp 1.936.000</span>
                    </div>

                    <div class="bg-slate-50/70 p-3.5 rounded-xl flex items-center justify-between">
                        <div>
                            <h4 class="text-xs font-bold text-slate-800">Es Teh Manis Jumbo</h4>
                            <p class="text-[11px] text-slate-400 font-medium mt-0.5">76 pcs terjual</p>
                        </div>
                        <span class="text-xs font-bold text-indigo-600">Rp 380.000</span>
                    </div>
                @endif
            </div>
        </div>

    </div>

@endsection