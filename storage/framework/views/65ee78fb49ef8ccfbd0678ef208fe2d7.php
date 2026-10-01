<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo e(config('app.name', 'Sikadir')); ?> - <?php echo $__env->yieldContent('title', 'Sistem Kasir Digital Terpadu'); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- FontAwesome & Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Alpine.js untuk fitur Dropdown Interaktif -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>

    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-[#EEF2F6] text-gray-800 antialiased min-h-screen flex flex-col">

    <!-- 1. TOP HEADER (BIRU NAVY UTAMA) -->
    <header class="bg-[#2D2D86] text-white px-6 py-3 shadow-md relative z-50">
        <div class="max-w-[1400px] mx-auto flex justify-between items-center">
            
            <!-- Logo & Title -->
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-500/30 border border-indigo-400/40 flex items-center justify-center text-white text-lg font-bold shadow-inner">
                    <i class="fa-solid fa-bag-shopping"></i>
                </div>
                <div>
                    <h1 class="font-extrabold text-lg leading-none tracking-tight">Sikadir</h1>
                    <p class="text-[11px] text-indigo-200 mt-0.5">Sistem Kasir Digital Terpadu</p>
                </div>
            </div>

            <!-- Role Mode Switcher & User Profile Dropdown -->
            <div class="flex items-center gap-4">
                
                <!-- Role Mode Switcher -->
                <div class="bg-[#1F1F68]/60 p-1 rounded-xl flex items-center text-xs font-medium border border-indigo-400/20">
                    <span class="text-indigo-200 px-3 py-1 text-[11px]">Role Mode:</span>
                    <button type="button" class="bg-[#4F46E5] text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5 font-semibold shadow-sm">
                        <i class="fa-regular fa-circle-check text-xs"></i> Admin / Pemilik
                    </button>
                    <button type="button" class="text-indigo-200 hover:text-white px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-colors">
                        <i class="fa-solid fa-user-gear text-xs"></i> Kasir
                    </button>
                </div>

                <!-- User Dropdown Profile -->
                <div class="relative" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false">
                    
                    <!-- Tombol Trigger Dropdown -->
                    <button @click="open = ! open" 
                            type="button"
                            class="flex items-center gap-2.5 bg-[#1F1F68]/60 hover:bg-[#1F1F68] px-3 py-1.5 rounded-xl border border-indigo-400/20 transition-all focus:outline-none">
                        
                        <!-- Avatar -->
                        <div class="w-7 h-7 rounded-full bg-indigo-400/40 overflow-hidden flex items-center justify-center text-white text-xs font-bold border border-white/20 shrink-0">
                            <?php if(Auth::check() && Auth::user()->avatar): ?>
                                <img src="<?php echo e(asset('storage/'.Auth::user()->avatar)); ?>" class="w-full h-full object-cover" alt="Avatar">
                            <?php else: ?>
                                <span class="uppercase"><?php echo e(substr(Auth::user()->name ?? 'A', 0, 1)); ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Nama User -->
                        <span class="text-xs font-semibold">
                            <?php echo e(Auth::user()->name ?? 'Ahmad'); ?> 
                            <span class="text-indigo-200/80">(<?php echo e(ucfirst(Auth::user()->role ?? 'Admin')); ?>)</span>
                        </span>

                        <i class="fa-solid fa-chevron-down text-[10px] text-indigo-300 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                    </button>

                    <!-- Menu Popover Dropdown -->
                    <div x-show="open" 
                         x-cloak
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-100"
                         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                         x-transition:leave-end="opacity-0 scale-95 translate-y-1"
                         class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl border border-slate-100 py-2 z-50 text-slate-800 divide-y divide-slate-100">
                        
                        <!-- Header Profil -->
                        <div class="px-4 py-3">
                            <p class="text-xs font-bold text-indigo-600">
                                <?php echo e(Auth::check() && Auth::user()->role ? ucfirst(Auth::user()->role) . ' / Pemilik' : 'Administrator / Pemilik'); ?>

                            </p>
                            <p class="text-[11px] text-slate-500 truncate mt-0.5">
                                <?php echo e(Auth::user()->email ?? 'sikadir@pos.id'); ?>

                            </p>
                        </div>

                        <!-- Menu Link Ubah Profil & Password -->
                        <div class="py-1">
                            <a href="<?php echo e(Route::has('profile.edit') ? route('profile.edit') : url('/profile')); ?>" 
                               class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                <i class="fa-regular fa-user w-4 text-center text-slate-400"></i>
                                <span>Ubah Profil</span>
                            </a>

                            <a href="<?php echo e(Route::has('profile.edit') ? route('profile.edit') : url('/profile')); ?>" 
                               class="flex items-center gap-3 px-4 py-2 text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-key w-4 text-center text-slate-400"></i>
                                <span>Ubah Password</span>
                            </a>
                        </div>

                        <!-- Tombol Logout -->
                        <div class="py-1">
                            <form method="POST" action="<?php echo e(Route::has('logout') ? route('logout') : url('/logout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2 text-xs font-semibold text-rose-600 hover:bg-rose-50 transition text-left">
                                    <i class="fa-solid fa-right-from-bracket w-4 text-center text-rose-500"></i>
                                    <span>Keluar (Logout)</span>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>

            </div>

        </div>
    </header>

    <!-- 2. MAIN NAVIGATION TABS (NAVBAR PUTIH) -->
    <nav class="bg-white border-b border-gray-200 shadow-xs sticky top-0 z-40">
        <div class="max-w-[1400px] mx-auto px-6 flex items-center gap-2 h-14">
            
            
            <?php if(auth()->check() && auth()->user()->role === 'admin'): ?>
                <a href="<?php echo e(Route::has('dashboard') ? route('dashboard') : url('/dashboard')); ?>" 
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo e(request()->routeIs('dashboard*') ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'); ?>">
                    <i class="fa-solid fa-table-cells-large"></i>
                    <span>Dashboard</span>
                </a>
            <?php endif; ?>

            <!-- Tab POS / Transaksi (Bisa diakses Admin & Kasir) -->
            <a href="<?php echo e(Route::has('pos') ? route('pos') : (Route::has('transactions.create') ? route('transactions.create') : url('/pos'))); ?>" 
               class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo e(request()->routeIs('pos*') || request()->routeIs('transactions.create') ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'); ?>">
                <i class="fa-solid fa-cart-shopping"></i>
                <span>POS / Transaksi</span>
            </a>

            
            <?php if(auth()->check() && auth()->user()->role === 'admin'): ?>
                <a href="<?php echo e(Route::has('products.index') ? route('products.index') : url('/products')); ?>" 
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo e(request()->routeIs('products*') || request()->routeIs('categories*') || request()->routeIs('users*') ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'); ?>">
                    <i class="fa-solid fa-database"></i>
                    <span>Data Master</span>
                </a>

                <a href="<?php echo e(Route::has('reports.index') ? route('reports.index') : url('/reports')); ?>" 
                   class="flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-semibold transition-all <?php echo e(request()->routeIs('reports*') ? 'bg-[#EEF2FF] text-[#4F46E5]' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'); ?>">
                    <i class="fa-regular fa-file-lines"></i>
                    <span>Laporan & Ekspor</span>
                </a>
            <?php endif; ?>

        </div>
    </nav>

    <!-- 3. MAIN CONTENT CONTAINER -->
    <main class="flex-grow max-w-[1400px] w-full mx-auto px-6 py-6">
        <?php echo $__env->yieldContent('content'); ?>
        <?php echo e($slot ?? ''); ?>

    </main>

    <!-- 4. FOOTER -->
    <footer class="bg-transparent py-4 text-center text-xs text-gray-400">
        &copy; <?php echo e(date('Y')); ?> Sikadir. All rights reserved.
    </footer>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\Tantri Rachmayani\Downloads\sikadir\resources\views\layouts\app.blade.php ENDPATH**/ ?>