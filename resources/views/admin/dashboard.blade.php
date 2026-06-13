@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
    <!-- Stat Card: Total Alat -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50/50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="h-14 w-14 shrink-0 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center relative z-10 border border-blue-100/50">
            <i data-lucide="wrench" class="w-6 h-6"></i>
        </div>
        <div class="ml-5 relative z-10 flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Alat</p>
            <h3 class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($stats['total_alat']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Alat Tersedia -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50/50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="h-14 w-14 shrink-0 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center relative z-10 border border-green-100/50">
            <i data-lucide="check-circle" class="w-6 h-6"></i>
        </div>
        <div class="ml-5 relative z-10 flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">Stok Tersedia</p>
            <h3 class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($stats['alat_tersedia']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Peminjaman Menunggu -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-50/50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="h-14 w-14 shrink-0 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center relative z-10 border border-amber-100/50">
            <i data-lucide="clock" class="w-6 h-6"></i>
        </div>
        <div class="ml-5 relative z-10 flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">Menunggu Approve</p>
            <h3 class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($stats['peminjaman_menunggu']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Peminjaman Overdue -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-red-50/50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="h-14 w-14 shrink-0 rounded-2xl bg-red-50 text-red-600 flex items-center justify-center relative z-10 border border-red-100/50">
            <i data-lucide="alert-triangle" class="w-6 h-6"></i>
        </div>
        <div class="ml-5 relative z-10 flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">Terlambat Kembali</p>
            <h3 class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($stats['overdue']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Total Mahasiswa -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex items-center hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300 relative overflow-hidden group">
        <div class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50/50 rounded-full group-hover:scale-150 transition-transform duration-500 z-0"></div>
        <div class="h-14 w-14 shrink-0 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center relative z-10 border border-indigo-100/50">
            <i data-lucide="users" class="w-6 h-6"></i>
        </div>
        <div class="ml-5 relative z-10 flex-1">
            <p class="text-sm font-medium text-slate-500 mb-1">Total Mahasiswa</p>
            <div class="flex items-end justify-between gap-2">
                <h3 class="text-3xl font-bold text-slate-900 leading-none">{{ number_format($stats['total_mahasiswa']) }}</h3>
                <a href="{{ route('admin.users.index') }}" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 transition-colors px-2 py-1 rounded-md flex items-center gap-1 shrink-0">
                    Kelola <i data-lucide="arrow-right" class="w-3 h-3"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Chart -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 p-6 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-lg">
                <i data-lucide="bar-chart-2" class="w-5 h-5 text-blue-500"></i>
                Statistik Peminjaman {{ date('Y') }}
            </h3>
        </div>
        <div class="h-64 flex-1">
            <canvas id="borrowingChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions & Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-lg">Quick Actions</h3>
            </div>
            <div class="p-6 space-y-3">
                <a href="{{ route('admin.tools.create') }}" class="flex items-center gap-4 w-full px-4 py-3 bg-white border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all group">
                    <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="plus" class="w-5 h-5"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">Tambah Alat Baru</span>
                </a>
                <a href="{{ route('admin.items.create') }}" class="flex items-center gap-4 w-full px-4 py-3 bg-white border border-slate-200 rounded-xl hover:border-blue-300 hover:shadow-sm transition-all group">
                    <div class="h-10 w-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i data-lucide="box" class="w-5 h-5"></i>
                    </div>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-blue-700 transition-colors">Tambah Inventaris</span>
                </a>
            </div>
        </div>
        

    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Pengajuan Terbaru -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-base">
                <i data-lucide="activity" class="w-4 h-4 text-blue-500"></i>
                Pengajuan Terbaru
            </h3>
            <a href="{{ route('admin.borrowings.index') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="divide-y divide-slate-50 flex-1">
            @forelse($recentBorrowings as $borrowing)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 shrink-0 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-xs font-bold border border-slate-200">
                            {{ substr($borrowing->mahasiswa->nama_lengkap, 0, 1) }}
                        </div>
                        <div class="min-w-0 max-w-[120px] lg:max-w-[100px] xl:max-w-[140px]">
                            <p class="text-xs font-semibold text-slate-900 truncate" title="{{ $borrowing->mahasiswa->nama_lengkap }}">{{ $borrowing->mahasiswa->nama_lengkap }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Tgl: {{ $borrowing->tgl_rencana_pinjam->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0 ml-2">
                        @if($borrowing->status == 'Menunggu Persetujuan')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[9px] font-medium text-amber-700">Menunggu</span>
                        @elseif($borrowing->status == 'Disetujui')
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[9px] font-medium text-blue-700">Disetujui</span>
                        @elseif($borrowing->status == 'Dipinjam')
                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-[9px] font-medium text-purple-700">Dipinjam</span>
                        @elseif($borrowing->status == 'Dikembalikan')
                            <span class="inline-flex items-center rounded-full bg-green-100 px-2 py-0.5 text-[9px] font-medium text-green-700">Selesai</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-medium text-red-700">Ditolak</span>
                        @endif
                        <a href="{{ route('admin.borrowings.show', $borrowing) }}" class="text-[10px] text-blue-600 hover:text-blue-700 transition-colors font-medium">Detail</a>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-slate-500 h-full flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                        <i data-lucide="inbox" class="w-6 h-6 text-slate-300"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-600">Belum ada pengajuan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Peminjaman Aktif -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-base">
                <i data-lucide="refresh-cw" class="w-4 h-4 text-blue-500"></i>
                Peminjaman Aktif
            </h3>
            <a href="{{ route('admin.borrowings.index', ['status' => 'Dipinjam']) }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lihat Semua &rarr;</a>
        </div>
        <div class="divide-y divide-slate-50 flex-1">
            @forelse($activeBorrowingsList as $active)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50/80 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 shrink-0 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 text-xs font-bold border border-blue-100">
                            {{ substr($active->mahasiswa->nama_lengkap, 0, 1) }}
                        </div>
                        <div class="min-w-0 max-w-[120px] lg:max-w-[100px] xl:max-w-[140px]">
                            <p class="text-xs font-semibold text-slate-900 truncate" title="{{ $active->mahasiswa->nama_lengkap }}">{{ $active->mahasiswa->nama_lengkap }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">Tenggat: <span class="font-medium text-slate-700">{{ $active->tgl_rencana_kembali->format('d/m/Y') }}</span></p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 shrink-0 ml-2">
                        @if($active->isOverdue())
                            <span class="inline-flex items-center rounded-full bg-red-100 px-2 py-0.5 text-[9px] font-bold text-red-700">Terlambat</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-[9px] font-bold text-purple-700">Berjalan</span>
                        @endif
                        <a href="{{ route('admin.borrowings.show', $active) }}" class="text-[10px] text-blue-600 hover:text-blue-700 transition-colors font-medium">Detail</a>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-slate-500 h-full flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                        <i data-lucide="check" class="w-6 h-6 text-slate-300"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-600">Tidak ada peminjaman aktif</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Barang Stok Rendah -->
    <div class="bg-white rounded-2xl shadow-[0_2px_10px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden flex flex-col">
        <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
            <h3 class="font-bold text-slate-800 flex items-center gap-2 text-base">
                <i data-lucide="alert-circle" class="w-4 h-4 text-red-500"></i>
                Peringatan Stok Rendah
            </h3>
            <a href="{{ route('admin.tools.index') }}" class="text-[11px] font-semibold text-blue-600 hover:text-blue-700 transition-colors">Lihat Katalog &rarr;</a>
        </div>
        <div class="divide-y divide-slate-50 flex-1">
            @forelse($lowStockTools as $tool)
                <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-50/80 transition-colors group">
                    <div class="flex items-center gap-3">
                        @if($tool->foto_alat)
                            <img src="{{ Storage::url($tool->foto_alat) }}" alt="Foto" class="h-8 w-8 shrink-0 object-cover rounded-lg border border-slate-200 shadow-sm">
                        @else
                            <div class="h-8 w-8 shrink-0 bg-slate-50 flex items-center justify-center rounded-lg border border-slate-100 shadow-sm">
                                <i data-lucide="cpu" class="w-4 h-4 text-slate-400"></i>
                            </div>
                        @endif
                        <div class="min-w-0 max-w-[140px] lg:max-w-[120px] xl:max-w-[160px]">
                            <p class="text-xs font-semibold text-slate-900 group-hover:text-blue-600 transition-colors truncate" title="{{ $tool->nama_alat }}">{{ $tool->nama_alat }}</p>
                            <p class="text-[10px] text-slate-500 font-mono mt-0.5 truncate">{{ $tool->kode_alat }}</p>
                        </div>
                    </div>
                    <div class="flex items-center shrink-0 ml-2">
                        <span class="font-bold text-[10px] {{ $tool->stok_tersedia == 0 ? 'text-red-600 bg-red-50 border-red-100' : 'text-amber-600 bg-amber-50 border-amber-100' }} px-2 py-0.5 rounded border">
                            {{ $tool->stok_tersedia }} sisa
                        </span>
                    </div>
                </div>
            @empty
                <div class="px-5 py-8 text-center text-slate-500 h-full flex flex-col items-center justify-center">
                    <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-3 border border-slate-100">
                        <i data-lucide="thumbs-up" class="w-6 h-6 text-slate-300"></i>
                    </div>
                    <p class="text-xs font-medium text-slate-600">Stok semua alat aman</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('borrowingChart').getContext('2d');
        const chartData = @json($chartData);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: chartData,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563EB',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, family: "'Inter', sans-serif" },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Peminjaman';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, precision: 0, font: { family: "'Inter', sans-serif", size: 11 } },
                        grid: { color: '#f1f5f9', drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { family: "'Inter', sans-serif", size: 11 } }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endsection
