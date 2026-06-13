@extends('layouts.app')

@section('title', 'Detail Mahasiswa')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2 text-slate-500 hover:text-slate-700 transition-colors">
        <i data-lucide="arrow-left" class="w-4 h-4"></i>
        <span class="text-sm font-semibold">Kembali ke Daftar</span>
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-1">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 text-center">
            <div class="w-24 h-24 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center text-3xl font-bold border-4 border-blue-100 mb-4">
                {{ substr($user->nama_lengkap, 0, 1) }}
            </div>
            <h2 class="text-xl font-bold text-slate-800">{{ $user->nama_lengkap }}</h2>
            <p class="text-slate-500 text-sm mt-1 mb-4">{{ $user->nim }}</p>

            <div class="inline-flex items-center justify-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                <span class="w-2 h-2 rounded-full {{ $user->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                {{ $user->is_active ? 'Akun Aktif' : 'Akun Nonaktif' }}
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 mb-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Informasi Mahasiswa</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Email</div>
                    <div class="text-slate-800 font-medium">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Program Studi</div>
                    <div class="text-slate-800 font-medium">{{ $user->program_studi ?? '-' }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal Daftar</div>
                    <div class="text-slate-800 font-medium">{{ $user->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div>
                    <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Jumlah Peminjaman</div>
                    <div class="text-slate-800 font-medium">{{ $user->borrowings_count }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4 pb-4 border-b border-slate-100">Aksi Akun</h3>
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between p-4 rounded-xl border gap-4 {{ $user->is_active ? 'border-red-100 bg-red-50/50' : 'border-green-100 bg-green-50/50' }}">
                <div>
                    <div class="font-semibold {{ $user->is_active ? 'text-red-700' : 'text-green-700' }}">{{ $user->is_active ? 'Nonaktifkan Akun' : 'Aktifkan Akun' }}</div>
                    <div class="text-sm mt-0.5 {{ $user->is_active ? 'text-red-600/80' : 'text-green-600/80' }}">{{ $user->is_active ? 'Mahasiswa tidak akan dapat login atau melakukan peminjaman.' : 'Mahasiswa akan dapat kembali login dan melakukan peminjaman.' }}</div>
                </div>
                
                <div x-data="{ open: false }" class="shrink-0">
                    <button type="button" @click="open = true" class="px-4 py-2 rounded-xl text-sm font-bold shadow-sm {{ $user->is_active ? 'bg-white text-red-600 border border-red-200 hover:bg-red-50' : 'bg-white text-green-600 border border-green-200 hover:bg-green-50' }} transition-colors">
                        {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                    </button>
                    
                    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm" @keydown.window.escape="open = false" style="display: none;">
                        <div @click.outside="open = false" class="bg-white rounded-2xl shadow-xl max-w-sm w-full p-6 mx-4 text-left">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full {{ $user->is_active ? ($user->borrowings()->where('status', 'Dipinjam')->exists() ? 'bg-amber-100 text-amber-600' : 'bg-red-100 text-red-600') : 'bg-green-100 text-green-600' }} flex items-center justify-center shrink-0">
                                    <i data-lucide="alert-circle" class="w-5 h-5"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-800">Konfirmasi Status</h3>
                            </div>
                            @if($user->is_active && $user->borrowings()->where('status', 'Dipinjam')->exists())
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
        </div>
    </div>
</div>
@endsection
