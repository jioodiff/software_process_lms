<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $activeBorrowing = Borrowing::where('mahasiswa_id', $user->id)
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui', 'Dipinjam'])
            ->with('items.tool')
            ->first();

        $recentBorrowings = Borrowing::where('mahasiswa_id', $user->id)
            ->latest('tgl_pengajuan')
            ->take(5)
            ->get();

        $totalPeminjaman = Borrowing::where('mahasiswa_id', $user->id)->count();
        $sedangDipinjam = Borrowing::where('mahasiswa_id', $user->id)
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui', 'Dipinjam'])
            ->count();
        $selesai = Borrowing::where('mahasiswa_id', $user->id)
            ->where('status', 'Dikembalikan')
            ->count();
        $ditolak = Borrowing::where('mahasiswa_id', $user->id)
            ->where('status', 'Ditolak')
            ->count();

        $popularTools = \App\Models\Tool::tersedia()
            ->withCount('borrowingItems')
            ->orderByDesc('borrowing_items_count')
            ->take(3)
            ->get();

        return view('mahasiswa.dashboard', compact(
            'activeBorrowing', 
            'recentBorrowings', 
            'totalPeminjaman',
            'sedangDipinjam',
            'selesai',
            'ditolak',
            'popularTools'
        ));
    }
}
