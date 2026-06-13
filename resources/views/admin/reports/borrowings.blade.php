@extends('layouts.app')

@section('title', 'Laporan Peminjaman Alat')

@section('content')
<div class="mb-6 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
    <form action="{{ route('admin.reports.borrowings') }}" method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Dari Tanggal</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sampai Tanggal</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
        </div>
        <div>
            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Status</label>
            <select name="status" class="rounded-xl border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                <option value="">Semua Status</option>
                <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition-colors">Tampilkan Laporan</button>
            <button type="button" onclick="window.print()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2">
                <i data-lucide="printer" class="w-4 h-4"></i> Cetak
            </button>
        </div>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden print:shadow-none print:border-none">
    <div class="hidden print:block p-6 text-center border-b border-slate-200">
        <h2 class="text-2xl font-bold">Laporan Peminjaman Alat</h2>
        <p class="text-sm text-slate-500">Lab Management System Universitas IPWIJA</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Tgl Pengajuan</th>
                    <th class="px-6 py-4">Peminjam</th>
                    <th class="px-6 py-4">Alat (Jumlah)</th>
                    <th class="px-6 py-4">Jadwal Pinjam</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($borrowings as $borrowing)
                    <tr>
                        <td class="px-6 py-3 text-slate-500">{{ $borrowing->tgl_pengajuan->format('d M Y') }}</td>
                        <td class="px-6 py-3">
                            <div class="font-medium">{{ $borrowing->mahasiswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-500">{{ $borrowing->mahasiswa->nim }}</div>
                        </td>
                        <td class="px-6 py-3 text-slate-700 truncate max-w-[200px]" title="{{ $borrowing->items->pluck('tool.nama_alat')->implode(', ') }}">
                            {{ $borrowing->items->pluck('tool.nama_alat')->implode(', ') }}
                        </td>
                        <td class="px-6 py-3 text-slate-700">
                            {{ $borrowing->tgl_rencana_pinjam->format('d/m/Y') }} - {{ $borrowing->tgl_rencana_kembali->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-3">
                            {{ $borrowing->status }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Tidak ada data untuk filter yang dipilih.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 print:hidden">
            {{ $borrowings->links() }}
        </div>
    @endif
</div>
@endsection
