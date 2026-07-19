@extends('layouts.app')

@section('title', 'Log Mutasi Stok')

@section('content')
<div class="w-full">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('admin.items.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-xl font-bold text-slate-800">Mutasi Stok: {{ $item->nama_barang }}</h2>
            <p class="text-sm text-slate-500">Kode: {{ $item->kode_barang }} • Stok Fisik Saat Ini: <span class="font-bold text-indigo-600">{{ $item->stok }} unit</span></p>
        </div>
    </div>

    <!-- Form Mutasi Baru -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
        <div class="px-6 py-4 border-b border-slate-100 bg-indigo-50 flex items-center gap-2">
            <i data-lucide="plus-circle" class="w-4 h-4 text-indigo-600"></i>
            <h3 class="font-semibold text-indigo-900">Catat Mutasi Baru</h3>
        </div>
        <form action="{{ route('admin.items.storeMutasi', $item) }}" method="POST" class="p-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5.5">Tipe Mutasi <span class="text-rose-500">*</span></label>
                    <div class="relative w-full">
                        <select name="tipe_mutasi" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 appearance-none" onchange="document.getElementById('tipe-mutasi-item-text').innerText = this.options[this.selectedIndex].text">
                            <option value="Masuk">Masuk (Tambah Stok)</option>
                            <option value="Keluar">Keluar (Kurangi Stok)</option>
                            <option value="Penyesuaian">Penyesuaian (Ganti Total Stok)</option>
                        </select>
                        <div class="flex items-center justify-between w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm shadow-sm transition-colors">
                            <span id="tipe-mutasi-item-text">Masuk (Tambah Stok)</span>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5.5">Jumlah <span class="text-rose-500">*</span></label>
                    <input type="number" name="jumlah" min="1" required placeholder="Contoh: 5" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5.5">Keterangan Tambahan</label>
                    <div class="flex gap-2">
                        <input type="text" name="keterangan" placeholder="Contoh: Barang rusak, Pembelian baru..." class="flex-1 rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors shrink-0">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Tabel Riwayat -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
            <h3 class="font-semibold text-slate-800 text-sm">Riwayat Mutasi</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-white text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-3 font-medium">Waktu</th>
                        <th class="px-6 py-3 font-medium">Tipe</th>
                        <th class="px-6 py-3 font-medium text-center">Mutasi</th>
                        <th class="px-6 py-3 font-medium text-center">Sisa Stok</th>
                        <th class="px-6 py-3 font-medium">Admin</th>
                        <th class="px-6 py-3 font-medium">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($mutations as $mutasi)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-3 text-slate-500">{{ $mutasi->timestamp->format('d M Y, H:i') }}</td>
                            <td class="px-6 py-3">
                                @if($mutasi->tipe_mutasi == 'Masuk')
                                    <span class="text-emerald-600 font-medium text-xs bg-emerald-50 px-2 py-1 rounded">Masuk</span>
                                @elseif($mutasi->tipe_mutasi == 'Keluar')
                                    <span class="text-rose-600 font-medium text-xs bg-rose-50 px-2 py-1 rounded">Keluar</span>
                                @else
                                    <span class="text-blue-600 font-medium text-xs bg-blue-50 px-2 py-1 rounded">Adjust</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 text-center font-mono">
                                {{ $mutasi->tipe_mutasi == 'Masuk' ? '+' : ($mutasi->tipe_mutasi == 'Keluar' ? '-' : '') }}{{ $mutasi->jumlah }}
                            </td>
                            <td class="px-6 py-3 text-center font-bold text-slate-800">
                                {{ $mutasi->stok_sesudah }}
                            </td>
                            <td class="px-6 py-3 text-slate-600">{{ $mutasi->user->nama_lengkap }}</td>
                            <td class="px-6 py-3 text-slate-500 text-xs max-w-xs truncate" title="{{ $mutasi->keterangan }}">{{ $mutasi->keterangan ?? '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada riwayat mutasi stok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($mutations->hasPages())
            <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                {{ $mutations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
