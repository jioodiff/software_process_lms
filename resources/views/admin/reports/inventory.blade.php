@extends('layouts.app')

@section('title', 'Laporan Inventaris')

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
        <h2 class="text-xl font-bold text-slate-800">Laporan Status Inventaris Barang</h2>
        <p class="text-sm text-slate-500 mt-1">Total aset barang yang terdaftar: {{ $items->count() }} jenis barang.</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Kode Barang</th>
                    <th class="px-6 py-4">Nama Barang</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok Saat Ini</th>
                    <th class="px-6 py-4">Kondisi / Lokasi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($items as $item)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-3 font-mono text-slate-500">{{ $item->kode_barang }}</td>
                        <td class="px-6 py-3 font-medium text-slate-800">{{ $item->nama_barang }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $item->kategori }}</td>
                        <td class="px-6 py-3 text-center font-bold text-indigo-600">{{ $item->stok }}</td>
                        <td class="px-6 py-3 text-slate-600">{{ $item->kondisi }} • {{ $item->lokasi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
