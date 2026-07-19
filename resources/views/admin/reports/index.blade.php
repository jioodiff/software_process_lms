@extends('layouts.app')

@section('title', 'Pusat Laporan')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ringkasan Laporan</h1>
            <p class="text-slate-500 text-sm mt-1">Cetak dan pantau aktivitas laboratorium secara terpusat.</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col lg:flex-row gap-4 justify-between items-end">
            <div class="flex flex-wrap gap-4 items-end w-full lg:w-auto">
                <div class="w-full sm:w-56">
                    <label for="jenis_laporan" class="block text-sm font-medium text-slate-700 mb-1">Jenis Laporan</label>
                    <select name="jenis_laporan" id="jenis_laporan" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" onchange="document.getElementById('status').value=''; this.form.submit()">
                        <option value="rekap_peminjaman" {{ $jenis_laporan == 'rekap_peminjaman' ? 'selected' : '' }}>Rekap Peminjaman</option>
                        <option value="alat_sering_dipinjam" {{ $jenis_laporan == 'alat_sering_dipinjam' ? 'selected' : '' }}>Alat Sering Dipinjam</option>
                        <option value="status_inventaris" {{ $jenis_laporan == 'status_inventaris' ? 'selected' : '' }}>Status Inventaris Barang</option>
                        <option value="log_mutasi_stok" {{ $jenis_laporan == 'log_mutasi_stok' ? 'selected' : '' }}>Log Mutasi Stok</option>
                        <option value="alat_sedang_dipinjam" {{ $jenis_laporan == 'alat_sedang_dipinjam' ? 'selected' : '' }}>Alat Sedang Dipinjam</option>
                        <option value="rekap_per_mahasiswa" {{ $jenis_laporan == 'rekap_per_mahasiswa' ? 'selected' : '' }}>Rekap per Mahasiswa</option>
                        <option value="alat_rusak" {{ $jenis_laporan == 'alat_rusak' ? 'selected' : '' }}>Alat Rusak / Hilang</option>
                    </select>
                </div>
                
                <div id="status_filter_wrapper" class="w-full sm:w-48 {{ in_array($jenis_laporan, ['rekap_peminjaman', 'status_inventaris']) ? '' : 'hidden' }}">
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status / Kondisi</label>
                    <select name="status" id="status" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" onchange="this.form.submit()">
                        <option value="">Semua</option>
                        @if($jenis_laporan == 'rekap_peminjaman')
                            <option value="Menunggu Persetujuan" {{ $status == 'Menunggu Persetujuan' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                            <option value="Disetujui" {{ $status == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
                            <option value="Dipinjam" {{ $status == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="Dikembalikan" {{ $status == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                            <option value="Ditolak" {{ $status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                        @elseif($jenis_laporan == 'status_inventaris')
                            <option value="Baik" {{ $status == 'Baik' ? 'selected' : '' }}>Baik</option>
                            <option value="Rusak" {{ $status == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            <option value="Hilang" {{ $status == 'Hilang' ? 'selected' : '' }}>Hilang</option>
                        @endif
                    </select>
                </div>

                <div class="w-full sm:w-40 {{ in_array($jenis_laporan, ['rekap_peminjaman', 'log_mutasi_stok', 'alat_sedang_dipinjam', 'alat_rusak']) ? '' : 'hidden' }}">
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" id="start_date" value="{{ $start_date }}" max="{{ $end_date }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" onchange="validateDatesAndSubmit(this)">
                </div>

                <div class="w-full sm:w-40 {{ in_array($jenis_laporan, ['rekap_peminjaman', 'log_mutasi_stok', 'alat_sedang_dipinjam', 'alat_rusak']) ? '' : 'hidden' }}">
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" id="end_date" value="{{ $end_date }}" min="{{ $start_date }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500" onchange="validateDatesAndSubmit(this)">
                </div>
            </div>

            <div class="flex gap-2 justify-end shrink-0 w-full lg:w-auto mt-2 lg:mt-0">
                @if(in_array($jenis_laporan, ['rekap_peminjaman', 'status_inventaris', 'log_mutasi_stok', 'alat_sedang_dipinjam', 'alat_rusak']))
                    <a href="{{ route('admin.reports.index', ['jenis_laporan' => $jenis_laporan]) }}" class="whitespace-nowrap justify-center flex items-center px-4 py-2 text-sm bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-100 transition-colors tooltip" title="Reset Filter">
                        <i data-lucide="rotate-ccw" class="w-4 h-4 mr-1.5"></i> Reset
                    </a>
                @endif
                <button type="submit" name="export" value="csv" class="whitespace-nowrap justify-center flex items-center px-4 py-2 text-sm bg-emerald-600 text-white font-medium rounded-xl hover:bg-emerald-700 transition-colors tooltip" title="Export to CSV">
                    <i data-lucide="download" class="w-4 h-4 mr-1.5"></i> Export CSV
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-sm text-slate-600">
                        <th class="px-6 py-4 font-medium w-16">No</th>
                        @if($jenis_laporan == 'rekap_peminjaman')
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-medium">Mahasiswa</th>
                            <th class="px-6 py-4 font-medium">Item Dipinjam</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Tanggal Kembali</th>
                        @elseif($jenis_laporan == 'alat_sering_dipinjam')
                            <th class="px-6 py-4 font-medium">Kode Alat</th>
                            <th class="px-6 py-4 font-medium">Nama Alat</th>
                            <th class="px-6 py-4 font-medium">Kategori</th>
                            <th class="px-6 py-4 font-medium text-center">Total Unit Dipinjam</th>
                            <th class="px-6 py-4 font-medium text-center">Frekuensi Peminjaman</th>
                        @elseif($jenis_laporan == 'status_inventaris')
                            <th class="px-6 py-4 font-medium">Kode Barang</th>
                            <th class="px-6 py-4 font-medium">Nama Barang</th>
                            <th class="px-6 py-4 font-medium">Kategori</th>
                            <th class="px-6 py-4 font-medium">Lokasi</th>
                            <th class="px-6 py-4 font-medium">Kondisi</th>
                            <th class="px-6 py-4 font-medium text-center">Stok</th>
                        @elseif($jenis_laporan == 'log_mutasi_stok')
                            <th class="px-6 py-4 font-medium">Tanggal</th>
                            <th class="px-6 py-4 font-medium">Barang/Alat</th>
                            <th class="px-6 py-4 font-medium">Tipe</th>
                            <th class="px-6 py-4 font-medium">Tipe Mutasi</th>
                            <th class="px-6 py-4 font-medium text-center">Jml</th>
                            <th class="px-6 py-4 font-medium text-center">Stok Awal</th>
                            <th class="px-6 py-4 font-medium text-center">Stok Akhir</th>
                            <th class="px-6 py-4 font-medium">Keterangan</th>
                            <th class="px-6 py-4 font-medium">Oleh</th>
                        @elseif($jenis_laporan == 'alat_sedang_dipinjam')
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Tanggal Pengajuan</th>
                            <th class="px-6 py-4 font-medium">Mahasiswa</th>
                            <th class="px-6 py-4 font-medium">Item Dipinjam</th>
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Rencana Kembali</th>
                            <th class="px-6 py-4 font-medium">Status Waktu</th>
                        @elseif($jenis_laporan == 'rekap_per_mahasiswa')
                            <th class="px-6 py-4 font-medium">NIM</th>
                            <th class="px-6 py-4 font-medium">Nama Mahasiswa</th>
                            <th class="px-6 py-4 font-medium">Program Studi</th>
                            <th class="px-6 py-4 font-medium text-center">Total Peminjaman</th>
                        @elseif($jenis_laporan == 'alat_rusak')
                            <th class="px-6 py-4 font-medium whitespace-nowrap">Tanggal Kembali</th>
                            <th class="px-6 py-4 font-medium">Mahasiswa</th>
                            <th class="px-6 py-4 font-medium">Nama Alat</th>
                            <th class="px-6 py-4 font-medium text-center">Jumlah Rusak</th>
                            <th class="px-6 py-4 font-medium">Detail Kerusakan</th>
                            <th class="px-6 py-4 font-medium">Catatan</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($data as $index => $row)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-500">{{ $data->firstItem() + $index }}</td>
                            
                            @if($jenis_laporan == 'rekap_peminjaman')
                                <td class="px-6 py-4 whitespace-nowrap">{{ $row->tgl_pengajuan->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->mahasiswa->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-500 max-w-sm">
                                    {{ $row->items->map(function($i) { return $i->tool->nama_alat . ' (' . $i->jumlah_unit . ')'; })->implode(', ') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $row->status === 'Dikembalikan' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $row->status === 'Dipinjam' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $row->status === 'Menunggu Persetujuan' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $row->status === 'Disetujui' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $row->status === 'Ditolak' ? 'bg-rose-100 text-rose-700' : '' }}
                                    ">
                                        {{ $row->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $row->tgl_rencana_kembali ? $row->tgl_rencana_kembali->format('d M Y') : '-' }}</td>
                                
                            @elseif($jenis_laporan == 'alat_sering_dipinjam')
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->kode_alat }}</td>
                                <td class="px-6 py-4">{{ $row->nama_alat }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ $row->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">{{ $row->total_dipinjam ?? 0 }}</td>
                                <td class="px-6 py-4 text-center">{{ $row->borrowing_count }}</td>

                            @elseif($jenis_laporan == 'status_inventaris')
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->kode_barang }}</td>
                                <td class="px-6 py-4">{{ $row->nama_barang }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-600">
                                        {{ $row->kategori }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">{{ $row->lokasi }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $row->kondisi === 'Baik' ? 'bg-emerald-100 text-emerald-700' : '' }}
                                        {{ $row->kondisi === 'Rusak' ? 'bg-rose-100 text-rose-700' : '' }}
                                        {{ $row->kondisi === 'Hilang' ? 'bg-slate-100 text-slate-700' : '' }}
                                    ">
                                        {{ $row->kondisi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-medium">{{ $row->stok }}</td>

                            @elseif($jenis_laporan == 'log_mutasi_stok')
                                <td class="px-6 py-4">{{ $row->tanggal->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->nama_barang }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $row->tipe_barang == 'Alat' ? 'bg-indigo-100 text-indigo-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $row->tipe_barang }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ in_array($row->tipe_mutasi, ['Masuk', 'Pengembalian']) ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}
                                    ">
                                        {{ $row->tipe_mutasi }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center {{ in_array($row->tipe_mutasi, ['Masuk', 'Pengembalian']) ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ in_array($row->tipe_mutasi, ['Masuk', 'Pengembalian']) ? '+' : '-' }}{{ $row->jumlah }}
                                </td>
                                <td class="px-6 py-4 text-center text-slate-500">{{ $row->stok_sebelum }}</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $row->stok_sesudah }}</td>
                                <td class="px-6 py-4 text-slate-500 max-w-xs truncate" title="{{ $row->keterangan }}">{{ $row->keterangan ?: '-' }}</td>
                                <td class="px-6 py-4">{{ $row->user }}</td>

                            @elseif($jenis_laporan == 'alat_sedang_dipinjam')
                                <td class="px-6 py-4 whitespace-nowrap">{{ $row->tgl_pengajuan->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->mahasiswa->nama_lengkap ?? '-' }}</td>
                                <td class="px-6 py-4 text-slate-500 max-w-sm">
                                    {{ $row->items->map(function($i) { return $i->tool->nama_alat . ' (' . $i->jumlah_unit . ')'; })->implode(', ') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ $row->tgl_rencana_kembali->format('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    @if($row->tgl_rencana_kembali->isPast())
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-700">
                                            Overdue
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700">
                                            Aman
                                        </span>
                                    @endif
                                </td>

                            @elseif($jenis_laporan == 'rekap_per_mahasiswa')
                                <td class="px-6 py-4">{{ $row->nim }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->nama_lengkap }}</td>
                                <td class="px-6 py-4 text-slate-500">{{ $row->program_studi ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-medium">{{ $row->borrowings_count }}</td>

                            @elseif($jenis_laporan == 'alat_rusak')
                                <td class="px-6 py-4 whitespace-nowrap">{{ $row->borrowing->updated_at->format('d M Y') }}</td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->borrowing->mahasiswa->nama_lengkap ?? '-' }}
                                    <div class="text-xs text-slate-500 mt-0.5">{{ $row->borrowing->mahasiswa->nim ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 font-medium text-slate-800">{{ $row->tool->nama_alat ?? '-' }}</td>
                                @php 
                                    $detail = $row->kondisi_detail ? json_decode($row->kondisi_detail, true) : []; 
                                    $hasDamage = false;
                                    $totalRusak = 0;
                                    foreach(['Rusak Ringan', 'Rusak Berat', 'Hilang'] as $k) {
                                        if(!empty($detail[$k]) && $detail[$k] > 0) {
                                            $totalRusak += $detail[$k];
                                            $hasDamage = true;
                                        }
                                    }
                                    if (!$hasDamage && $row->kondisi_saat_kembali !== 'Baik') {
                                        if ($row->kondisi_saat_kembali === 'Sebagian Rusak/Hilang') {
                                            $totalRusak = '-';
                                        } else {
                                            $totalRusak = $row->jumlah_unit;
                                        }
                                    }
                                @endphp
                                <td class="px-6 py-4 text-center font-medium text-rose-600">{{ $totalRusak }}</td>
                                <td class="px-6 py-4">
                                    @if($row->kondisi_detail && $hasDamage)
                                        <div class="flex flex-col gap-1 items-start">
                                            @foreach(['Rusak Ringan', 'Rusak Berat', 'Hilang'] as $k)
                                                @if(!empty($detail[$k]) && $detail[$k] > 0)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-700">
                                                        {{ $k }}: {{ $detail[$k] }} unit
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-rose-100 text-rose-700">
                                            {{ $row->kondisi_saat_kembali }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-sm max-w-xs">{{ $row->catatan_pengembalian ?? '-' }}</td>

                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-8 text-center text-slate-500">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mb-3"></i>
                                    <p class="text-base font-medium text-slate-600">Tidak ada data untuk laporan ini</p>
                                    <p class="text-sm">Coba sesuaikan filter atau rentang tanggal pencarian Anda.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>


<script>
function validateDatesAndSubmit(inputElement) {
    let startDate = document.getElementById('start_date');
    let endDate = document.getElementById('end_date');
    
    if (startDate && endDate && startDate.value && endDate.value) {
        if (startDate.value > endDate.value) {
            alert("Tanggal mulai tidak boleh lebih besar dari tanggal selesai!");
            // Reset the changed input to avoid an invalid state
            if (inputElement.id === 'start_date') {
                startDate.value = endDate.value;
            } else {
                endDate.value = startDate.value;
            }
        }
    }
    inputElement.form.submit();
}
</script>
@endsection
