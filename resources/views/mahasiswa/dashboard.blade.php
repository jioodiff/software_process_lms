@extends('layouts.app')

@section('title', 'Mahasiswa Dashboard')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- Welcome & Active Borrowing Card -->
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-white rounded-[2rem] p-8 shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 relative overflow-hidden flex flex-col md:flex-row items-center justify-between gap-6 transition-all duration-300 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-full blur-3xl -z-10 transform translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="z-10">
                <h2 class="text-3xl font-bold text-slate-800 mb-2">Halo, {{ explode(' ', auth()->user()->nama_lengkap)[0] }}! 👋</h2>
                <p class="text-slate-500 mb-8 max-w-md text-base leading-relaxed">Selamat datang di Lab Management System. Cari alat lab yang kamu butuhkan dan ajukan peminjaman sekarang.</p>
                
                <a href="{{ route('mahasiswa.catalog.index') }}" class="inline-flex justify-center items-center gap-2 rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 hover:shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all transform hover:-translate-y-0.5">
                    <i data-lucide="search" class="w-4 h-4"></i>
                    Cari Alat Lab
                </a>
            </div>
            
            <div class="shrink-0 relative z-10 w-48 h-48 hidden md:block">
                <!-- Abstract illustration -->
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" class="w-full h-full drop-shadow-xl text-blue-500 opacity-90">
                    <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.1,-46.3C90.4,-33.5,96,-18.1,95.5,-2.9C95.1,12.3,88.7,27.3,79.1,40C69.5,52.7,56.7,63.1,42.4,70.5C28.1,77.9,12.3,82.3,-3.1,87.6C-18.5,92.9,-33.5,99,-46.8,94.2C-60.1,89.4,-71.7,73.7,-80.6,56.7C-89.5,39.7,-95.7,21.4,-94.1,3.8C-92.5,-13.8,-83.1,-30.7,-72.1,-44.6C-61.1,-58.5,-48.5,-69.4,-34.5,-76.5C-20.5,-83.6,-5.1,-86.9,9.4,-84.9C23.9,-82.9,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100) scale(0.9)" />
                </svg>
            </div>
        </div>

        @if($activeBorrowing)
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                    <i data-lucide="activity" class="w-5 h-5 text-blue-500"></i>
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
            <div class="p-6">
                <div class="flex items-center justify-between mb-5">
                    <div class="text-sm text-slate-500">Rencana Pinjam: <span class="font-semibold text-slate-900 ml-1">{{ $activeBorrowing->tgl_rencana_pinjam->format('d M Y') }}</span></div>
                    <div class="text-sm text-slate-500">Rencana Kembali: <span class="font-semibold text-slate-900 ml-1">{{ $activeBorrowing->tgl_rencana_kembali->format('d M Y') }}</span></div>
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
    </div>

    <!-- Quick Stats & History -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center gap-5 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300">
            <div class="h-14 w-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100/50">
                <i data-lucide="history" class="w-6 h-6"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500 mb-1">Total Riwayat Pinjam</p>
                <h3 class="text-3xl font-bold text-slate-900">{{ $totalPeminjaman }} <span class="text-sm font-medium text-slate-400">kali</span></h3>
            </div>
        </div>
        
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
@endsection
