@extends('layouts.app')

@section('title', 'Laporan Peminjaman Aktif')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.reports.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
        <i data-lucide="arrow-left" class="w-5 h-5"></i>
    </a>
    <button type="button" onclick="window.print()" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2 rounded-xl text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
        <i data-lucide="printer" class="w-4 h-4"></i> Cetak PDF
    </button>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden print:shadow-none print:border-none">
    <div class="p-6 border-b border-slate-200 text-center">
        <h2 class="text-xl font-bold text-slate-800">Daftar Alat Sedang Dipinjam</h2>
        <p class="text-sm text-slate-500 mt-1">Peminjaman yang masih aktif (belum dikembalikan).</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">ID / Peminjam</th>
                    <th class="px-6 py-4">Alat (Jumlah)</th>
                    <th class="px-6 py-4">Tgl Pinjam</th>
                    <th class="px-6 py-4">Batas Kembali</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4">
                            <div class="font-medium text-slate-800">{{ $borrowing->mahasiswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-500">#B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4 text-slate-700">
                            {{ $borrowing->items->pluck('tool.nama_alat')->implode(', ') }}
                            <div class="text-xs text-indigo-600 font-medium mt-0.5">{{ $borrowing->items->sum('jumlah_unit') }} unit total</div>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $borrowing->tgl_rencana_pinjam->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 font-bold {{ $borrowing->isOverdue() ? 'text-rose-600' : 'text-slate-700' }}">
                            {{ $borrowing->tgl_rencana_kembali->format('d/m/Y') }}
                            @if($borrowing->isOverdue())
                                <span class="text-xs ml-1">(Overdue)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20">Sedang Dipinjam</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Tidak ada alat yang sedang dipinjam saat ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
