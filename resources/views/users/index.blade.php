@extends('layouts.app')

@section('title', 'Master Pengguna')

@section('content')

    <!-- Sub Tab Data Master -->
    <div class="bg-white p-1.5 rounded-2xl shadow-sm border border-slate-200/80 mb-6 flex items-center justify-between max-w-4xl mx-auto">

        <!-- Produk -->
        <a href="{{ route('products.index') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-slate-600 hover:bg-slate-50">

            <i class="fa-solid fa-box"></i>
            <span>Master Produk / Barang</span>

        </a>


        <!-- Kategori -->
        <a href="{{ route('categories.index') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 text-slate-600 hover:bg-slate-50">

            <i class="fa-solid fa-tags"></i>
            <span>Master Kategori</span>

        </a>


        <!-- User -->
        <a href="{{ route('users.index') }}"
           class="flex-1 py-2.5 text-center text-xs font-semibold rounded-xl transition-all flex items-center justify-center gap-2 bg-indigo-600 text-white shadow-sm">

            <i class="fa-solid fa-users"></i>
            <span>Master Pengguna (User)</span>

        </a>

    </div>


    <!-- Container -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">

            <div>

                <h2 class="text-xl font-bold text-slate-800">
                    Master Pengguna
                </h2>

                <p class="text-xs text-slate-500 mt-1">
                    Kelola pengguna yang dapat mengakses sistem Sikadir.
                </p>

            </div>


            <button
                type="button"
                class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold px-4 py-2.5 rounded-xl shadow-sm flex items-center gap-2 transition">

                <i class="fa-solid fa-plus"></i>

                Tambah Pengguna

            </button>

        </div>


        <!-- Tabel -->
        <div class="overflow-x-auto">

            <table class="w-full text-left border-collapse text-xs">

                <thead>

                    <tr class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100">

                        <th class="py-3.5 px-4">
                            #
                        </th>

                        <th class="py-3.5 px-4">
                            Nama
                        </th>

                        <th class="py-3.5 px-4">
                            Email
                        </th>

                        <th class="py-3.5 px-4">
                            Role
                        </th>

                        <th class="py-3.5 px-4 text-center">
                            Status
                        </th>

                        <th class="py-3.5 px-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y divide-slate-100 font-medium text-slate-700">

                    @forelse($users as $user)

                        <tr class="hover:bg-slate-50/50 transition">

                            <td class="py-3.5 px-4">
                                {{ $loop->iteration }}
                            </td>


                            <td class="py-3.5 px-4 font-bold text-slate-900">

                                {{ $user->name }}

                            </td>


                            <td class="py-3.5 px-4">

                                {{ $user->email }}

                            </td>


                            <td class="py-3.5 px-4">

                                <span class="bg-indigo-50 text-indigo-600 px-2.5 py-1 rounded-md text-[11px] font-semibold">

                                    {{ ucfirst($user->role ?? 'user') }}

                                </span>

                            </td>


                            <td class="py-3.5 px-4 text-center">

                                <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-md text-[11px] font-semibold">

                                    Aktif

                                </span>

                            </td>


                            <td class="py-3.5 px-4">

                                <div class="flex items-center justify-center gap-2">

                                    <button
                                        type="button"
                                        class="text-indigo-600 hover:text-indigo-800 p-1.5 transition"
                                        title="Edit">

                                        <i class="fa-regular fa-pen-to-square text-sm"></i>

                                    </button>


                                    <button
                                        type="button"
                                        class="text-rose-500 hover:text-rose-700 p-1.5 transition"
                                        title="Hapus">

                                        <i class="fa-regular fa-trash-can text-sm"></i>

                                    </button>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="text-center py-12 text-slate-400">

                                <i class="fa-solid fa-users text-3xl mb-2 text-slate-300 block"></i>

                                <span>
                                    Belum ada pengguna terdaftar.
                                </span>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

@endsection