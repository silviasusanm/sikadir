<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS / Transaksi - Sikadir</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-100 min-h-screen text-slate-800">

    <!-- NAVBAR ATAS -->
    <header class="bg-[#1e1b4b] text-white border-b border-indigo-900 sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">SK</div>
                <div>
                    <h1 class="font-bold text-white leading-none">Sikadir</h1>
                    <span class="text-xs text-indigo-300">Sistem Kasir Digital Terpadu</span>
                </div>
            </div>

            <div class="flex items-center space-x-4">
                {{-- Hanya tampilkan Mode Switcher jika yang login adalah Admin --}}
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <div class="bg-indigo-900/50 p-1 rounded-xl hidden md:flex items-center space-x-2 text-sm font-medium">
                        <span class="px-3 py-1.5 text-indigo-200">Role Mode:</span>
                        <a href="{{ route('dashboard') }}" class="px-3 py-1.5 text-indigo-200 hover:text-white transition">Admin / Pemilik</a>
                        <span class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg shadow-sm">Kasir</span>
                    </div>
                @else
                    <div class="bg-indigo-900/50 px-3 py-1.5 rounded-xl hidden md:flex items-center text-xs font-semibold text-indigo-200">
                        <span>Mode Kasir Aktif</span>
                    </div>
                @endif
                
                <!-- DROPDOWN PROFILE & LOGOUT -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="flex items-center space-x-2 bg-indigo-900/60 hover:bg-indigo-900 px-3 py-1.5 rounded-xl border border-indigo-800 transition cursor-pointer">
                        <div class="w-8 h-8 bg-indigo-500 text-white font-bold rounded-full flex items-center justify-center text-xs">
                            {{ strtoupper(substr(auth()->user()->name ?? 'B', 0, 1)) }}
                        </div>
                        <span class="text-sm font-semibold text-white">{{ auth()->user()->name ?? 'Budi' }} ({{ ucfirst(auth()->user()->role ?? 'Kasir') }})</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Popover Dropdown Menu -->
                    <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden z-50 text-slate-800">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-900">{{ auth()->user()->name ?? 'Staf Kasir' }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ auth()->user()->email ?? 'sikadir@pos.id' }}</p>
                        </div>
                        <div class="py-1">
                            <a href="{{ route('profile.edit') }}" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Ubah Profil</span>
                            </a>
                        </div>
                        <div class="border-t border-slate-100 pt-1">
                            {{-- Form Logout Resmi Laravel --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center space-x-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition cursor-pointer">
                                    <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- NAVIGASI MENU UTAMA -->
    <nav class="bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 flex space-x-8">
            {{-- Menu Navigasi Admin disembunyikan untuk Kasir --}}
            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Dashboard</a>
            @endif

            <a href="{{ route('pos') }}" class="py-4 text-indigo-600 border-b-2 border-indigo-600 font-semibold text-sm flex items-center space-x-2">POS / Transaksi</a>

            @if(auth()->check() && auth()->user()->role === 'admin')
                <a href="{{ route('products.index') }}" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Data Master</a>
                <a href="{{ route('reports.index') }}" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Laporan & Ekspor</a>
            @endif
        </div>
    </nav>

    <!-- KONTEN UTAMA POS -->
    <main class="max-w-7xl mx-auto px-4 py-6">
        @if(session('success'))
            <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-700 flex items-center justify-between gap-3">
                <span>{{ session('success') }}</span>
                @if(session('receipt_id'))
                    <a href="{{ route('pos.receipt', session('receipt_id')) }}" target="_blank" class="shrink-0 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition">Cetak Struk</a>
                @endif
            </div>
        @endif
        @if($errors->any())
            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-semibold text-rose-700">{{ $errors->first() }}</div>
        @endif
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            <!-- KOLOM KIRI: KATALOG PRODUK -->
            <div class="lg:col-span-8 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-5">
                <div class="flex items-center space-x-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input type="text" id="searchInput" placeholder="Cari nama produk atau kode barcode..." class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-indigo-600 focus:bg-white transition">
                    </div>
                    
                    {{-- Sembunyikan tombol Tambah Menu Baru jika pengguna adalah Kasir --}}
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('products.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl text-xs font-bold shadow-sm transition flex items-center space-x-1.5 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <span>Menu Baru</span>
                        </a>
                    @endif
                </div>

                <!-- Kategori Filter Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-1" id="categoryFilterContainer">
                    <button type="button" data-category="Semua" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm cursor-pointer transition">Semua</button>
                    @foreach($categories as $category)
                        <button type="button" data-category="{{ $category->name }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition">{{ $category->name }}</button>
                    @endforeach
                </div>

                <!-- Grid Daftar Produk -->
                <div id="productGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"></div>
            </div>

            <!-- KOLOM KANAN: KERANJANG BELANJA -->
            <div class="lg:col-span-4 bg-white rounded-2xl border border-slate-200 shadow-sm p-5 flex flex-col justify-between">
                <div>
                    <div class="flex justify-between items-center pb-4 border-b border-slate-100">
                        <h3 class="font-bold text-slate-900">Keranjang Belanja</h3>
                        <button onclick="clearCart()" class="text-xs font-semibold text-rose-500 hover:text-rose-600 cursor-pointer">Kosongkan</button>
                    </div>

                    <div id="cartItemsContainer" class="py-4 space-y-3 max-h-56 overflow-y-auto">
                        <div id="emptyCart" class="py-8 text-center">
                            <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            <p class="text-sm font-medium text-slate-400">Keranjang masih kosong</p>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Diskon Toko:</span>
                        <div class="flex items-center space-x-1 w-24">
                            <input type="number" id="discountInput" value="0" min="0" max="100" step="0.01" oninput="calculateTotal()" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-right text-xs focus:outline-none focus:border-indigo-600">
                            <span class="text-slate-500">%</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs text-slate-500 font-medium mb-1.5">Metode Pembayaran:</span>
                        <div class="grid grid-cols-2 gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-medium text-center">
                            <button type="button" onclick="setPaymentMethod('cash')" id="btnTunai" class="py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition">Tunai</button>
                            <button type="button" onclick="setPaymentMethod('qris')" id="btnQris" class="py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition">QRIS</button>
                        </div>
                    </div>

                    <div id="cashPaymentFields" class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Uang Diterima:</span>
                        <input type="number" id="cashInput" placeholder="0" min="0" step="0.01" oninput="calculateTotal()" class="w-32 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-right text-xs focus:outline-none focus:border-indigo-600">
                    </div>

                    <div id="cashQuickAmounts" class="flex gap-2">
                        <button type="button" onclick="setCashAmount(20000)" class="flex-1 rounded-lg border border-slate-200 py-1.5 text-[11px] font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-600">Rp 20.000</button>
                        <button type="button" onclick="setCashAmount(50000)" class="flex-1 rounded-lg border border-slate-200 py-1.5 text-[11px] font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-600">Rp 50.000</button>
                        <button type="button" onclick="setCashAmount(100000)" class="flex-1 rounded-lg border border-slate-200 py-1.5 text-[11px] font-semibold text-slate-600 hover:border-indigo-300 hover:text-indigo-600">Rp 100.000</button>
                    </div>

                    <div id="qrisPaymentDetails" class="hidden rounded-xl border border-indigo-100 bg-indigo-50/70 p-3 text-center">
                        <p class="text-xs font-bold text-slate-800">Pembayaran QRIS</p>
                        <p class="mt-1 text-[11px] text-slate-500">Minta pelanggan memindai QRIS resmi toko dan pastikan pembayaran berhasil.</p>
                        @if($qrisImageUrl)
                            <img src="{{ $qrisImageUrl }}" alt="QRIS resmi toko" class="mx-auto mt-3 h-44 w-44 rounded-lg border border-slate-200 bg-white object-contain p-2">
                        @else
                            <div class="mx-auto mt-3 flex h-44 w-44 flex-col items-center justify-center rounded-lg border-2 border-dashed border-indigo-200 bg-white px-3">
                                <span class="text-3xl text-indigo-400" aria-hidden="true">▦</span>
                                <span class="mt-2 text-[11px] font-semibold text-slate-600">QRIS toko belum disiapkan</span>
                                <span class="mt-1 text-[10px] text-slate-400">Tambahkan file resmi di public/images/qris.png</span>
                            </div>
                        @endif
                        <p class="mt-2 text-xs text-slate-600">Jumlah pembayaran: <strong id="qrisTotalText" class="text-indigo-700">Rp 0</strong></p>
                    </div>

                    <label id="paymentConfirmationRow" class="hidden items-start gap-2 rounded-lg border border-amber-200 bg-amber-50 p-2.5 text-[11px] leading-relaxed text-amber-900">
                        <input type="checkbox" id="paymentConfirmed" name="payment_confirmed" value="1" class="mt-0.5 rounded border-amber-400 text-indigo-600 focus:ring-indigo-500">
                        <span>Saya sudah memeriksa dan memastikan pembayaran non-tunai diterima oleh toko.</span>
                    </label>

                    <div class="pt-2 border-t border-slate-100 space-y-1">
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Subtotal</span>
                            <span id="subtotalText">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-500">
                            <span>Potongan Diskon</span>
                            <span id="discountText">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="font-bold text-slate-900 text-sm">TOTAL</span>
                            <span id="totalText" class="font-bold text-indigo-600 text-base">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Kembalian</span>
                            <span id="changeText">Rp 0</span>
                        </div>
                    </div>

                    <button onclick="checkout()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 rounded-xl font-bold text-sm shadow-md shadow-indigo-200 transition flex items-center justify-center space-x-2 mt-2 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Proses Pembayaran</span>
                    </button>
                </div>
            </div>

        </div>
    </main>

    <form id="checkoutForm" action="{{ route('pos.checkout') }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="payment_method" id="checkoutPaymentMethod">
        <input type="hidden" name="discount_percent" id="checkoutDiscountPercent">
        <input type="hidden" name="amount_paid" id="checkoutAmountPaid">
        <input type="hidden" name="payment_confirmed" id="checkoutPaymentConfirmed" disabled>
        <div id="checkoutItems"></div>
    </form>

    <!-- SCRIPT APLIKASI -->
    <script>
        // Cek peran pengguna untuk menyembunyikan fitur edit kartu produk pada sisi client
        let products = @js($posProducts);

        let cart = [];
        let selectedPayment = 'cash';
        let activeCategory = 'Semua';

        function escapeHtml(value) {
            return String(value).replace(/[&<>"']/g, character => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            })[character]);
        }

        function toggleUserDropdown() {
            let dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        window.addEventListener('click', function(e) {
            if (!e.target.closest('#userDropdown') && !e.target.closest('button[onclick="toggleUserDropdown()"]')) {
                document.getElementById('userDropdown').classList.add('hidden');
            }
        });

        function filterCategory(category) {
            activeCategory = category;
            document.querySelectorAll('#categoryFilterContainer button').forEach(button => {
                if (button.dataset.category === category) {
                    button.className = 'px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm cursor-pointer transition';
                } else {
                    button.className = 'px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition';
                }
            });
            renderProducts();
        }

        function renderProducts() {
            const grid = document.getElementById('productGrid');
            const searchKeyword = document.getElementById('searchInput').value.toLowerCase();
            
            let filteredProducts = products.filter(p => {
                let matchCategory = (activeCategory === 'Semua' || p.category === activeCategory);
                let matchSearch = p.name.toLowerCase().includes(searchKeyword)
                    || p.barcode.toLowerCase().includes(searchKeyword);
                return matchCategory && matchSearch;
            });

            if (filteredProducts.length === 0) {
                grid.innerHTML = `<div class="col-span-full py-12 text-center text-slate-400 text-xs">Tidak ada menu yang ditemukan dalam kategori ini.</div>`;
                return;
            }

            let html = '';
            filteredProducts.forEach((p) => {
                html += `
                    <div class="bg-white border border-slate-200 rounded-2xl p-3 flex flex-col justify-between hover:border-indigo-300 transition shadow-xs">
                        <div>
                            <div class="relative h-24 bg-indigo-50 text-indigo-600 rounded-xl mb-3 flex items-center justify-center overflow-hidden">
                                ${p.image
                                    ? `<img src="${escapeHtml(p.image)}" alt="${escapeHtml(p.name)}" loading="lazy" class="w-full h-full object-cover">`
                                    : `<span class="text-xs font-semibold">${escapeHtml(p.category)}</span>`}
                                ${p.stock < 1
                                    ? `<span class="absolute top-1.5 left-1.5 bg-rose-600 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">Habis</span>`
                                    : (p.stock <= p.min_stock
                                        ? `<span class="absolute top-1.5 left-1.5 bg-amber-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-md">Stok menipis</span>`
                                        : '')}
                            </div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">${escapeHtml(p.barcode)}</span>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug mt-0.5">${escapeHtml(p.name)}</h4>
                            <p class="text-indigo-600 font-semibold text-xs mt-1">Rp ${p.price.toLocaleString('id-ID')}</p>
                            <p class="text-slate-500 text-xs mt-1">Stok: ${p.stock}</p>
                        </div>
                        <button onclick="addToCart(${p.id})" ${p.stock < 1 ? 'disabled' : ''} class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 text-white py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center justify-center space-x-1 cursor-pointer">
                            <span>+ Tambah</span>
                        </button>
                    </div>
                `;
            });
            grid.innerHTML = html;
        }

        document.getElementById('searchInput').addEventListener('input', renderProducts);
        document.getElementById('categoryFilterContainer').addEventListener('click', event => {
            const button = event.target.closest('button[data-category]');
            if (button) {
                filterCategory(button.dataset.category);
            }
        });

        function addToCart(productId) {
            const product = products.find(item => item.id === productId);
            const existingItem = cart.find(item => item.id === productId);

            if (!product || (existingItem?.qty ?? 0) >= product.stock) {
                alert('Jumlah produk melebihi stok yang tersedia.');
                return;
            }

            if (existingItem) {
                existingItem.qty += 1;
            } else {
                cart.push({ id: product.id, name: product.name, price: product.price, stock: product.stock, image: product.image, qty: 1 });
            }
            renderCart();
        }

        function changeQty(index, amount) {
            cart[index].qty = Math.min(cart[index].qty + amount, cart[index].stock);
            if (cart[index].qty <= 0) { cart.splice(index, 1); }
            renderCart();
        }

        function clearCart() { cart = []; renderCart(); }

        function setPaymentMethod(method) {
            selectedPayment = method;
            document.getElementById('btnTunai').className = method === 'cash' ? 'py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition' : 'py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition';
            document.getElementById('btnQris').className = method === 'qris' ? 'py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition' : 'py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition';
            document.getElementById('cashPaymentFields').classList.toggle('hidden', method !== 'cash');
            document.getElementById('cashQuickAmounts').classList.toggle('hidden', method !== 'cash');
            document.getElementById('qrisPaymentDetails').classList.toggle('hidden', method !== 'qris');
            document.getElementById('paymentConfirmationRow').classList.toggle('hidden', method === 'cash');
            document.getElementById('paymentConfirmationRow').classList.toggle('flex', method !== 'cash');
            document.getElementById('paymentConfirmed').checked = false;
            calculateTotal();
        }

        function setCashAmount(amount) {
            document.getElementById('cashInput').value = amount;
            calculateTotal();
        }

        function renderCart() {
            const container = document.getElementById('cartItemsContainer');
            if (cart.length === 0) {
                container.innerHTML = `
                    <div id="emptyCart" class="py-8 text-center">
                        <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-400">Keranjang masih kosong</p>
                    </div>
                `;
            } else {
                let html = '';
                cart.forEach((item, index) => {
                    html += `
                        <div class="flex items-center justify-between bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                            ${item.image ? `<img src="${escapeHtml(item.image)}" alt="" class="w-10 h-10 rounded-lg object-cover mr-2.5 shrink-0">` : ''}
                            <div class="flex-1 pr-2">
                                <h5 class="text-xs font-bold text-slate-900">${escapeHtml(item.name)}</h5>
                                <span class="text-[11px] text-indigo-600 font-semibold">Rp ${item.price.toLocaleString('id-ID')}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button onclick="changeQty(${index}, -1)" class="w-6 h-6 bg-white border border-slate-200 rounded-md text-slate-600 font-bold text-xs flex items-center justify-center hover:bg-slate-100 transition cursor-pointer">-</button>
                                <span class="text-xs font-bold w-4 text-center">${item.qty}</span>
                                <button onclick="changeQty(${index}, 1)" class="w-6 h-6 bg-white border border-slate-200 rounded-md text-slate-600 font-bold text-xs flex items-center justify-center hover:bg-slate-100 transition cursor-pointer">+</button>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }
            calculateTotal();
        }

        function calculateTotal() {
            const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            const discountPercent = Math.min(100, Math.max(0, parseFloat(document.getElementById('discountInput').value) || 0));
            const discountAmount = Math.round(subtotal * discountPercent) / 100;
            const total = Math.round((subtotal - discountAmount) * 100) / 100;
            let cashReceived = parseFloat(document.getElementById('cashInput').value) || 0;
            let change = cashReceived - total;
            document.getElementById('subtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('discountText').innerText = '- Rp ' + discountAmount.toLocaleString('id-ID');
            document.getElementById('totalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('changeText').innerText = change >= 0 ? 'Rp ' + change.toLocaleString('id-ID') : 'Rp 0';
            document.getElementById('qrisTotalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong!');
                return;
            }

            const subtotal = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
            const discountPercent = Math.min(100, Math.max(0, parseFloat(document.getElementById('discountInput').value) || 0));
            const total = Math.round(subtotal * (1 - discountPercent / 100) * 100) / 100;
            const amountPaid = selectedPayment === 'cash'
                ? (parseFloat(document.getElementById('cashInput').value) || 0)
                : total;

            if (selectedPayment === 'cash' && amountPaid < total) {
                alert('Uang yang diterima belum mencukupi total pembayaran.');
                return;
            }

            if (selectedPayment !== 'cash' && !document.getElementById('paymentConfirmed').checked) {
                alert('Pastikan pembayaran sudah diterima sebelum melanjutkan.');
                return;
            }

            document.getElementById('checkoutPaymentMethod').value = selectedPayment;
            document.getElementById('checkoutDiscountPercent').value = discountPercent;
            document.getElementById('checkoutAmountPaid').value = amountPaid;
            document.getElementById('checkoutPaymentConfirmed').value = selectedPayment === 'cash' ? '' : '1';
            document.getElementById('checkoutPaymentConfirmed').disabled = selectedPayment === 'cash';
            document.getElementById('checkoutItems').replaceChildren();

            cart.forEach((item, index) => {
                const productId = document.createElement('input');
                productId.type = 'hidden';
                productId.name = `cart[${index}][product_id]`;
                productId.value = item.id;

                const quantity = document.createElement('input');
                quantity.type = 'hidden';
                quantity.name = `cart[${index}][quantity]`;
                quantity.value = item.qty;

                document.getElementById('checkoutItems').append(productId, quantity);
            });

            document.getElementById('checkoutForm').submit();
        }

        renderProducts();
    </script>
</body>
</html>