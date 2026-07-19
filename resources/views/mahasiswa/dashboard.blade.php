@extends('layouts.app')

@section('title', ucfirst(auth()->user()->role) . ' Dashboard')

@section('content')
<div class="space-y-6 mb-8">
    
    <!-- TOP ROW: Welcome & Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">
        <!-- Welcome Card -->
        <div class="xl:col-span-2 bg-white rounded-2xl p-6 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 relative overflow-hidden flex flex-col justify-center h-full">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="z-10">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-800 mb-2">Halo, {{ explode(' ', auth()->user()->nama_lengkap)[0] }}!</h2>
                <p class="text-slate-500 mb-5 text-sm leading-relaxed">Cari alat lab yang kamu butuhkan dan ajukan peminjaman sekarang.</p>
                
                <a href="{{ route('mahasiswa.catalog.index') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition-all transform hover:-translate-y-0.5 w-max">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari Alat Lab
                </a>
            </div>
        </div>

        <!-- Total Pinjam -->
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 flex flex-col justify-between group h-full">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="layers" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900 leading-tight">{{ $totalPeminjaman }}</h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Total Peminjaman</p>
            </div>
        </div>

        <!-- Selesai -->
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 flex flex-col justify-between group h-full">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100/50 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                    <i data-lucide="check-circle" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $selesai }}</h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Dikembalikan</p>
            </div>
        </div>

        <!-- Ditolak -->
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 flex flex-col justify-between group h-full">
            <div class="flex justify-between items-start mb-4">
                <div class="h-10 w-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center border border-red-100/50 group-hover:bg-red-500 group-hover:text-white transition-colors">
                    <i data-lucide="x-circle" class="w-5 h-5"></i>
                </div>
            </div>
            <div>
                <h3 class="text-2xl font-bold text-slate-900">{{ $ditolak }}</h3>
                <p class="text-xs font-medium text-slate-500 mt-1">Ditolak</p>
            </div>
        </div>
    </div>

    <!-- BOTTOM ROW: Main Content & Sidebar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column: Active Borrowing & Popular Tools -->
        <div class="lg:col-span-2 space-y-6">
            @if($activeBorrowing)
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border {{ $activeBorrowing->isOverdue() ? 'border-rose-300 ring-4 ring-rose-50' : 'border-slate-100' }} overflow-hidden relative transition-all">
                @if($activeBorrowing->isOverdue())
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500"></div>
                @endif
                
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center relative z-10 bg-white">
                    <h3 class="font-bold {{ $activeBorrowing->isOverdue() ? 'text-rose-700' : 'text-slate-800' }} flex items-center gap-2 text-lg">
                        <i data-lucide="activity" class="w-5 h-5 {{ $activeBorrowing->isOverdue() ? 'text-rose-600' : 'text-blue-500' }}"></i>
                        Peminjaman Aktif
                    </h3>
                    @if($activeBorrowing->status == 'Menunggu Persetujuan')
                        <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Menunggu</span>
                    @elseif($activeBorrowing->status == 'Disetujui')
                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">Disetujui</span>
                    @elseif($activeBorrowing->status == 'Dipinjam')
                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-700">Dipinjam</span>
                    @endif
                </div>
                <div class="p-6 relative z-10 bg-white">
                    @if($activeBorrowing->isOverdue())
                        <div class="mb-5 bg-rose-50 border border-rose-200 rounded-xl p-4 flex gap-3 text-rose-800">
                            <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 text-rose-600"></i>
                            <div>
                                <p class="text-sm font-bold mb-0.5">Terlambat Mengembalikan!</p>
                                <p class="text-xs">Peminjaman ini telah melewati batas waktu pengembalian. Harap segera kembalikan alat ke laboratorium.</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-5 gap-3">
                        <div class="text-sm text-slate-500">Rencana Pinjam: <span class="font-semibold text-slate-900 ml-1">{{ $activeBorrowing->tgl_rencana_pinjam->format('d M Y') }}</span></div>
                        <div class="text-sm text-slate-500">Rencana Kembali: <span class="font-semibold {{ $activeBorrowing->isOverdue() ? 'text-rose-600' : 'text-slate-900' }} ml-1">{{ $activeBorrowing->tgl_rencana_kembali->format('d M Y') }}</span></div>
                    </div>
                    
                    <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                        <h4 class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-4">Alat yang Dipinjam:</h4>
                        <ul class="space-y-3">
                            @foreach($activeBorrowing->items as $item)
                            <li class="flex items-center justify-between text-sm">
                                <span class="font-medium text-slate-800">{{ $item->tool->nama_alat }}</span>
                                <span class="text-slate-600 px-2.5 py-1 bg-white rounded-md border border-slate-200 font-medium text-xs shadow-sm">{{ $item->jumlah_unit }} unit</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    
                    <div class="mt-5 flex justify-end">
                        <a href="{{ route('mahasiswa.borrowings.show', $activeBorrowing) }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 flex items-center gap-1 transition-colors">
                            Lihat Detail <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Alat Sering Dipinjam -->
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                        <i data-lucide="flame" class="w-5 h-5 text-orange-500"></i>
                        Alat Sering Dipinjam
                    </h3>
                    <a href="{{ route('mahasiswa.catalog.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">
                        Lihat Semua
                    </a>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @forelse($popularTools as $tool)
                            <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 hover:border-blue-200 hover:shadow-sm transition-all group flex flex-col justify-between">
                                <div>
                                    <div class="w-full h-32 bg-white rounded-lg mb-4 flex items-center justify-center border border-slate-100 overflow-hidden">
                                        @if($tool->foto_alat)
                                            <img src="{{ Storage::url($tool->foto_alat) }}" alt="{{ $tool->nama_alat }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <i data-lucide="image" class="w-8 h-8 text-slate-300"></i>
                                        @endif
                                    </div>
                                    <h4 class="font-semibold text-slate-800 text-sm mb-1 line-clamp-1" title="{{ $tool->nama_alat }}">{{ $tool->nama_alat }}</h4>
                                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[10px] font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mb-2">{{ $tool->kategori }}</span>
                                </div>
                                <div class="flex items-center justify-between mt-2 pt-3 border-t border-slate-200/60">
                                    <span class="text-xs font-medium text-slate-500">{{ $tool->stok_tersedia }} Unit</span>
                                    <a href="{{ route('mahasiswa.catalog.index') }}?search={{ urlencode($tool->nama_alat) }}" class="w-7 h-7 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-600 group-hover:border-blue-300 transition-colors" title="Pinjam Alat">
                                        <i data-lucide="plus" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 text-center py-8 text-slate-500 text-sm">
                                Belum ada alat lab tersedia di katalog
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: History -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-lg">Riwayat Terakhir</h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($recentBorrowings as $borrowing)
                        <div class="p-5 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                            <div>
                                <p class="text-xs text-slate-500 mb-2 font-medium">{{ $borrowing->tgl_pengajuan->format('d M Y') }}</p>
                                @if($borrowing->status == 'Menunggu Persetujuan')
                                    <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-700">Menunggu</span>
                                @elseif($borrowing->status == 'Disetujui')
                                    <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-bold text-blue-700">Disetujui</span>
                                @elseif($borrowing->status == 'Dipinjam')
                                    <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-bold text-purple-700">Dipinjam</span>
                                @elseif($borrowing->status == 'Dikembalikan')
                                    <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-bold text-green-700">Dikembalikan</span>
                                @else
                                    <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[10px] font-bold text-red-700">Ditolak</span>
                                @endif
                            </div>
                            <a href="{{ route('mahasiswa.borrowings.show', $borrowing) }}" class="p-2.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all">
                                <i data-lucide="chevron-right" class="w-5 h-5"></i>
                            </a>
                        </div>
                    @empty
                        <div class="p-8 text-center text-slate-500 text-sm font-medium">
                            Belum ada riwayat peminjaman
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 text-center">
                    <a href="{{ route('mahasiswa.borrowings.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lihat Semua Riwayat</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
