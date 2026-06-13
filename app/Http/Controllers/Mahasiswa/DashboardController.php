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

        return view('mahasiswa.dashboard', compact('activeBorrowing', 'recentBorrowings', 'totalPeminjaman'));
    }
}
