@extends('layouts.app')

@section('title', 'Kelola Peminjaman')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
    <form action="{{ route('admin.borrowings.index') }}" method="GET" class="flex gap-3 w-full sm:w-auto">
        <div class="relative flex-1 sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari peminjam..." class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
        </div>
        <select name="status" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
            <option value="">Semua Status</option>
            <option value="Menunggu Persetujuan" {{ request('status') == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
            <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            <option value="Terlambat" {{ request('status') == 'Terlambat' || request('overdue') == '1' ? 'selected' : '' }}>Terlambat</option>
        </select>
        <button type="submit" class="bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
            Filter
        </button>
        @if(request()->anyFilled(['search', 'status']))
            <a href="{{ route('admin.borrowings.index') }}" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center px-2 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">ID / Tgl Pengajuan</th>
                    <th class="px-6 py-4">Peminjam</th>
                    <th class="px-6 py-4">Alat (Jumlah)</th>
                    <th class="px-6 py-4">Jadwal Pinjam</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="font-mono text-slate-900 font-semibold">#B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $borrowing->tgl_pengajuan->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-semibold text-slate-800">{{ $borrowing->mahasiswa->nama_lengkap }}</div>
                            <div class="text-xs text-slate-500 mt-1">{{ $borrowing->mahasiswa->nim }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="max-w-[200px] truncate text-slate-700 font-medium" title="{{ $borrowing->items->pluck('tool.nama_alat')->implode(', ') }}">
                                {{ $borrowing->items->first()->tool->nama_alat }} 
                                @if($borrowing->items->count() > 1)
                                    <span class="text-[10px] font-bold text-slate-500 bg-slate-200/50 px-1.5 py-0.5 rounded ml-1">+{{ $borrowing->items->count() - 1 }}</span>
                                @endif
                            </div>
                            <div class="text-[11px] text-blue-600 font-bold mt-1 bg-blue-50 inline-block px-2 py-0.5 rounded-md">{{ $borrowing->items->sum('jumlah_unit') }} unit total</div>
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
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="inline-flex items-center justify-center bg-white border border-slate-200 text-slate-700 hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors shadow-sm">
                                Detail & Proses
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-700 mb-1">Tidak ada data peminjaman</p>
                            <p class="text-sm">Belum ada pengajuan peminjaman atau sesuai dengan filter.</p>
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
@endsection
