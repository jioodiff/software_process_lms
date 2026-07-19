<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function getOverdue()
    {
        // Ambil data peminjaman yang telat (status Dipinjam dan tgl_rencana_kembali lewat hari ini)
        $borrowings = Borrowing::with('items.tool', 'mahasiswa')
            ->where('status', 'Dipinjam')
            ->whereDate('tgl_rencana_kembali', '<', now()->toDateString())
            ->get();

        $data = $borrowings->map(function ($borrowing) {
            $toolNames = $borrowing->items->pluck('tool.nama_alat')->implode(', ');
            $itemsDetail = $borrowing->items->map(function ($item) {
                return "- {$item->tool->nama_alat} ({$item->jumlah_unit} unit)";
            })->implode("\n");

            return [
                'borrowing_id' => $borrowing->id,
                'student_name' => $borrowing->mahasiswa->nama_lengkap ?? 'Unknown',
                'student_email' => $borrowing->mahasiswa->email ?? '',
                'student_whatsapp' => $borrowing->mahasiswa->no_whatsapp ?? '',
                'tool_name' => $toolNames,
                'items_detail_string' => $itemsDetail,
                'borrow_date' => $borrowing->tgl_rencana_pinjam->toDateString(),
                'return_date' => $borrowing->tgl_rencana_kembali->toDateString(),
            ];
        });

        return response()->json([
            'success' => true,
            'count' => $data->count(),
            'data' => $data
        ]);
    }
}
