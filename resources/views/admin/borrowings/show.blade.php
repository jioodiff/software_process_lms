@extends('layouts.app')

@section('title', 'Detail Peminjaman #B-'.str_pad($borrowing->id, 4, '0', STR_PAD_LEFT))

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.borrowings.index') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Detail Peminjaman</h2>
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
    <!-- Left Column: Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Student Info -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50 flex items-center gap-2">
                <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                <h3 class="font-semibold text-slate-800">Informasi Peminjam</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                <div>
                    <p class="text-xs text-slate-500 mb-1">Nama Lengkap</p>
                    <p class="font-medium text-slate-900">{{ $borrowing->mahasiswa->nama_lengkap }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-1">NIM</p>
                    <p class="font-medium text-slate-900">{{ $borrowing->mahasiswa->nim }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-1">Email</p>
                    <p class="font-medium text-slate-900">{{ $borrowing->mahasiswa->email }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 mb-1">Program Studi</p>
                    <p class="font-medium text-slate-900">{{ $borrowing->mahasiswa->program_studi }}</p>
                </div>
            </div>
        </div>

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
                    <div class="flex-1 bg-indigo-50/50 border border-indigo-100 rounded-xl p-4 relative">
                        <div class="absolute top-1/2 -left-3 transform -translate-y-1/2 w-6 h-6 bg-white border border-indigo-100 rounded-full flex items-center justify-center z-10 hidden md:flex">
                            <i data-lucide="arrow-right" class="w-3 h-3 text-indigo-400"></i>
                        </div>
                        <p class="text-xs text-indigo-600 font-medium mb-1 uppercase tracking-wider">Tenggat Kembali</p>
                        <p class="text-lg font-bold text-slate-900 {{ $borrowing->isOverdue() ? 'text-rose-600' : '' }}">{{ $borrowing->tgl_rencana_kembali->format('d M Y') }}</p>
                        @if($borrowing->isOverdue())
                            <span class="absolute top-4 right-4 flex h-3 w-3">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                            </span>
                        @endif
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
                            @if(in_array($borrowing->status, ['Dikembalikan']))
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
                            @if(in_array($borrowing->status, ['Dikembalikan']))
                                <td class="px-6 py-4">
                                    @if($item->kondisi_saat_kembali == 'Baik')
                                        <span class="text-emerald-600 flex items-center gap-1"><i data-lucide="check" class="w-3 h-3"></i> Baik</span>
                                    @else
                                        <span class="text-rose-600 flex items-center gap-1" title="{{ $item->catatan_pengembalian }}"><i data-lucide="alert-triangle" class="w-3 h-3"></i> {{ $item->kondisi_saat_kembali }}</span>
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

    <!-- Right Column: Actions -->
    <div class="space-y-6">
        @if($borrowing->status == 'Menunggu Persetujuan')
            <div class="bg-white rounded-2xl shadow-md border border-indigo-100 overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-indigo-500"></div>
                <div class="p-6">
                    <h3 class="font-bold text-lg text-slate-800 mb-4 flex items-center gap-2">
                        <i data-lucide="check-square" class="w-5 h-5 text-indigo-600"></i> Tindakan Admin
                    </h3>
                    
                    <div class="space-y-3">
                        <form action="{{ route('admin.borrowings.approve', $borrowing) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-xl font-semibold shadow-sm transition-all hover:shadow-md">
                                <i data-lucide="check" class="w-4 h-4"></i> Setujui Pengajuan
                            </button>
                        </form>
                        
                        <div x-data="{ open: false }">
                            <button @click="open = !open" type="button" class="w-full flex items-center justify-center gap-2 bg-white border border-rose-200 text-rose-600 hover:bg-rose-50 py-3 px-4 rounded-xl font-semibold transition-colors">
                                <i data-lucide="x" class="w-4 h-4"></i> Tolak Pengajuan
                            </button>
                            
                            <div x-show="open" x-cloak class="mt-4 p-4 bg-rose-50 border border-rose-100 rounded-xl" x-transition>
                                <form action="{{ route('admin.borrowings.reject', $borrowing) }}" method="POST">
                                    @csrf
                                    <label class="block text-xs font-semibold text-rose-800 mb-1.5">Alasan Penolakan <span class="text-rose-500">*</span></label>
                                    <textarea name="catatan_admin" required rows="2" class="w-full rounded-xl border border-rose-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition-colors bg-white shadow-sm mb-3"></textarea>
                                    <button type="submit" class="w-full bg-rose-600 hover:bg-rose-700 text-white py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">Konfirmasi Tolak</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($borrowing->status == 'Disetujui')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-lg text-slate-800 mb-2">Penyerahan Alat</h3>
                <p class="text-sm text-slate-500 mb-6">Mahasiswa akan mengambil alat. Konfirmasi jika alat sudah diserahkan.</p>
                <form action="{{ route('admin.borrowings.handover', $borrowing) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white py-3 px-4 rounded-xl font-semibold shadow-sm transition-all hover:shadow-md">
                        <i data-lucide="package-check" class="w-5 h-5"></i> Alat Diserahkan
                    </button>
                </form>
            </div>
        @elseif($borrowing->status == 'Dipinjam')
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <h3 class="font-bold text-lg text-slate-800 mb-2">Pengembalian Alat</h3>
                <p class="text-sm text-slate-500 mb-6">Mahasiswa mengembalikan alat. Cek kondisi alat sebelum mencatat.</p>
                <a href="{{ route('admin.borrowings.return', $borrowing) }}" class="w-full flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-900 text-white py-3 px-4 rounded-xl font-semibold shadow-sm transition-all hover:shadow-md">
                    <i data-lucide="clipboard-check" class="w-5 h-5"></i> Form Pengembalian
                </a>
            </div>
        @endif

        <!-- Status Info & Logs -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="font-semibold text-slate-800 mb-4 text-sm uppercase tracking-wider">Log Riwayat</h3>
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
                        <p class="text-xs text-slate-400 mt-1">{{ $borrowing->admin->nama_lengkap }}</p>
                        @if($borrowing->catatan_admin)
                            <div class="mt-2 text-xs bg-slate-50 p-2 rounded border border-slate-100 text-slate-600">
                                "{{ $borrowing->catatan_admin }}"
                            </div>
                        @endif
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
