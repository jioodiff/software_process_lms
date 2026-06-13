@extends('layouts.app')

@section('title', 'Manajemen Akun Mahasiswa')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
    <form action="{{ route('admin.users.index') }}" method="GET" class="flex gap-3 w-full sm:w-auto">
        <div class="relative flex-1 sm:w-72">
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400"></i>
            </div>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama lengkap atau NIM..." class="block w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-[0_2px_10px_rgb(0,0,0,0.02)] outline-none transition-all">
        </div>
        <button type="submit" class="bg-white border border-slate-200 text-slate-700 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors shadow-sm">Cari</button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.users.index') }}" class="text-sm font-medium text-red-500 hover:text-red-600 flex items-center px-2 transition-colors">Reset</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm whitespace-nowrap">
            <thead class="bg-slate-50/80 text-slate-500 font-semibold border-b border-slate-100 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-4">Mahasiswa</th>
                    <th class="px-6 py-4">Program Studi</th>
                    <th class="px-6 py-4">Status Akun</th>
                    <th class="px-6 py-4">Terdaftar</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="px-6 py-4 flex items-center gap-4">
                            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold border border-blue-100">
                                {{ substr($user->nama_lengkap, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-slate-800">{{ $user->nama_lengkap }}</div>
                                <div class="text-[11px] text-slate-500 font-mono mt-0.5">{{ $user->nim }} • {{ $user->email }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-700 font-medium">{{ $user->program_studi ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($user->is_active)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700"><span class="w-1.5 h-1.5 rounded-full bg-green-500 mr-1.5"></span> Aktif</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700"><span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5"></span> Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-slate-500 text-xs font-medium">{{ $user->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="inline-flex items-center justify-center px-4 py-2 bg-white border border-slate-200 text-slate-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 rounded-xl text-xs font-bold transition-all shadow-sm">
                                    Detail
                                </a>
                                <div x-data="{ open: false }" class="inline-block">
                                    <button type="button" @click="open = true" class="inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-bold transition-all shadow-sm {{ $user->is_active ? 'bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:shadow' : 'bg-white border border-green-200 text-green-600 hover:bg-green-50 hover:shadow' }}">
                                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>

                                    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" @keydown.window.escape="open = false" style="display: none;">
                                    <div @click.outside="open = false" class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 mx-4 text-left">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div class="w-10 h-10 rounded-full {{ $user->is_active ? ($user->has_active_borrowing ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') : 'bg-green-100 text-green-600' }} flex items-center justify-center shrink-0">
                                                <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-800">Konfirmasi Status</h3>
                                        </div>
                                        @if($user->is_active && $user->has_active_borrowing)
                                            <div class="bg-amber-50 border border-amber-200 text-amber-800 p-3 rounded-xl mb-4 text-sm font-medium">
                                                <div class="flex gap-2 items-start">
                                                    <i data-lucide="alert-triangle" class="w-5 h-5 shrink-0 mt-0.5 text-amber-600"></i>
                                                    <p class="whitespace-normal break-words leading-relaxed">Warning: Mahasiswa masih memiliki peminjaman aktif. Pastikan alat dikembalikan sebelum menonaktifkan.</p>
                                                </div>
                                            </div>
                                            <p class="text-slate-500 mb-6 text-sm whitespace-normal">Lanjutkan penonaktifan akun <strong>{{ $user->nama_lengkap }}</strong>?</p>
                                        @else
                                            <p class="text-slate-500 mb-6 text-sm whitespace-normal">Yakin ingin {{ $user->is_active ? 'menonaktifkan' : 'mengaktifkan' }} akun mahasiswa <strong>{{ $user->nama_lengkap }}</strong>?</p>
                                        @endif
                                        <div class="flex justify-end gap-3">
                                            <button type="button" @click="open = false" class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-xl text-sm font-semibold transition-colors shadow-sm">Batal</button>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 {{ $user->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">
                                                    Ya, {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <i data-lucide="users" class="w-8 h-8 text-slate-300"></i>
                            </div>
                            <p class="text-base font-semibold text-slate-700 mb-1">Tidak ada data mahasiswa</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
