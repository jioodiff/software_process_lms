@extends('layouts.app')

@section('title', 'Laporan Alat Populer')

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
        <h2 class="text-xl font-bold text-slate-800">Laporan Alat Sering Dipinjam</h2>
        <p class="text-sm text-slate-500 mt-1">Peringkat Alat Laboratorium berdasarkan frekuensi peminjaman.</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50 text-slate-600 font-medium border-b border-slate-200">
                <tr>
                    <th class="px-6 py-4">Peringkat</th>
                    <th class="px-6 py-4">Kode Alat</th>
                    <th class="px-6 py-4">Nama Alat</th>
                    <th class="px-6 py-4 text-center">Kali Dipinjam</th>
                    <th class="px-6 py-4 text-center">Total Unit Dipinjam</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($tools as $index => $tool)
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-4 font-bold text-slate-400">#{{ $index + 1 }}</td>
                        <td class="px-6 py-4 font-mono text-slate-500">{{ $tool->kode_alat }}</td>
                        <td class="px-6 py-4 font-medium text-slate-800">{{ $tool->nama_alat }}</td>
                        <td class="px-6 py-4 text-center font-bold text-indigo-600">{{ $tool->borrowing_count }} kali</td>
                        <td class="px-6 py-4 text-center font-medium text-slate-700">{{ $tool->total_dipinjam ?? 0 }} unit</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada data peminjaman alat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
