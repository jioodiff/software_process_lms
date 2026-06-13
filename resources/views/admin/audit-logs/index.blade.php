@extends('layouts.app')

@section('title', 'Audit Trail Logs')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
    <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-3 w-full">
        <select name="modul" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all w-36">
            <option value="">Semua Modul</option>
            @foreach($moduls as $modul)
                <option value="{{ $modul }}" {{ request('modul') == $modul ? 'selected' : '' }}>{{ $modul }}</option>
            @endforeach
        </select>
        <select name="aksi" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all w-36">
            <option value="">Semua Aksi</option>
            @foreach($aksis as $aksi)
                <option value="{{ $aksi }}" {{ request('aksi') == $aksi ? 'selected' : '' }}>{{ $aksi }}</option>
            @endforeach
        </select>
        <input type="date" name="start_date" value="{{ request('start_date') }}" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all shadow-sm">Filter</button>
        @if(request()->anyFilled(['modul', 'aksi', 'start_date']))
            <a href="{{ route('admin.audit-logs.index') }}" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center px-2 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 bg-amber-50/50 border-b border-amber-100/50 flex items-start gap-3">
        <i data-lucide="shield-check" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
        <div class="text-sm text-amber-700">
            <span class="font-bold">Append-Only Audit Trail.</span> Data log ini digenerate otomatis oleh sistem dan tidak dapat dimodifikasi (Update/Delete) oleh siapapun demi integritas data.
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Timestamp (UTC)</th>
                    <th class="px-6 py-4">Modul / Aksi</th>
                    <th class="px-6 py-4">Pelaku</th>
                    <th class="px-6 py-4">Record ID</th>
                    <th class="px-6 py-4">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 font-mono text-xs">
                @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-slate-500">{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4">
                            <span class="font-bold text-slate-700">{{ $log->modul }}</span> / 
                            <span class="text-blue-600 font-semibold">{{ $log->aksi }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-slate-700">{{ $log->nama_user ?? 'System' }}</span>
                            <span class="text-[10px] text-slate-400 block mt-0.5">({{ $log->role_pelaku }})</span>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $log->id_record ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-sans">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="shield-off" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-700 mb-1">Tidak ada audit log</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
    @endif
</div>
@endsection
