<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\FiresN8nWebhook;

class BorrowingController extends Controller
{
    use FiresN8nWebhook;
    public function index(Request $request)
    {
        $query = Borrowing::with(['mahasiswa', 'items.tool']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"));
        }

        $borrowings = $query->latest('tgl_pengajuan')->paginate(10)->withQueryString();

        return view('admin.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['mahasiswa', 'items.tool', 'admin']);
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Menunggu Persetujuan') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $before = $borrowing->toArray();

        $borrowing->update([
            'status' => 'Disetujui',
            'diproses_oleh' => auth()->id(),
        ]);

        AuditLog::record('Peminjaman', 'APPROVE', (string) $borrowing->id, $before, $borrowing->fresh()->toArray());

        // Fire-and-forget N8N webhook
        $this->fireWebhook('borrowing.approved', $borrowing);

        return back()->with('success', 'Pengajuan berhasil disetujui.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Menunggu Persetujuan') {
            return back()->with('error', 'Pengajuan sudah diproses.');
        }

        $request->validate(['catatan_admin' => 'required|string|max:500'], [
            'catatan_admin.required' => 'Catatan wajib diisi saat menolak pengajuan.',
        ]);

        $before = $borrowing->toArray();

        DB::transaction(function () use ($borrowing, $request) {
            $borrowing->update([
                'status' => 'Ditolak',
                'diproses_oleh' => auth()->id(),
                'catatan_admin' => $request->catatan_admin,
            ]);

            // Restore reserved stock
            foreach ($borrowing->items as $item) {
                Tool::where('id', $item->tool_id)->increment('stok_tersedia', $item->jumlah_unit);
            }
        });

        AuditLog::record('Peminjaman', 'REJECT', (string) $borrowing->id, $before, $borrowing->fresh()->toArray());

        $this->fireWebhook('borrowing.rejected', $borrowing);

        return back()->with('success', 'Pengajuan berhasil ditolak. Stok dikembalikan.');
    }

    public function returnForm(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Dipinjam') {
            return back()->with('error', 'Peminjaman belum dalam status dipinjam.');
        }

        $borrowing->load(['mahasiswa', 'items.tool']);
        return view('admin.borrowings.return', compact('borrowing'));
    }

    public function processReturn(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Dipinjam') {
            return back()->with('error', 'Peminjaman belum dalam status dipinjam.');
        }

        $request->validate([
            'kondisi' => 'required|array',
            'kondisi.*' => 'required|in:Baik,Rusak Ringan,Rusak Berat',
            'catatan.*' => 'nullable|string|max:500',
        ]);

        $before = $borrowing->toArray();

        DB::transaction(function () use ($borrowing, $request) {
            foreach ($borrowing->items as $item) {
                $kondisi = $request->kondisi[$item->id];
                $catatan = $request->catatan[$item->id] ?? null;

                $item->update([
                    'kondisi_saat_kembali' => $kondisi,
                    'catatan_pengembalian' => $catatan,
                ]);

                if ($kondisi === 'Baik') {
                    Tool::where('id', $item->tool_id)->increment('stok_tersedia', $item->jumlah_unit);
                } else {
                    // Rusak: kurangi stok_total, stok_tersedia tidak ditambah
                    Tool::where('id', $item->tool_id)->decrement('stok_total', $item->jumlah_unit);
                }
            }

            $borrowing->update(['status' => 'Dikembalikan']);
        });

        AuditLog::record('Peminjaman', 'RETURN', (string) $borrowing->id, $before, $borrowing->fresh()->load('items')->toArray());

        $this->fireWebhook('borrowing.returned', $borrowing);

        return redirect()->route('admin.borrowings.index')->with('success', 'Pengembalian berhasil dicatat.');
    }

    public function handover(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'Disetujui') {
            return back()->with('error', 'Status tidak valid untuk penyerahan.');
        }

        $before = $borrowing->toArray();
        $borrowing->update(['status' => 'Dipinjam']);

        AuditLog::record('Peminjaman', 'UPDATE', (string) $borrowing->id, $before, $borrowing->fresh()->toArray());

        $this->fireWebhook('borrowing.handed_over', $borrowing);

        return back()->with('success', 'Alat telah diserahkan ke mahasiswa.');
    }

}
