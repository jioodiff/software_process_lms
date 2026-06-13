@extends('layouts.app')

@section('title', 'Pusat Laporan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">
    
    <a href="{{ route('admin.reports.borrowings') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-start gap-4 hover:shadow-md hover:border-indigo-300 transition-all group">
        <div class="h-12 w-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
            <i data-lucide="clipboard-list" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-lg mb-1">Rekap Peminjaman</h3>
            <p class="text-sm text-slate-500">Laporan seluruh riwayat pengajuan dan peminjaman dengan filter rentang tanggal dan status.</p>
        </div>
    </a>

    <a href="{{ route('admin.reports.popular-tools') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-start gap-4 hover:shadow-md hover:indigo-300 transition-all group">
        <div class="h-12 w-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
            <i data-lucide="bar-chart-2" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-lg mb-1">Alat Sering Dipinjam</h3>
            <p class="text-sm text-slate-500">Peringkat alat lab berdasarkan jumlah frekuensi dipinjam oleh mahasiswa.</p>
        </div>
    </a>

    <a href="{{ route('admin.reports.inventory') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-start gap-4 hover:shadow-md hover:border-indigo-300 transition-all group">
        <div class="h-12 w-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 group-hover:bg-amber-600 group-hover:text-white transition-colors">
            <i data-lucide="boxes" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-lg mb-1">Status Inventaris Barang</h3>
            <p class="text-sm text-slate-500">Laporan kondisi dan jumlah stok fisik seluruh inventaris barang lab saat ini.</p>
        </div>
    </a>

    <a href="{{ route('admin.reports.active-borrowings') }}" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 flex items-start gap-4 hover:shadow-md hover:border-indigo-300 transition-all group">
        <div class="h-12 w-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-colors">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div>
            <h3 class="font-bold text-slate-800 text-lg mb-1">Alat Sedang Dipinjam</h3>
            <p class="text-sm text-slate-500">Daftar peminjaman yang masih aktif dan belum dikembalikan (termasuk yang overdue).</p>
        </div>
    </a>
</div>
@endsection
