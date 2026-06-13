@extends('layouts.app')

@section('title', 'Form Pengembalian Alat')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Pencatatan Pengembalian</h2>
            <p class="text-sm text-slate-500">Peminjaman #B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }} • {{ $borrowing->mahasiswa->nama_lengkap }}</p>
        </div>
    </div>

    <form action="{{ route('admin.borrowings.processReturn', $borrowing) }}" method="POST">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                <i data-lucide="clipboard-check" class="w-4 h-4 text-slate-400"></i>
                <h3 class="font-semibold text-slate-800">Cek Fisik & Kondisi Alat</h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @foreach($borrowing->items as $item)
                <div class="p-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-start hover:bg-slate-50/50 transition-colors">
                    <div class="md:col-span-4 lg:col-span-5">
                        <div class="flex items-start gap-4">
                            @if($item->tool->foto_alat)
                                <img src="{{ Storage::url($item->tool->foto_alat) }}" alt="{{ $item->tool->nama_alat }}" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                            @else
                                <div class="w-16 h-16 rounded-lg bg-slate-100 flex items-center justify-center border border-slate-200 text-slate-400">
                                    <i data-lucide="image" class="w-6 h-6"></i>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs font-mono text-slate-500 mb-1">{{ $item->tool->kode_alat }}</p>
                                <p class="font-medium text-slate-900 leading-tight mb-1">{{ $item->tool->nama_alat }}</p>
                                <span class="inline-flex items-center rounded bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-700 border border-indigo-100">{{ $item->jumlah_unit }} unit</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="md:col-span-8 lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5.5">Kondisi Saat Kembali <span class="text-rose-500">*</span></label>
                            <select name="kondisi[{{ $item->id }}]" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                                <option value="Baik">Baik (Sesuai awal pinjam)</option>
                                <option value="Rusak Ringan">Rusak Ringan (Bisa diperbaiki)</option>
                                <option value="Rusak Berat">Rusak Berat (Tidak bisa dipakai)</option>
                            </select>
                            <p class="text-[10px] text-slate-400 mt-1.5">Jika rusak, stok tidak akan ditambahkan kembali ke 'Tersedia'.</p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5.5">Catatan (Opsional)</label>
                            <textarea name="catatan[{{ $item->id }}]" rows="2" placeholder="Detail kerusakan atau info lain..." class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm"></textarea>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-700 font-medium hover:bg-slate-50 transition-colors">Batal</a>
            <button type="submit" class="px-6 py-3 rounded-xl bg-slate-800 text-white font-medium hover:bg-slate-900 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Pengembalian
            </button>
        </div>
    </form>
</div>
@endsection
