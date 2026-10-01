

<?php $__env->startSection('title', 'Kelola Master Produk'); ?>

<?php $__env->startSection('content'); ?>

    
    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex items-center justify-between max-w-4xl mx-auto">

        <a href="<?php echo e(route('products.index')); ?>"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl bg-indigo-600 text-white shadow-sm flex items-center justify-center gap-2">
            <i class="fa-solid fa-box"></i>
            <span>Master Produk / Barang</span>
        </a>

        <a href="<?php echo e(Route::has('categories.index') ? route('categories.index') : url('/categories')); ?>"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 flex items-center justify-center gap-2">
            <i class="fa-solid fa-tags"></i>
            <span>Master Kategori</span>
        </a>

        <a href="<?php echo e(Route::has('users.index') ? route('users.index') : url('/users')); ?>"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl text-slate-600 hover:bg-slate-50 flex items-center justify-center gap-2">
            <i class="fa-solid fa-users"></i>
            <span>Master Pengguna (User)</span>
        </a>

    </div>


    
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">

        
        <div class="flex justify-between items-center mb-6">

            <div>
                <h2 class="text-xl font-bold text-slate-800">
                    Kelola Master Produk
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    Kelola daftar barang, harga modal, harga jual, dan stok minimal.
                </p>
            </div>

            
            <button
                type="button"
                onclick="openProductModal()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition"
            >
                <i class="fa-solid fa-plus"></i>
                Tambah Produk Baru
            </button>

        </div>


        
        <?php if(session('success')): ?>

            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl mb-6 text-xs font-semibold flex items-center gap-2">

                <i class="fa-solid fa-circle-check text-emerald-600"></i>

                <span><?php echo e(session('success')); ?></span>

            </div>

        <?php endif; ?>


        
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse text-xs">

                <thead>

                    <tr class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">

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

                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>

                        <tr class="hover:bg-slate-50/50 transition">

                            <td class="py-3.5 px-4 font-bold text-slate-800">
                                <?php echo e($product->code ?? 'BRG-00' . $product->id); ?>

                            </td>


                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                <?php echo e($product->name); ?>

                            </td>


                            <td class="py-3.5 px-4">

                                <span class="bg-slate-100 text-slate-600 px-2.5 py-1 rounded-md text-[11px] font-medium">

                                    <?php echo e($product->category->name ?? 'Lainnya'); ?>


                                </span>

                            </td>


                            <td class="py-3.5 px-4">

                                Rp
                                <?php echo e(number_format($product->cost_price ?? $product->price * 0.7, 0, ',', '.')); ?>


                            </td>


                            <td class="py-3.5 px-4 font-bold text-indigo-600">

                                Rp
                                <?php echo e(number_format($product->price, 0, ',', '.')); ?>


                            </td>


                            <td class="py-3.5 px-4 font-bold
                                <?php echo e($product->stock <= ($product->min_stock ?? 5)
                                    ? 'text-amber-500'
                                    : 'text-slate-800'); ?>">

                                <?php echo e($product->stock); ?> pcs

                            </td>


                            <td class="py-3.5 px-4 text-slate-400">

                                <?php echo e($product->min_stock ?? 5); ?> pcs

                            </td>


                            <td class="py-3.5 px-4 text-center">

                                <div class="flex items-center justify-center space-x-2">

                                    
                                    <a
                                        href="<?php echo e(route('products.edit', $product->id)); ?>"
                                        class="text-indigo-600 hover:text-indigo-800 p-1.5 transition"
                                        title="Edit"
                                    >
                                        <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>


                                    
                                    <form
                                        action="<?php echo e(route('products.destroy', $product->id)); ?>"
                                        method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')"
                                    >

                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>

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

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>

                        <tr>

                            <td colspan="8" class="text-center py-12 text-slate-400">

                                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-300 block"></i>

                                <span>
                                    Belum ada data produk tersimpan.
                                </span>

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>



    

    <div
        id="productModal"
        class="fixed inset-0 z-[9999] hidden items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4"
    >

        <div class="bg-white w-full max-w-xl rounded-2xl shadow-2xl p-7">


            
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


            
            <form
                action="<?php echo e(route('products.store')); ?>"
                method="POST"
            >

                <?php echo csrf_field(); ?>


                
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Kode Barang / Barcode ID
                    </label>

                    <input
                        type="text"
                        name="code"
                        placeholder="misal: BRG-008"
                        class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                    >

                </div>


                
                <div class="mb-4">

                    <label class="block text-sm font-semibold text-slate-700 mb-2">
                        Nama Produk
                    </label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Nama lengkap barang"
                        required
                        class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                    >

                </div>


                
                <div class="grid grid-cols-2 gap-2.5 mb-4">


                    
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

                            <option value="1">
                                Makanan
                            </option>

                            <option value="2">
                                Minuman
                            </option>

                            <option value="3">
                                Snack
                            </option>

                            <option value="4">
                                ATK / Lainnya
                            </option>

                        </select>

                    </div>


                    
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            URL Gambar (Opsional)
                        </label>

                        <input
                            type="url"
                            name="image_url"
                            placeholder="https://..."
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>

                </div>


                
                <div class="grid grid-cols-2 gap-2.5 mb-4">


                    
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga Beli (Modal)
                        </label>

                        <input
                            type="number"
                            name="cost_price"
                            value="0"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>


                    
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Harga Jual
                        </label>

                        <input
                            type="number"
                            name="price"
                            value="0"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>

                </div>


                
                <div class="grid grid-cols-2 gap-2.5">


                    
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Stok Awal
                        </label>

                        <input
                            type="number"
                            name="stock"
                            value="0"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>


                    
                    <div>

                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Minimal Stok Warning
                        </label>

                        <input
                            type="number"
                            name="min_stock"
                            value="5"
                            min="0"
                            required
                            class="w-full h-11 px-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 outline-none"
                        >

                    </div>

                </div>


                
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

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Tantri Rachmayani\Downloads\sikadir\resources\views/products/index.blade.php ENDPATH**/ ?>