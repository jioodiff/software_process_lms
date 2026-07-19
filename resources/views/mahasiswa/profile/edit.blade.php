@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('mahasiswa.dashboard') }}" class="p-2 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 transition-colors">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <h2 class="text-xl font-bold text-slate-800">Edit Profil</h2>
    </div>

    <form action="{{ route('mahasiswa.profile.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="nama_lengkap" class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" name="nama_lengkap" id="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
            @error('nama_lengkap') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="nim" class="block text-sm font-semibold text-slate-700 mb-2">NIM / Username <span class="text-rose-500">*</span></label>
            <input type="text" name="nim" id="nim" value="{{ old('nim', auth()->user()->nim) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
            @error('nim') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="no_whatsapp" class="block text-sm font-semibold text-slate-700 mb-2">Nomor WhatsApp <span class="text-rose-500">*</span></label>
            <input type="text" name="no_whatsapp" id="no_whatsapp" value="{{ old('no_whatsapp', auth()->user()->no_whatsapp) }}" required class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm" placeholder="Contoh: 08123456789">
            @error('no_whatsapp') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
        </div>

        <div class="pt-4 border-t border-slate-100">
            <h3 class="text-sm font-medium text-slate-800 mb-4">Ganti Password <span class="text-slate-400 font-normal">(Opsional)</span></h3>
            
            <div class="space-y-4">
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru</label>
                    <input type="password" name="password" id="password" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm" placeholder="Kosongkan jika tidak ingin mengganti">
                    @error('password') <p class="mt-1 text-xs text-rose-500">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" class="w-full rounded-xl border border-slate-300 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white shadow-sm">
                </div>
            </div>
        </div>

        <div class="pt-6 flex justify-end gap-3">
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700 shadow-sm transition-colors flex items-center gap-2">
                <i data-lucide="save" class="w-4 h-4"></i> Simpan Profil
            </button>
        </div>
    </form>
</div>
@endsection
