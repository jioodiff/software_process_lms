@extends('layouts.app')

@section('title', 'Katalog Alat')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
    <form action="{{ route('admin.tools.index') }}" method="GET" class="flex gap-3 w-full sm:w-auto">
        <div class="relative flex-1 sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari alat..." class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
        </div>
        <select name="kategori" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
            <option value="">Semua Kategori</option>
            @foreach($kategoris as $kat)
                <option value="{{ $kat }}" {{ request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
            @endforeach
        </select>
        <select name="status" class="border border-slate-200 rounded-xl text-sm py-2.5 px-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
            <option value="">Semua Status</option>
            <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
            <option value="Tidak Tersedia" {{ request('status') == 'Tidak Tersedia' ? 'selected' : '' }}>Tidak Tersedia</option>
            <option value="Rusak" {{ request('status') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
            <option value="Dalam Perbaikan" {{ request('status') == 'Dalam Perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
            <option value="Dinonaktifkan" {{ request('status') == 'Dinonaktifkan' ? 'selected' : '' }}>Dinonaktifkan</option>
        </select>
        <button type="submit" class="bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">
            Filter
        </button>
        @if(request()->anyFilled(['search', 'kategori', 'status']))
            <a href="{{ route('admin.tools.index') }}" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center px-2 transition-colors">Reset</a>
        @endif
    </form>
    
    <a href="{{ route('admin.tools.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-all flex items-center gap-2 shadow-sm hover:shadow-md shrink-0 w-full sm:w-auto justify-center">
        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Alat
    </a>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Kode</th>
                    <th class="px-6 py-4">Nama Alat</th>
                    <th class="px-6 py-4">Kategori</th>
                    <th class="px-6 py-4 text-center">Stok (Tersedia/Total)</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Lokasi</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($tools as $tool)
                    <tr class="hover:bg-slate-50/80 transition-colors group">
                        <td class="px-6 py-4 font-mono text-xs text-slate-500">{{ $tool->kode_alat }}</td>
                        <td class="px-6 py-4 flex items-center gap-3">
                            @if($tool->foto_alat)
                                <img src="{{ Storage::url($tool->foto_alat) }}" alt="Foto" class="h-10 w-10 object-cover rounded-xl border border-slate-200 shadow-sm">
                            @else
                                <div class="h-10 w-10 bg-slate-50 flex items-center justify-center rounded-xl border border-slate-100 shadow-sm">
                                    <i data-lucide="image" class="w-4 h-4 text-slate-300"></i>
                                </div>
                            @endif
                            <div class="font-semibold text-slate-800">{{ $tool->nama_alat }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600 border border-slate-200">{{ $tool->kategori }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-bold text-[13px] {{ $tool->stok_tersedia > 0 ? 'text-green-600 bg-green-50 px-2 py-0.5 rounded-md' : 'text-red-600 bg-red-50 px-2 py-0.5 rounded-md' }}">{{ $tool->stok_tersedia }}</span>
                            <span class="text-slate-400 font-medium ml-1">/ {{ $tool->stok_total }}</span>
                        </td>
                        <td class="px-6 py-4">
                            @if($tool->trashed())
                                <span class="inline-flex items-center rounded-full bg-slate-200 px-2.5 py-0.5 text-xs font-medium text-slate-700">Dinonaktifkan</span>
                            @elseif($tool->status_alat == 'Tersedia')
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Tersedia</span>
                            @elseif($tool->status_alat == 'Rusak')
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Rusak</span>
                            @elseif($tool->status_alat == 'Dalam Perbaikan')
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Perbaikan</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-600">Tidak Tersedia</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 font-medium">{{ $tool->lokasi ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                @if($tool->trashed())
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button type="button" @click="open = true" class="px-3 py-1.5 text-xs font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-xl transition-colors shadow-sm">
                                            Aktifkan
                                        </button>

                                        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" @keydown.window.escape="open = false" style="display: none;">
                                            <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 mx-4 text-left">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shrink-0">
                                                        <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                                                    </div>
                                                    <h3 class="text-lg font-bold text-slate-800">Konfirmasi Aktifkan</h3>
                                                </div>
                                                <p class="text-slate-500 mb-6 text-sm whitespace-normal">Yakin ingin mengaktifkan kembali alat ini agar tersedia di katalog?</p>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="open = false" class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-colors shadow-sm">Batal</button>
                                                    <form action="{{ route('admin.tools.restore', $tool->id) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 rounded-xl text-sm font-semibold transition-colors shadow-sm">Ya, Aktifkan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('admin.tools.mutasi', $tool) }}" class="p-2 text-green-600 hover:bg-green-50 rounded-xl transition-colors tooltip" title="Mutasi Stok">
                                        <i data-lucide="arrow-right-left" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.tools.edit', $tool) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors tooltip" title="Edit">
                                        <i data-lucide="edit-2" class="w-4 h-4"></i>
                                    </a>
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button type="button" @click="open = true" class="p-2 text-red-600 hover:bg-red-50 rounded-xl transition-colors tooltip" title="Hapus">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>

                                        <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" @keydown.window.escape="open = false" style="display: none;">
                                            <div class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 mx-4 text-left">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                                                        <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                                                    </div>
                                                    <h3 class="text-lg font-bold text-slate-800">Konfirmasi Hapus</h3>
                                                </div>
                                                <p class="text-slate-500 mb-6 text-sm whitespace-normal">Yakin ingin menonaktifkan alat ini? Alat yang dinonaktifkan tidak akan muncul lagi di katalog mahasiswa.</p>
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" @click="open = false" class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-colors shadow-sm">Batal</button>
                                                    <form action="{{ route('admin.tools.destroy', $tool) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded-xl text-sm font-semibold transition-colors shadow-sm">Ya, Nonaktifkan</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="inbox" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-700 mb-1">Tidak ada data alat</p>
                            <p class="text-sm">Belum ada alat yang ditambahkan atau sesuai dengan filter.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($tools->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $tools->links() }}
        </div>
    @endif
</div>
@endsection
