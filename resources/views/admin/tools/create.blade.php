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
                <input type="text" name="kode_alat" id="kode_alat" value="{{ old('kode_alat') }}" required placeholder="Contoh: ALK-001" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kode_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="nama_alat" class="block text-sm font-semibold text-slate-700 mb-2">Nama Alat <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_alat" id="nama_alat" value="{{ old('nama_alat') }}" required placeholder="Contoh: Multimeter Digital" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('nama_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori" id="kategori" value="{{ old('kategori') }}" required placeholder="Contoh: Komponen, Mikrokontroler" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kategori') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stok_total" class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="stok_total" id="stok_total" min="0" value="{{ old('stok_total') }}" required placeholder="Contoh: 10" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                <p class="mt-1 text-xs text-slate-500">Stok tersedia otomatis mengikuti stok awal.</p>
                @error('stok_total') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Rak A-1" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('lokasi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Alat</label>
                <div class="relative w-full">
                    <input type="file" name="foto_alat" id="foto_alat" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('file-name-create').textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen'">
                    
                    <div class="flex items-center w-full rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden text-sm">
                        <div class="bg-indigo-50 text-indigo-700 px-4 py-2.5 font-semibold hover:bg-indigo-100 transition-colors shrink-0 border-r border-slate-200">
                            Choose File
                        </div>
                        <div class="px-4 text-slate-500 truncate" id="file-name-create">
                            No file chosen
                        </div>
                    </div>
                </div>
                @error('foto_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi & Spesifikasi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" placeholder="Tuliskan spesifikasi, panduan penggunaan, atau informasi tambahan lainnya..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">{{ old('deskripsi') }}</textarea>
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
