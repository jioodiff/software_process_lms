@extends('layouts.app')

@section('title', 'Detail Peminjaman Saya')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('mahasiswa.borrowings.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Detail Peminjaman #B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</h2>
    </div>
    
    <div>
        @if($borrowing->status == 'Menunggu Persetujuan')
            <span class="inline-flex items-center rounded-md bg-amber-50 px-3 py-1.5 text-sm font-medium text-amber-700 ring-1 ring-inset ring-amber-600/20 shadow-sm"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2 animate-pulse"></span> Menunggu Persetujuan</span>
        @elseif($borrowing->status == 'Disetujui')
            <span class="inline-flex items-center rounded-md bg-blue-50 px-3 py-1.5 text-sm font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20 shadow-sm"><span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Disetujui</span>
        @elseif($borrowing->status == 'Dipinjam')
            <span class="inline-flex items-center rounded-md bg-purple-50 px-3 py-1.5 text-sm font-medium text-purple-700 ring-1 ring-inset ring-purple-600/20 shadow-sm"><span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span> Sedang Dipinjam</span>
        @elseif($borrowing->status == 'Dikembalikan')
            <span class="inline-flex items-center rounded-md bg-emerald-50 px-3 py-1.5 text-sm font-medium text-emerald-700 ring-1 ring-inset ring-emerald-600/20 shadow-sm"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Dikembalikan</span>
        @else
            <span class="inline-flex items-center rounded-md bg-rose-50 px-3 py-1.5 text-sm font-medium text-rose-700 ring-1 ring-inset ring-rose-600/20 shadow-sm"><span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Ditolak</span>
        @endif
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <!-- Status Banner -->
        @if($borrowing->status == 'Disetujui')
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-6 flex gap-4 text-emerald-800">
                <i data-lucide="check-circle-2" class="w-8 h-8 shrink-0 text-emerald-600"></i>
                <div>
                    <h3 class="font-bold text-lg mb-1">Pengajuan Disetujui!</h3>
                    <p class="text-sm">Silakan datang ke laboratorium untuk mengambil alat pada tanggal yang telah dijadwalkan ({{ $borrowing->tgl_rencana_pinjam->format('d M Y') }}). Tunjukkan halaman ini kepada Admin.</p>
                </div>
            </div>
        @elseif($borrowing->status == 'Dipinjam')
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 flex gap-4 text-blue-800 relative overflow-hidden">
                <i data-lucide="info" class="w-8 h-8 shrink-0 text-blue-600"></i>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-1">Alat Sedang Dipinjam</h3>
                    <p class="text-sm">Jangan lupa kembalikan alat tepat waktu. Tanggal kembali: <span class="font-bold">{{ $borrowing->tgl_rencana_kembali->format('d M Y') }}</span>.</p>
                </div>
                @if($borrowing->isOverdue())
                    <div class="absolute inset-0 border-2 border-rose-500 rounded-2xl pointer-events-none"></div>
                    <div class="mt-4 p-3 bg-rose-100 text-rose-800 rounded-xl text-sm font-medium flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-5 h-5"></i> Peminjaman telah melewati batas waktu pengembalian. Segera kembalikan alat!
                    </div>
                @endif
            </div>
        @elseif($borrowing->status == 'Ditolak')
            <div class="bg-rose-50 border border-rose-200 rounded-2xl p-6 flex gap-4 text-rose-800">
                <i data-lucide="x-circle" class="w-8 h-8 shrink-0 text-rose-600"></i>
                <div>
                    <h3 class="font-bold text-lg mb-1">Pengajuan Ditolak</h3>
                    <p class="text-sm mb-3">Mohon maaf, pengajuan kamu tidak dapat diproses.</p>
                    <div class="bg-white/50 rounded-lg p-3 text-sm italic">
                        Alasan: "{{ $borrowing->catatan_admin }}"
                    </div>
                </div>
            </div>
        @endif

        <!-- Borrowing Details -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                <h3 class="font-semibold text-slate-800">Jadwal & Keperluan</h3>
            </div>
            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-6 mb-6">
                    <div class="flex-1 bg-indigo-50/50 border border-indigo-100 rounded-xl p-4">
                        <p class="text-xs text-indigo-600 font-medium mb-1 uppercase tracking-wider">Tanggal Ambil</p>
                        <p class="text-lg font-bold text-slate-900">{{ $borrowing->tgl_rencana_pinjam->format('d M Y') }}</p>
                    </div>
                    <div class="flex-1 bg-indigo-50/50 border border-indigo-100 rounded-xl p-4">
                        <p class="text-xs text-indigo-600 font-medium mb-1 uppercase tracking-wider">Tenggat Kembali</p>
                        <p class="text-lg font-bold text-slate-900 {{ $borrowing->isOverdue() ? 'text-rose-600' : '' }}">{{ $borrowing->tgl_rencana_kembali->format('d M Y') }}</p>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-2 uppercase tracking-wider font-medium">Keperluan Praktikum/Proyek</p>
                    <div class="bg-slate-50 rounded-xl p-4 text-sm text-slate-700 leading-relaxed border border-slate-100">
                        {{ $borrowing->keperluan }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Items List -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex justify-between items-center">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i data-lucide="package" class="w-4 h-4 text-slate-400"></i>
                    Daftar Alat ({{ $borrowing->items->sum('jumlah_unit') }} unit)
                </h3>
            </div>
            <div class="p-0">
                <table class="w-full text-left text-sm whitespace-nowrap">
                    <thead class="bg-white text-slate-500 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-3 font-medium">Kode</th>
                            <th class="px-6 py-3 font-medium">Nama Alat</th>
                            <th class="px-6 py-3 text-center font-medium">Jumlah</th>
                            @if($borrowing->status == 'Dikembalikan')
                                <th class="px-6 py-3 font-medium">Kondisi Kembali</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($borrowing->items as $item)
                        <tr class="hover:bg-slate-50/50">
                            <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $item->tool->kode_alat }}</td>
                            <td class="px-6 py-4 font-medium text-slate-800">{{ $item->tool->nama_alat }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-md font-semibold border border-indigo-100">{{ $item->jumlah_unit }}</span>
                            </td>
                            @if($borrowing->status == 'Dikembalikan')
                                <td class="px-6 py-4">
                                    @if($item->kondisi_saat_kembali == 'Baik')
                                        <span class="text-emerald-600 flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> Baik</span>
                                    @else
                                        <span class="text-rose-600 flex items-center gap-1"><i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $item->kondisi_saat_kembali }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Timeline -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sticky top-24">
            <h3 class="font-semibold text-slate-800 mb-6 text-sm uppercase tracking-wider">Log Riwayat</h3>
            <div class="relative pl-4 border-l-2 border-slate-100 space-y-6">
                <div class="relative">
                    <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-amber-500 ring-4 ring-white"></div>
                    <p class="text-sm font-medium text-slate-900">Pengajuan dibuat</p>
                    <p class="text-xs text-slate-500">{{ $borrowing->tgl_pengajuan->format('d M Y, H:i') }}</p>
                </div>
                
                @if($borrowing->diproses_oleh)
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full {{ $borrowing->status == 'Ditolak' ? 'bg-rose-500' : 'bg-blue-500' }} ring-4 ring-white"></div>
                        <p class="text-sm font-medium text-slate-900">{{ $borrowing->status == 'Ditolak' ? 'Ditolak' : 'Disetujui' }} oleh Admin</p>
                        <p class="text-xs text-slate-500">{{ $borrowing->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif

                @if(in_array($borrowing->status, ['Dipinjam', 'Dikembalikan']))
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-purple-500 ring-4 ring-white"></div>
                        <p class="text-sm font-medium text-slate-900">Alat diambil (Dipinjam)</p>
                    </div>
                @endif
                
                @if($borrowing->status == 'Dikembalikan')
                    <div class="relative">
                        <div class="absolute -left-[21px] top-1 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-4 ring-white"></div>
                        <p class="text-sm font-medium text-slate-900">Alat dikembalikan</p>
                        <p class="text-xs text-slate-500">{{ $borrowing->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
