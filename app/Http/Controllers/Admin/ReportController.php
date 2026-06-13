<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Tool;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function borrowings(Request $request)
    {
        $query = Borrowing::with(['mahasiswa', 'items.tool']);
        if ($request->filled('start_date')) $query->whereDate('tgl_pengajuan', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->whereDate('tgl_pengajuan', '<=', $request->end_date);
        if ($request->filled('status')) $query->where('status', $request->status);
        $borrowings = $query->latest('tgl_pengajuan')->paginate(15)->withQueryString();
        return view('admin.reports.borrowings', compact('borrowings'));
    }

    public function popularTools()
    {
        $tools = Tool::withCount(['borrowingItems as borrowing_count'])
            ->withSum('borrowingItems as total_dipinjam', 'jumlah_unit')
            ->orderByDesc('borrowing_count')
            ->paginate(15);
        return view('admin.reports.popular-tools', compact('tools'));
    }

    public function inventory()
    {
        $items = Item::latest()->paginate(15);
        return view('admin.reports.inventory', compact('items'));
    }

    public function activeBorrowings()
    {
        $borrowings = Borrowing::with(['mahasiswa', 'items.tool'])
            ->where('status', 'Dipinjam')
            ->orderBy('tgl_rencana_kembali')
            ->paginate(15);
        return view('admin.reports.active-borrowings', compact('borrowings'));
    }
}
