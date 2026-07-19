@extends('layouts.app')

@section('title', 'Tambah Inventaris Barang')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.items.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-semibold text-slate-800">Form Tambah Inventaris</h2>
    </div>

    <form action="{{ route('admin.items.store') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kode Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="kode_barang" value="{{ old('kode_barang') }}" required placeholder="Contoh: BRG-001" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kode_barang') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Barang <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required placeholder="Contoh: Meja Lab Komputer" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('nama_barang') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kategori</label>
                <input type="text" name="kategori" value="{{ old('kategori') }}" placeholder="Contoh: Furniture, Komputer" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('kategori') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Stok Awal <span class="text-rose-500">*</span></label>
                <input type="number" name="stok" min="0" value="{{ old('stok') }}" required placeholder="Contoh: 5" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('stok') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Kondisi</label>
                <div class="relative w-full">
                    <select name="kondisi" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 appearance-none" onchange="document.getElementById('kondisi-text-create').innerText = this.options[this.selectedIndex].text">
                        <option value="" disabled {{ old('kondisi') ? '' : 'selected' }}>Pilih Kondisi</option>
                        <option value="Baik" {{ old('kondisi') == 'Baik' ? 'selected' : '' }}>Baik</option>
                        <option value="Rusak Ringan" {{ old('kondisi') == 'Rusak Ringan' ? 'selected' : '' }}>Rusak Ringan</option>
                        <option value="Rusak Berat" {{ old('kondisi') == 'Rusak Berat' ? 'selected' : '' }}>Rusak Berat</option>
                        <option value="Dalam Perbaikan" {{ old('kondisi') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                    </select>
                    <div class="flex items-center justify-between w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm transition-colors">
                        <span id="kondisi-text-create">{{ old('kondisi') ?: 'Pilih Kondisi' }}</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                @error('kondisi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Lokasi Penyimpanan</label>
                <input type="text" name="lokasi" value="{{ old('lokasi') }}" placeholder="Contoh: Lab 1" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                @error('lokasi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.items.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-medium hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Barang
            </button>
        </div>
    </form>
</div>
@endsection
