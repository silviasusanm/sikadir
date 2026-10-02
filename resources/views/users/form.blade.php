@extends('layouts.app')

@section('title', $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna')

@section('content')
    <div class="max-w-3xl mx-auto bg-white p-6 rounded-2xl shadow-sm border border-slate-200/80">
        <h2 class="text-xl font-bold text-slate-800 mb-6">{{ $user->exists ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h2>

        <form action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" method="POST" class="space-y-5">
            @csrf
            @if($user->exists)
                @method('PUT')
            @endif

            @if($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ $errors->first() }}</div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama</label>
                    <input id="name" name="name" value="{{ old('name', $user->name) }}" required class="w-full rounded-xl border-slate-300">
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-xl border-slate-300">
                </div>
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-1">Peran</label>
                    <select id="role" name="role" required class="w-full rounded-xl border-slate-300">
                        <option value="kasir" @selected(old('role', $user->role ?? 'kasir') === 'kasir')>Kasir</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin / Pemilik</option>
                    </select>
                </div>
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">
                        {{ $user->exists ? 'Password baru (opsional)' : 'Password' }}
                    </label>
                    <input id="password" name="password" type="password" {{ $user->exists ? '' : 'required' }} minlength="8" autocomplete="new-password" class="w-full rounded-xl border-slate-300">
                    @if($user->exists)
                        <p class="mt-1 text-xs text-slate-500">Biarkan kosong jika password tidak diubah.</p>
                    @endif
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" {{ $user->exists ? '' : 'required' }} minlength="8" autocomplete="new-password" class="w-full rounded-xl border-slate-300">
                </div>
            </div>

            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold text-sm">Batal</a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold text-sm">Simpan</button>
            </div>
        </form>
    </div>
@endsection
