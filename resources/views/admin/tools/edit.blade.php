@extends('layouts.app')

@section('title', 'Edit Alat Lab')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.tools.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-semibold text-slate-800">Edit Alat: {{ $tool->nama_alat }}</h2>
    </div>

    <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="kode_alat" class="block text-sm font-semibold text-slate-700 mb-2">Kode Alat <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_alat" id="kode_alat" value="{{ old('kode_alat', $tool->kode_alat) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kode_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="nama_alat" class="block text-sm font-semibold text-slate-700 mb-2">Nama Alat <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_alat" id="nama_alat" value="{{ old('nama_alat', $tool->nama_alat) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('nama_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="kategori" class="block text-sm font-semibold text-slate-700 mb-2">Kategori <span class="text-rose-500">*</span></label>
                <input type="text" name="kategori" id="kategori" value="{{ old('kategori', $tool->kategori) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kategori') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="lokasi" class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $tool->lokasi) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('lokasi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label for="status_alat" class="block text-sm font-semibold text-slate-700 mb-2">Status Alat <span class="text-rose-500">*</span></label>
                <select name="status_alat" id="status_alat" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                    <option value="Tersedia" {{ old('status_alat', $tool->status_alat) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                    <option value="Tidak Tersedia" {{ old('status_alat', $tool->status_alat) == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                    <option value="Rusak" {{ old('status_alat', $tool->status_alat) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                    <option value="Dalam Perbaikan" {{ old('status_alat', $tool->status_alat) == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
                @error('status_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="foto_alat" class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Alat</label>
                @if($tool->foto_alat)
                    <div class="mb-2">
                        <img src="{{ Storage::url($tool->foto_alat) }}" alt="Current Photo" class="h-16 w-16 object-cover rounded-lg border border-slate-200">
                    </div>
                @endif
                <input type="file" name="foto_alat" id="foto_alat" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-slate-200 rounded-xl bg-white shadow-sm">
                @error('foto_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi & Spesifikasi</label>
            <textarea name="deskripsi" id="deskripsi" rows="4" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">{{ old('deskripsi', $tool->deskripsi) }}</textarea>
            @error('deskripsi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 text-amber-800">
            <i data-lucide="info" class="w-5 h-5 shrink-0 text-amber-600 mt-0.5"></i>
            <div class="text-sm">
                <p><strong>Info Stok:</strong> Total Stok saat ini {{ $tool->stok_total }}, Stok Tersedia {{ $tool->stok_tersedia }}.</p>
                <p>Untuk mengubah jumlah stok (menambah alat baru/rusak), harap gunakan fitur Mutasi Barang, bukan melalui Edit Data.</p>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.tools.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
