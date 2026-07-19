@extends('layouts.app')

@section('title', 'Peminjaman Saya')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <p class="text-sm text-slate-500">Riwayat dan status pengajuan peminjaman alat lab kamu.</p>
    </div>
    <a href="{{ route('mahasiswa.borrowings.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm hover:shadow-md shrink-0">
        <i data-lucide="plus" class="w-4 h-4"></i> Ajukan Peminjaman
    </a>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Tgl Pengajuan</th>
                    <th class="px-6 py-4">Alat Dipinjam</th>
                    <th class="px-6 py-4">Jadwal Pinjam</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $borrowing->tgl_pengajuan->format('d M Y') }}</div>
                            <div class="text-xs text-slate-500 font-mono mt-1">#B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[250px] truncate font-semibold text-slate-800" title="{{ $borrowing->items->pluck('tool.nama_alat')->implode(', ') }}">
                                {{ $borrowing->items->first()->tool->nama_alat }} 
                                @if($borrowing->items->count() > 1)
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-200/50 px-1.5 py-0.5 rounded ml-1">+{{ $borrowing->items->count() - 1 }} lagi</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-slate-700 font-medium">{{ $borrowing->tgl_rencana_pinjam->format('d M') }} - {{ $borrowing->tgl_rencana_kembali->format('d M Y') }}</div>
                            @if($borrowing->isOverdue())
                                <div class="text-xs text-red-600 font-bold mt-1 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> Terlambat</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($borrowing->status == 'Menunggu Persetujuan')
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Menunggu Persetujuan</span>
                            @elseif($borrowing->status == 'Disetujui')
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">Disetujui</span>
                            @elseif($borrowing->status == 'Dipinjam')
                                <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700">Dipinjam</span>
                            @elseif($borrowing->status == 'Dikembalikan')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Dikembalikan</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('mahasiswa.borrowings.show', $borrowing) }}" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors shadow-sm">
                                Lihat Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-700 mb-1">Belum ada peminjaman</p>
                            <p class="text-sm mb-4">Kamu belum pernah mengajukan peminjaman alat lab.</p>
                            <a href="{{ route('mahasiswa.catalog.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-semibold text-sm transition-colors">Cari alat di Katalog &rarr;</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $borrowings->links() }}
        </div>
    @endif
</div>

@if(session('success'))
<script>
    // Clear cart upon successful submission
    localStorage.removeItem('cart_items_{{ auth()->id() }}');
    localStorage.removeItem('cart_open_{{ auth()->id() }}');
</script>
@endif

@endsection
