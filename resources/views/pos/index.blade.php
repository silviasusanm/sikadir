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
                <div class="bg-indigo-900/50 p-1 rounded-xl hidden md:flex items-center space-x-2 text-sm font-medium">
                    <span class="px-3 py-1.5 text-indigo-200">Role Mode:</span>
                    <span class="px-3 py-1.5 text-indigo-200">Admin / Pemilik</span>
                    <span class="px-3 py-1.5 bg-indigo-600 text-white rounded-lg shadow-sm">Kasir</span>
                </div>
                
                <!-- DROPDOWN PROFILE & LOGOUT -->
                <div class="relative">
                    <button onclick="toggleUserDropdown()" class="flex items-center space-x-2 bg-indigo-900/60 hover:bg-indigo-900 px-3 py-1.5 rounded-xl border border-indigo-800 transition cursor-pointer">
                        <div class="w-8 h-8 bg-indigo-500 text-white font-bold rounded-full flex items-center justify-center text-xs">B</div>
                        <span class="text-sm font-semibold text-white">Budi (Kasir)</span>
                        <svg class="w-4 h-4 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <!-- Popover Dropdown Menu -->
                    <div id="userDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 hidden z-50 text-slate-800">
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-xs font-bold text-slate-900">Staf Kasir</p>
                            <p class="text-[11px] text-slate-400 truncate">sikadir@pos.id</p>
                        </div>
                        <div class="py-1">
                            <a href="#" onclick="alert('Fitur Ubah Profil')" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span>Ubah Profil</span>
                            </a>
                            <a href="#" onclick="alert('Fitur Ubah Password')" class="flex items-center space-x-2 px-4 py-2 text-xs text-slate-600 hover:bg-slate-50 transition">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                <span>Ubah Password</span>
                            </a>
                        </div>
                        <div class="border-t border-slate-100 pt-1">
                            <a href="#" onclick="logout()" class="flex items-center space-x-2 px-4 py-2 text-xs text-rose-600 hover:bg-rose-50 font-semibold transition">
                                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>Keluar (Logout)</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </header>

    <!-- NAVIGASI MENU UTAMA -->
    <nav class="bg-white border-b border-slate-200 shadow-xs">
        <div class="max-w-7xl mx-auto px-4 flex space-x-8">
            <a href="/dashboard" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Dashboard</a>
            <a href="/pos" class="py-4 text-indigo-600 border-b-2 border-indigo-600 font-semibold text-sm flex items-center space-x-2">POS / Transaksi</a>
            <a href="/products" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Data Master</a>
            <a href="/reports" class="py-4 text-slate-500 hover:text-indigo-600 font-medium text-sm flex items-center space-x-2">Laporan & Ekspor</a>
        </div>
    </nav>

    <!-- KONTEN UTAMA POS -->
    <main class="max-w-7xl mx-auto px-4 py-6">
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
                    <button onclick="openAddProductModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-3 rounded-xl text-xs font-bold shadow-sm transition flex items-center space-x-1.5 shrink-0 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        <span>Menu Baru</span>
                    </button>
                </div>

                <!-- Kategori Filter Pills -->
                <div class="flex items-center space-x-2 overflow-x-auto pb-1" id="categoryFilterContainer">
                    <button onclick="filterCategory('Semua')" id="btnCat-Semua" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm cursor-pointer transition">Semua</button>
                    <button onclick="filterCategory('Minuman')" id="btnCat-Minuman" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition">Minuman</button>
                    <button onclick="filterCategory('Makanan')" id="btnCat-Makanan" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition">Makanan</button>
                    <button onclick="filterCategory('Snack')" id="btnCat-Snack" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition">Snack</button>
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
                            <input type="number" id="discountInput" value="0" oninput="calculateTotal()" class="w-full bg-slate-50 border border-slate-200 rounded-lg px-2 py-1 text-right text-xs focus:outline-none focus:border-indigo-600">
                            <span class="text-slate-500">%</span>
                        </div>
                    </div>

                    <div>
                        <span class="block text-xs text-slate-500 font-medium mb-1.5">Metode Pembayaran:</span>
                        <div class="grid grid-cols-3 gap-1.5 bg-slate-100 p-1 rounded-xl text-xs font-medium text-center">
                            <button type="button" onclick="setPaymentMethod('Tunai')" id="btnTunai" class="py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition">Tunai</button>
                            <button type="button" onclick="setPaymentMethod('QRIS')" id="btnQris" class="py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition">QRIS</button>
                            <button type="button" onclick="setPaymentMethod('Transfer')" id="btnTransfer" class="py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition">Transfer</button>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs">
                        <span class="text-slate-500 font-medium">Uang Diterima:</span>
                        <input type="number" id="cashInput" placeholder="0" oninput="calculateTotal()" class="w-32 bg-slate-50 border border-slate-200 rounded-lg px-2.5 py-1 text-right text-xs focus:outline-none focus:border-indigo-600">
                    </div>

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
                        <span>Bayar & Cetak Struk</span>
                    </button>
                </div>
            </div>

        </div>
    </main>

    <!-- MODAL EDIT / TAMBAH MENU -->
    <div id="productModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
            <h3 id="modalTitle" class="font-bold text-slate-900 text-lg">Edit Menu Produk</h3>
            <input type="hidden" id="editIndex">
            
            <div class="space-y-3 text-xs">
                <div>
                    <label class="block font-medium text-slate-600 mb-1">Nama Produk</label>
                    <input type="text" id="inputName" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:border-indigo-600">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block font-medium text-slate-600 mb-1">Harga (Rp)</label>
                        <input type="number" id="inputPrice" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:border-indigo-600">
                    </div>
                    <div>
                        <label class="block font-medium text-slate-600 mb-1">Stok</label>
                        <input type="number" id="inputStock" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:border-indigo-600">
                    </div>
                </div>
                <div>
                    <label class="block font-medium text-slate-600 mb-1">Kategori</label>
                    <select id="inputCategory" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-slate-800 focus:outline-none focus:border-indigo-600">
                        <option value="Minuman">Minuman</option>
                        <option value="Makanan">Makanan</option>
                        <option value="Snack">Snack</option>
                    </select>
                </div>
                <div>
                    <label class="block font-medium text-slate-600 mb-1">Pilih Foto dari Device</label>
                    <input type="file" id="inputFile" accept="image/*" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-slate-800 text-xs file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer">
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
                <button onclick="closeModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-semibold text-xs transition cursor-pointer">Batal</button>
                <button onclick="saveProduct()" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-semibold text-xs transition cursor-pointer">Simpan Perubahan</button>
            </div>
        </div>
    </div>

    <!-- SCRIPT APLIKASI -->
    <script>
        let products = [
            { name: 'Kopi Susu Gula Aren', price: 18000, stock: 24, category: 'Minuman', image: 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?auto=format&fit=crop&q=80&w=300' },
            { name: 'Nasi Goreng Spesial', price: 22000, stock: 15, category: 'Makanan', image: 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?auto=format&fit=crop&q=80&w=300' },
            { name: 'Keripik Singkong Pedas', price: 10000, stock: 3, category: 'Snack', image: 'https://images.unsplash.com/photo-1566478989037-eec170784d0b?auto=format&fit=crop&q=80&w=300' },
            { name: 'Es Teh Manis Jumbo', price: 5000, stock: 40, category: 'Minuman', image: 'https://images.unsplash.com/photo-1554866585-cd94860890b7?auto=format&fit=crop&q=80&w=300' },
            { name: 'Roti Bakar Cokelat', price: 15000, stock: 2, category: 'Makanan', image: 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?auto=format&fit=crop&q=80&w=300' },
            { name: 'Air Mineral 600ml', price: 4000, stock: 4, category: 'Minuman', image: 'https://images.unsplash.com/photo-1548839140-29a749e1cf4d?auto=format&fit=crop&q=80&w=300' }
        ];

        let cart = [];
        let selectedPayment = 'Tunai';
        let activeCategory = 'Semua';

        // Toggle Dropdown Profile
        function toggleUserDropdown() {
            let dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown jika klik di luar area
        window.addEventListener('click', function(e) {
            if (!e.target.closest('#userDropdown') && !e.target.closest('button[onclick="toggleUserDropdown()"]')) {
                document.getElementById('userDropdown').classList.add('hidden');
            }
        });

        // Simulasi Logout
        // Langsung Logout & Redirect
function logout() {
    window.location.href = '/login';
}
        function filterCategory(category) {
            activeCategory = category;
            ['Semua', 'Minuman', 'Makanan', 'Snack'].forEach(cat => {
                let btn = document.getElementById('btnCat-' + cat);
                if (cat === category) {
                    btn.className = 'px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm cursor-pointer transition';
                } else {
                    btn.className = 'px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-medium cursor-pointer transition';
                }
            });
            renderProducts();
        }

        function renderProducts() {
            const grid = document.getElementById('productGrid');
            const searchKeyword = document.getElementById('searchInput').value.toLowerCase();
            
            let filteredProducts = products.filter(p => {
                let matchCategory = (activeCategory === 'Semua' || p.category === activeCategory);
                let matchSearch = p.name.toLowerCase().includes(searchKeyword);
                return matchCategory && matchSearch;
            });

            if (filteredProducts.length === 0) {
                grid.innerHTML = `<div class="col-span-full py-12 text-center text-slate-400 text-xs">Tidak ada menu yang ditemukan dalam kategori ini.</div>`;
                return;
            }

            let html = '';
            filteredProducts.forEach((p) => {
                let originalIndex = products.findIndex(prod => prod.name === p.name);
                html += `
                    <div class="bg-white border border-slate-200 rounded-2xl p-3 flex flex-col justify-between hover:border-indigo-300 transition shadow-xs relative group">
                        <div>
                            <div class="relative h-28 bg-slate-100 rounded-xl overflow-hidden mb-3">
                                <img src="${p.image}" alt="${p.name}" class="w-full h-full object-cover">
                                <span class="absolute top-2 right-2 bg-slate-900/70 backdrop-blur-xs text-white text-[10px] px-2 py-0.5 rounded-md font-medium">Stok: ${p.stock}</span>
                                <button onclick="openEditModal(${originalIndex})" class="absolute top-2 left-2 bg-white/90 hover:bg-white text-slate-700 p-1.5 rounded-lg shadow-sm transition cursor-pointer" title="Edit Menu">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/></svg>
                                </button>
                            </div>
                            <span class="text-[10px] uppercase font-bold tracking-wider text-slate-400">${p.category}</span>
                            <h4 class="font-bold text-slate-900 text-sm leading-snug mt-0.5">${p.name}</h4>
                            <p class="text-indigo-600 font-semibold text-xs mt-1">Rp ${p.price.toLocaleString('id-ID')}</p>
                        </div>
                        <button onclick="addToCart('${p.name}', ${p.price})" class="mt-4 w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-xl text-xs font-semibold shadow-sm transition flex items-center justify-center space-x-1 cursor-pointer">
                            <span>+ Tambah</span>
                        </button>
                    </div>
                `;
            });
            grid.innerHTML = html;
        }

        document.getElementById('searchInput').addEventListener('input', renderProducts);

        function openEditModal(index) {
            document.getElementById('modalTitle').innerText = 'Edit Menu Produk';
            document.getElementById('editIndex').value = index;
            document.getElementById('inputName').value = products[index].name;
            document.getElementById('inputPrice').value = products[index].price;
            document.getElementById('inputStock').value = products[index].stock;
            document.getElementById('inputCategory').value = products[index].category;
            document.getElementById('inputFile').value = '';
            document.getElementById('productModal').classList.remove('hidden');
            document.getElementById('productModal').classList.add('flex');
        }

        function openAddProductModal() {
            document.getElementById('modalTitle').innerText = 'Tambah Menu Baru';
            document.getElementById('editIndex').value = '';
            document.getElementById('inputName').value = '';
            document.getElementById('inputPrice').value = '';
            document.getElementById('inputStock').value = '';
            document.getElementById('inputCategory').value = activeCategory !== 'Semua' ? activeCategory : 'Minuman';
            document.getElementById('inputFile').value = '';
            document.getElementById('productModal').classList.remove('hidden');
            document.getElementById('productModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productModal').classList.remove('flex');
        }

        function saveProduct() {
            let index = document.getElementById('editIndex').value;
            let name = document.getElementById('inputName').value;
            let price = parseFloat(document.getElementById('inputPrice').value) || 0;
            let stock = parseInt(document.getElementById('inputStock').value) || 0;
            let category = document.getElementById('inputCategory').value;
            let fileInput = document.getElementById('inputFile');

            if (!name || price <= 0) {
                alert('Nama produk dan harga wajib diisi dengan benar!');
                return;
            }

            let processAndSave = (imageSrc) => {
                if (index === '') {
                    products.push({ name, price, stock, category, image: imageSrc || 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&q=80&w=300' });
                } else {
                    let finalImage = imageSrc || products[index].image;
                    products[index] = { name, price, stock, category, image: finalImage };
                }
                closeModal();
                renderProducts();
            };

            if (fileInput.files && fileInput.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) { processAndSave(e.target.result); };
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                processAndSave(null);
            }
        }

        function addToCart(name, price) {
            let existingItem = cart.find(item => item.name === name);
            if (existingItem) { existingItem.qty += 1; } 
            else { cart.push({ name: name, price: price, qty: 1 }); }
            renderCart();
        }

        function changeQty(index, amount) {
            cart[index].qty += amount;
            if (cart[index].qty <= 0) { cart.splice(index, 1); }
            renderCart();
        }

        function clearCart() { cart = []; renderCart(); }

        function setPaymentMethod(method) {
            selectedPayment = method;
            document.getElementById('btnTunai').className = method === 'Tunai' ? 'py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition' : 'py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition';
            document.getElementById('btnQris').className = method === 'QRIS' ? 'py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition' : 'py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition';
            document.getElementById('btnTransfer').className = method === 'Transfer' ? 'py-1.5 bg-white text-indigo-600 rounded-lg shadow-xs font-semibold cursor-pointer transition' : 'py-1.5 text-slate-600 hover:text-slate-900 cursor-pointer transition';
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
                            <div class="flex-1 pr-2">
                                <h5 class="text-xs font-bold text-slate-900">${item.name}</h5>
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
            let subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
            let discountPercent = parseFloat(document.getElementById('discountInput').value) || 0;
            let discountAmount = (subtotal * discountPercent) / 100;
            let total = subtotal - discountAmount;
            let cashReceived = parseFloat(document.getElementById('cashInput').value) || 0;
            let change = cashReceived - total;

            document.getElementById('subtotalText').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('discountText').innerText = '- Rp ' + discountAmount.toLocaleString('id-ID');
            document.getElementById('totalText').innerText = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('changeText').innerText = change >= 0 ? 'Rp ' + change.toLocaleString('id-ID') : 'Rp 0';
        }

        function checkout() {
            if (cart.length === 0) {
                alert('Keranjang masih kosong, wak!');
                return;
            }
            alert('Transaksi berhasil diproses menggunakan metode ' + selectedPayment + '!');
            clearCart();
            document.getElementById('cashInput').value = '';
            document.getElementById('discountInput').value = '0';
        }

        renderProducts();
    </script>
</body>
</html>