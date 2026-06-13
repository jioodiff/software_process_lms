@extends('layouts.app')

@section('title', 'Tambah Alat Lab')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.tools.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-semibold text-slate-800">Form Tambah Alat</h2>
    </div>

    <form action="{{ route('admin.tools.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="kode_alat" class="block text-sm font-semibold text-slate-700 mb-2">Kode Alat <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_alat" id="kode_alat" value="{{ old('kode_alat') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kode_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="nama_alat" class="block text-sm font-semibold text-slate-700 mb-2">Nama Alat <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_alat" id="nama_alat" value="{{ old('nama_alat') }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('nama_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}" required placeholder="Contoh: Komponen, Mikrokontroler" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kategori') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stok_total" class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="stok_total" id="stok_total" min="0" value="{{ old('stok_total', 0) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                <p class="mt-1 text-xs text-slate-500">Stok tersedia otomatis mengikuti stok awal.</p>
                @error('stok_total') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Rak A-1" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('lokasi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="foto_alat" class="block text-sm font-semibold text-slate-700 mb-2">Foto Alat</label>
                <input type="file" name="foto_alat" id="foto_alat" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-slate-200 rounded-xl bg-white shadow-sm">
                @error('foto_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi & Spesifikasi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">{{ old('deskripsi') }}</textarea>
            @error('deskripsi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.tools.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Alat
            </button>
        </div>
    </form>
</div>
@endsection
