@extends('layouts.app')

@section('title', 'Edit Alat Lab')

@section('content')
<div class="w-full">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.tools.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-lg font-semibold text-slate-800">Edit Alat: {{ $tool->nama_alat }}</h2>
    </div>

    <form action="{{ route('admin.tools.update', $tool) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 sm:p-8">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Side (2 Columns Wide) -->
            <div class="xl:col-span-2 space-y-6">
                <div class="flex items-center gap-2 mb-2 border-b border-slate-100 pb-2">
                    <i data-lucide="info" class="w-5 h-5 text-indigo-500"></i>
                    <h3 class="font-bold text-slate-800">Informasi Dasar</h3>
                </div>

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
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-semibold text-slate-700 mb-2">Deskripsi & Spesifikasi</label>
                    <textarea name="deskripsi" id="deskripsi" rows="6" value="{{ old('deskripsi', $tool->deskripsi) }}" placeholder="{{ old('deskripsi', $tool->deskripsi) }}" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">{{ old('deskripsi', $tool->deskripsi) }}</textarea>
                    @error('deskripsi') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="pt-6 mt-4 flex justify-end gap-3 border-t border-slate-100">
                    <a href="{{ route('admin.tools.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 hover:text-slate-900 transition-colors">
                        Batal
                    </a>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-colors flex items-center justify-center gap-2">
                        <i data-lucide="save" class="w-4 h-4"></i> Simpan Perubahan
                    </button>
                </div>
            </div>

            <!-- Right Side (1 Column Wide) -->
            <div class="space-y-6 xl:border-l xl:border-slate-100 xl:pl-8">
                <div class="flex items-center gap-2 mb-2 border-b border-slate-100 pb-2">
                    <i data-lucide="settings-2" class="w-5 h-5 text-indigo-500"></i>
                    <h3 class="font-bold text-slate-800">Status & Media</h3>
                </div>

                <div>
                    <label for="status_alat" class="block text-sm font-semibold text-slate-700 mb-2">Status Alat <span class="text-rose-500">*</span></label>
                    @if($activeBorrowings ?? false)
                        <div class="relative w-full">
                            <input type="hidden" name="status_alat" value="{{ old('status_alat', $tool->status_alat) }}">
                            <div class="flex items-center justify-between w-full rounded-xl border border-slate-200 bg-slate-100 text-slate-500 px-4 py-2.5 text-sm shadow-sm cursor-not-allowed">
                                <span>{{ old('status_alat', $tool->status_alat) }}</span>
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                        </div>
                        <p class="mt-2 text-[11px] text-amber-600 font-medium bg-amber-50 px-3 py-2 rounded-lg border border-amber-100"><i data-lucide="alert-triangle" class="w-3.5 h-3.5 inline mr-1"></i> Status dikunci karena alat sedang dipinjam.</p>
                    @else
                        <div class="relative w-full">
                            <select name="status_alat" id="status_alat" required 
                                    onchange="document.getElementById('status-text').innerText = this.options[this.selectedIndex].text"
                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 appearance-none">
                                <option value="Tersedia" {{ old('status_alat', $tool->status_alat) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                                <option value="Tidak Tersedia" {{ old('status_alat', $tool->status_alat) == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
                                <option value="Rusak" {{ old('status_alat', $tool->status_alat) == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                                <option value="Dalam Perbaikan" {{ old('status_alat', $tool->status_alat) == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            </select>
                            <div class="flex items-center justify-between w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm transition-colors">
                                <span id="status-text">{{ old('status_alat', $tool->status_alat) ?? 'Tersedia' }}</span>
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    @endif
                    @error('status_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ganti Foto Alat</label>
                    @if($tool->foto_alat)
                        <div class="mb-3 p-2 border border-slate-100 rounded-xl bg-slate-50 flex justify-center">
                            <img src="{{ Storage::url($tool->foto_alat) }}" alt="Current Photo" class="h-32 w-auto object-contain rounded-lg border border-slate-200 bg-white">
                        </div>
                    @endif
                    <div class="relative w-full">
                        <input type="file" name="foto_alat" id="foto_alat" accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="document.getElementById('file-name-edit').textContent = this.files.length > 0 ? this.files[0].name : 'No file chosen'">
                        
                        <div class="flex items-center w-full rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden text-sm">
                            <div class="bg-indigo-50 text-indigo-700 px-4 py-2.5 font-semibold hover:bg-indigo-100 transition-colors shrink-0 border-r border-slate-200">
                                Choose File
                            </div>
                            <div class="px-4 text-slate-500 truncate" id="file-name-edit">
                                No file chosen
                            </div>
                        </div>
                    </div>
                    @error('foto_alat') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3 text-amber-800 shadow-sm">
                    <i data-lucide="info" class="w-5 h-5 shrink-0 text-amber-600 mt-0.5"></i>
                    <div class="text-sm">
                        <p class="mb-1"><strong>Info Stok:</strong> Total Stok saat ini <span class="font-bold">{{ $tool->stok_total }}</span>, Stok Tersedia <span class="font-bold">{{ $tool->stok_tersedia }}</span>.</p>
                        <p class="text-amber-700/80 leading-relaxed">Untuk mengubah jumlah stok (menambah alat baru/rusak), harap gunakan fitur <span class="font-semibold">Mutasi Barang</span>.</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
