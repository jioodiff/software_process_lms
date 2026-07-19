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
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50 font-mono text-xs">
                @forelse($logs as $log)
                    @php
                        $targetName = $log->id_record ?? '-';
                        if ($log->id_record && in_array($log->modul, ['Alat', 'Inventaris'])) {
                            if ($log->modul === 'Alat') {
                                $model = \App\Models\Tool::withTrashed()->find($log->id_record);
                                $targetName = $model ? $model->nama_alat : $targetName;
                            } else {
                                $model = \App\Models\Item::withTrashed()->find($log->id_record);
                                $targetName = $model ? $model->nama_barang : $targetName;
                            }
                        }
                    @endphp
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 text-slate-500">{{ $log->timestamp->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-1.5">
                                <span class="font-bold text-slate-700">{{ $log->modul }}</span>
                                <span class="text-slate-400">/</span>
                                <span class="text-blue-600 font-semibold">{{ $log->aksi }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-700">{{ $log->nama_user ?? 'System' }}</span>
                                <span class="text-[10px] text-slate-400 mt-0.5">({{ $log->role_pelaku }})</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $log->id_record ?? '-' }}</td>
                        <td class="px-6 py-4 text-slate-400">{{ $log->ip_address ?? '-' }}</td>
                        <td class="px-6 py-4 text-center" x-data="{ showModal: false }">
                            <button @click="showModal = true" class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition-colors inline-flex items-center justify-center" title="Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            
                            <div x-show="showModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm whitespace-normal" @click.self="showModal = false" x-transition.opacity>
                                <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] flex flex-col text-left m-4" x-show="showModal" x-transition.scale.origin.center>
                                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0 rounded-t-2xl">
                                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                                            <i data-lucide="file-search" class="w-5 h-5 text-blue-600"></i>
                                            Detail Audit Log
                                        </h3>
                                        <button @click="showModal = false" class="text-slate-400 hover:text-red-500 transition-colors p-1 rounded-lg hover:bg-red-50">
                                            <i data-lucide="x" class="w-5 h-5"></i>
                                        </button>
                                    </div>
                                    <div class="p-6 overflow-y-auto font-sans text-sm">
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Timestamp (UTC)</p>
                                                <p class="font-medium text-slate-700">{{ $log->timestamp->format('Y-m-d H:i:s') }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Modul / Aksi</p>
                                                <p class="font-medium"><span class="font-bold text-slate-700">{{ $log->modul }}</span> / <span class="text-blue-600 font-semibold">{{ $log->aksi }}</span></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Pelaku</p>
                                                <p class="font-medium text-slate-700">{{ $log->nama_user ?? 'System' }} <span class="text-xs text-slate-400">({{ $log->role_pelaku }})</span></p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Target Record</p>
                                                <p class="font-medium text-slate-700">{{ $targetName }}</p>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">IP Address</p>
                                                <p class="font-medium text-slate-700">{{ $log->ip_address ?? '-' }}</p>
                                            </div>
                                            <div class="col-span-2 md:col-span-1">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Aktivitas</p>
                                                <p class="font-medium text-slate-700">
                                                    Pelaku melakukan aksi <span class="font-bold">{{ $log->aksi }}</span> pada modul <span class="font-bold">{{ $log->modul }}</span>
                                                    @if($targetName !== '-' && $targetName !== $log->id_record)
                                                        untuk <span class="font-bold">"{{ $targetName }}"</span>.
                                                    @else
                                                        .
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Data Sebelum -->
                                            <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden flex flex-col h-full">
                                                <div class="px-4 py-2 border-b border-slate-200 bg-slate-100/50 shrink-0">
                                                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Data Sebelum</p>
                                                </div>
                                                <div class="p-4 bg-white overflow-x-auto flex-1">
                                                    @if(empty($log->data_sebelum))
                                                        <p class="text-slate-400 italic text-xs">Tidak ada data</p>
                                                    @else
                                                        <table class="w-full text-left text-xs">
                                                            <tbody class="divide-y divide-slate-100">
                                                            @foreach((array)$log->data_sebelum as $key => $value)
                                                                <tr>
                                                                    <td class="py-2 pr-4 font-semibold text-slate-500 uppercase tracking-wider w-1/3 align-top">{{ str_replace('_', ' ', $key) }}</td>
                                                                    <td class="py-2 font-medium text-slate-800 align-top break-words">
                                                                        @if(is_array($value) || is_object($value))
                                                                            <div class="space-y-2 mt-1 max-h-48 overflow-y-auto pr-1">
                                                                                @foreach((array)$value as $k => $v)
                                                                                    @if(is_array($v) || is_object($v))
                                                                                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                                                                            <p class="text-[10px] font-bold text-slate-400 mb-2 border-b border-slate-200 pb-1">
                                                                                                {{ is_numeric($k) ? 'RECORD ' . ($k + 1) : strtoupper(str_replace('_', ' ', $k)) }}
                                                                                            </p>
                                                                                            <ul class="space-y-1.5 text-xs">
                                                                                                @foreach((array)$v as $subK => $subV)
                                                                                                    @if(!is_array($subV) && !is_object($subV))
                                                                                                        <li class="flex justify-between items-start gap-4">
                                                                                                            <span class="text-slate-500">{{ ucwords(str_replace('_', ' ', $subK)) }}:</span>
                                                                                                            <span class="font-semibold text-slate-700 text-right">{{ $subV === null ? '-' : $subV }}</span>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="flex justify-between items-start gap-4 text-xs border-b border-slate-50 pb-1 last:border-0">
                                                                                            <span class="text-slate-500">{{ ucwords(str_replace('_', ' ', $k)) }}:</span>
                                                                                            <span class="font-semibold text-slate-700 text-right">{{ $v === null ? '-' : $v }}</span>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            {{ $value === null ? '-' : (is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                            <!-- Data Sesudah -->
                                            <div class="bg-slate-50 rounded-xl border border-slate-200 overflow-hidden flex flex-col h-full">
                                                <div class="px-4 py-2 border-b border-slate-200 bg-slate-100/50 shrink-0">
                                                    <p class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">Data Sesudah</p>
                                                </div>
                                                <div class="p-4 bg-white overflow-x-auto flex-1">
                                                    @if(empty($log->data_sesudah))
                                                        <p class="text-slate-400 italic text-xs">Tidak ada data</p>
                                                    @else
                                                        <table class="w-full text-left text-xs">
                                                            <tbody class="divide-y divide-slate-100">
                                                            @foreach((array)$log->data_sesudah as $key => $value)
                                                                <tr>
                                                                    <td class="py-2 pr-4 font-semibold text-slate-500 uppercase tracking-wider w-1/3 align-top">{{ str_replace('_', ' ', $key) }}</td>
                                                                    <td class="py-2 font-medium text-slate-800 align-top break-words">
                                                                        @if(is_array($value) || is_object($value))
                                                                            <div class="space-y-2 mt-1 max-h-48 overflow-y-auto pr-1">
                                                                                @foreach((array)$value as $k => $v)
                                                                                    @if(is_array($v) || is_object($v))
                                                                                        <div class="bg-slate-50 border border-slate-200 rounded-lg p-3">
                                                                                            <p class="text-[10px] font-bold text-slate-400 mb-2 border-b border-slate-200 pb-1">
                                                                                                {{ is_numeric($k) ? 'RECORD ' . ($k + 1) : strtoupper(str_replace('_', ' ', $k)) }}
                                                                                            </p>
                                                                                            <ul class="space-y-1.5 text-xs">
                                                                                                @foreach((array)$v as $subK => $subV)
                                                                                                    @if(!is_array($subV) && !is_object($subV))
                                                                                                        <li class="flex justify-between items-start gap-4">
                                                                                                            <span class="text-slate-500">{{ ucwords(str_replace('_', ' ', $subK)) }}:</span>
                                                                                                            <span class="font-semibold text-slate-700 text-right">{{ $subV === null ? '-' : $subV }}</span>
                                                                                                        </li>
                                                                                                    @endif
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @else
                                                                                        <div class="flex justify-between items-start gap-4 text-xs border-b border-slate-50 pb-1 last:border-0">
                                                                                            <span class="text-slate-500">{{ ucwords(str_replace('_', ' ', $k)) }}:</span>
                                                                                            <span class="font-semibold text-slate-700 text-right">{{ $v === null ? '-' : $v }}</span>
                                                                                        </div>
                                                                                    @endif
                                                                                @endforeach
                                                                            </div>
                                                                        @else
                                                                            {{ $value === null ? '-' : (is_bool($value) ? ($value ? 'Ya' : 'Tidak') : $value) }}
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex justify-end shrink-0 rounded-b-2xl">
                                        <button @click="showModal = false" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-2.5 rounded-xl text-sm font-semibold transition-all">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-slate-500 font-sans">
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
            {{ $logs->onEachSide(1)->links() }}
        </div>
    @endif
</div>
@endsection
