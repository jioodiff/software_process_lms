<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Tool;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_alat' => Tool::count(),
            'alat_tersedia' => Tool::sum('stok_tersedia'),
            'peminjaman_menunggu' => Borrowing::where('status', 'Menunggu Persetujuan')->count(),
            'peminjaman_aktif' => Borrowing::whereIn('status', ['Disetujui', 'Dipinjam'])->count(),
            'total_mahasiswa' => User::whereIn('role', ['mahasiswa', 'dosen'])->count(),
            'overdue' => Borrowing::overdue()->count(),
            'total_barang_inventaris' => Item::count(),
        ];

        $recentBorrowings = Borrowing::with('mahasiswa')
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        $activeBorrowingsList = Borrowing::with('mahasiswa', 'items.tool')
            ->whereIn('status', ['Disetujui', 'Dipinjam'])
            ->orderBy('tgl_rencana_kembali', 'asc')
            ->take(5)
            ->get();

        $lowStockTools = Tool::where('stok_tersedia', '<=', 5)
            ->orderBy('stok_tersedia', 'asc')
            ->take(5)
            ->get();

        // Data for Chart
        $borrowingsPerMonth = Borrowing::selectRaw('MONTH(tgl_pengajuan) as month, COUNT(*) as count')
            ->whereYear('tgl_pengajuan', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $borrowingsPerMonth[$i] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'recentBorrowings', 'activeBorrowingsList', 'lowStockTools', 'chartData'));
    }
}
