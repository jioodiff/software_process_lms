@extends('layouts.app')

@section('title', 'Form Pengembalian Alat')

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="p-2.5 bg-white border border-slate-200 rounded-xl text-slate-500 hover:text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
        </a>
        <div>
            <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Pencatatan Pengembalian</h2>
            <div class="flex items-center gap-2 text-sm text-slate-500 mt-1">
                <span class="font-mono font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">#B-{{ str_pad($borrowing->id, 4, '0', STR_PAD_LEFT) }}</span>
                <span>•</span>
                <span>Pengecekan kondisi alat yang dikembalikan</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.borrowings.processReturn', $borrowing) }}" method="POST">
        @csrf
        
        @if($errors->any())
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-4 rounded-xl shadow-sm flex items-start gap-3">
                <div class="p-1 bg-rose-100 rounded-full shrink-0">
                    <i data-lucide="alert-circle" class="w-5 h-5 text-rose-600"></i>
                </div>
                <div>
                    <span class="font-bold text-sm block mb-1">Gagal memproses pengembalian:</span>
                    <ul class="list-disc list-inside text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
            <!-- Left Column: Form Items -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-3xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden flex flex-col h-full">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">Cek Fisik & Kondisi Alat</h3>
                        </div>
                        <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">{{ $borrowing->items->count() }} Alat</span>
                    </div>
                    
                    <div class="divide-y divide-slate-100 bg-white flex-1">
                        @foreach($borrowing->items as $item)
                        <div class="p-6 grid grid-cols-1 xl:grid-cols-12 gap-6 items-start hover:bg-slate-50/50 transition-colors group">
                            <div class="xl:col-span-5">
                                <div class="flex items-start gap-4">
                                    @if($item->tool->foto_alat)
                                        <img src="{{ Storage::url($item->tool->foto_alat) }}" alt="{{ $item->tool->nama_alat }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200 shadow-sm group-hover:scale-105 transition-transform shrink-0">
                                    @else
                                        <div class="w-16 h-16 rounded-xl bg-slate-50 flex items-center justify-center border border-slate-200 text-slate-300 shadow-sm group-hover:bg-slate-100 transition-colors shrink-0">
                                            <i data-lucide="image" class="w-6 h-6"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-xs font-mono font-bold text-indigo-500 mb-1">{{ $item->tool->kode_alat }}</p>
                                        <p class="font-bold text-slate-800 leading-tight mb-2 line-clamp-2">{{ $item->tool->nama_alat }}</p>
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 px-2.5 py-1 text-[11px] font-bold text-slate-600 border border-slate-200">
                                            <i data-lucide="hash" class="w-3 h-3 mr-1 text-slate-400"></i> {{ $item->jumlah_unit }} Unit Dipinjam
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="xl:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wider">Kondisi Saat Kembali <span class="text-rose-500">*</span></label>
                                        <div class="grid grid-cols-2 gap-3 mb-2">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 mb-1">Baik (Otomatis)</label>
                                                <input type="number" id="baik-{{ $item->id }}" name="kondisi[{{ $item->id }}][Baik]" value="{{ $item->jumlah_unit }}" readonly class="w-full rounded-lg border border-slate-200 bg-slate-100 text-slate-500 font-semibold px-3 py-1.5 text-sm shadow-sm cursor-not-allowed focus:outline-none">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-slate-500 mb-1">Rusak Ringan</label>
                                                <input type="number" name="kondisi[{{ $item->id }}][Rusak Ringan]" value="0" min="0" max="{{ $item->jumlah_unit }}" data-item-id="{{ $item->id }}" data-max="{{ $item->jumlah_unit }}" class="bad-input w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-slate-500 mb-1">Rusak Berat</label>
                                                <input type="number" name="kondisi[{{ $item->id }}][Rusak Berat]" value="0" min="0" max="{{ $item->jumlah_unit }}" data-item-id="{{ $item->id }}" data-max="{{ $item->jumlah_unit }}" class="bad-input w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] text-slate-500 mb-1">Hilang</label>
                                                <input type="number" name="kondisi[{{ $item->id }}][Hilang]" value="0" min="0" max="{{ $item->jumlah_unit }}" data-item-id="{{ $item->id }}" data-max="{{ $item->jumlah_unit }}" class="bad-input w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm shadow-sm focus:ring-2 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-rose-500 mt-1 hidden" id="error-{{ $item->id }}">Total harus {{ $item->jumlah_unit }} unit.</p>
                                    <p class="text-[10px] font-medium text-slate-500 mt-2 flex items-start gap-1">
                                        <i data-lucide="info" class="w-3 h-3 shrink-0 mt-0.5"></i>
                                        Jika rusak/hilang, stok tidak kembali.
                                    </p>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-2 uppercase tracking-wider">Catatan (Opsional)</label>
                                    <textarea name="catatan[{{ $item->id }}]" rows="2" placeholder="Tambahkan catatan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 focus:bg-white transition-all shadow-sm resize-none"></textarea>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="px-6 py-5 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-end gap-3">
                        <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-700 font-bold bg-white hover:bg-slate-50 transition-colors shadow-sm text-center">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-[#155DFC] text-white font-bold hover:bg-[#114bca] shadow-sm transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 group">
                            <i data-lucide="check-circle" class="w-5 h-5 group-hover:scale-110 transition-transform"></i> Konfirmasi Pengembalian
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Column: Summary -->
            <div class="space-y-6 lg:sticky lg:top-6">
                <!-- Info Peminjam Card -->
                <div class="bg-white rounded-3xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-2">
                        <i data-lucide="user" class="w-4 h-4 text-slate-400"></i>
                        <h3 class="font-bold text-slate-800">Informasi Peminjaman</h3>
                    </div>
                    <div class="p-6 space-y-5">
                        <!-- User Info -->
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-600 border border-blue-100 flex items-center justify-center font-bold text-lg shrink-0">
                                {{ substr($borrowing->mahasiswa->nama_lengkap, 0, 1) }}
                            </div>
                            <div class="overflow-hidden">
                                <p class="text-sm font-bold text-slate-900 truncate">{{ $borrowing->mahasiswa->nama_lengkap }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $borrowing->mahasiswa->nim ?? 'Mahasiswa' }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-200 shadow-sm shrink-0">
                                    <i data-lucide="mail" class="w-3.5 h-3.5 text-slate-500"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Email</p>
                                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $borrowing->mahasiswa->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-200 shadow-sm shrink-0">
                                    <i data-lucide="phone" class="w-3.5 h-3.5 text-slate-500"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">WhatsApp</p>
                                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $borrowing->mahasiswa->no_whatsapp ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center border border-slate-200 shadow-sm shrink-0">
                                    <i data-lucide="graduation-cap" class="w-3.5 h-3.5 text-slate-500"></i>
                                </div>
                                <div class="overflow-hidden">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Program Studi</p>
                                    <p class="text-xs font-semibold text-slate-700 truncate">{{ $borrowing->mahasiswa->program_studi ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <hr class="border-dashed border-slate-200">

                        <!-- Dates -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Ambil</p>
                                <p class="text-sm font-semibold text-slate-800 flex items-center gap-2">
                                    <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i>
                                    {{ $borrowing->tgl_rencana_pinjam->format('d M Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tenggat Kembali</p>
                                <p class="text-sm font-semibold {{ $borrowing->isOverdue() ? 'text-rose-600' : 'text-slate-800' }} flex items-center gap-2">
                                    <i data-lucide="calendar-clock" class="w-4 h-4 {{ $borrowing->isOverdue() ? 'text-rose-500' : 'text-slate-400' }}"></i>
                                    {{ $borrowing->tgl_rencana_kembali->format('d M Y') }}
                                </p>
                            </div>
                            
                            @if($borrowing->isOverdue())
                                <div class="bg-rose-50 border border-rose-200 rounded-xl p-3 flex items-start gap-2 text-rose-700">
                                    <i data-lucide="alert-circle" class="w-4 h-4 shrink-0 mt-0.5"></i>
                                    <div class="text-xs font-medium">
                                        <p class="font-bold">Terlambat Mengembalikan!</p>
                                        <p class="mt-0.5 opacity-90">Melewati batas waktu yang ditentukan.</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.querySelectorAll('.bad-input').forEach(input => {
    input.addEventListener('input', function() {
        const itemId = this.getAttribute('data-item-id');
        const max = parseInt(this.getAttribute('data-max'));
        
        const inputs = document.querySelectorAll(`.bad-input[data-item-id="${itemId}"]`);
        let sumBad = 0;
        
        inputs.forEach(inp => {
            let val = parseInt(inp.value) || 0;
            if (val < 0) { val = 0; inp.value = 0; }
            sumBad += val;
        });
        
        if (sumBad > max) {
            const excess = sumBad - max;
            let currentVal = parseInt(this.value) || 0;
            this.value = currentVal - excess;
            sumBad = max;
        }
        
        const baikInput = document.getElementById(`baik-${itemId}`);
        baikInput.value = max - sumBad;
    });
});
</script>
@endpush
@endsection
