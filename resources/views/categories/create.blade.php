@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-indigo-900 text-white">
            <h2 class="text-xl font-semibold">{{ $category->exists ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h2>
        </div>
        
        <form action="{{ $category->exists ? route('categories.update', $category) : route('categories.store') }}" method="POST" class="p-6 space-y-6">
            @csrf
            @if($category->exists)
                @method('PUT')
            @endif

            <!-- Nama Kategori -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm border p-2 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tombol Aksi -->
            <div class="flex items-center justify-end space-x-3">
                <a href="{{ route('categories.index') }}" class="bg-gray-200 py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-300 focus:outline-none">
                    Batal
                </a>
                <button type="submit" class="bg-indigo-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none">
                    {{ $category->exists ? 'Simpan Perubahan' : 'Simpan Kategori' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection