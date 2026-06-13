<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\FiresN8nWebhook;

class BorrowingController extends Controller
{
    use FiresN8nWebhook;
    public function index()
    {
        $borrowings = Borrowing::where('mahasiswa_id', auth()->id())
            ->with('items.tool')
            ->latest('tgl_pengajuan')
            ->paginate(10);

        return view('mahasiswa.borrowings.index', compact('borrowings'));
    }

    public function show(Borrowing $borrowing)
    {
        if ($borrowing->mahasiswa_id !== auth()->id()) {
            abort(403);
        }

        $borrowing->load(['items.tool', 'admin']);
        return view('mahasiswa.borrowings.show', compact('borrowing'));
    }

    public function create()
    {
        return redirect()->route('mahasiswa.catalog.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.tool_id' => 'required|exists:tools,id',
            'items.*.jumlah_unit' => 'required|integer|min:1',
            'tgl_rencana_pinjam' => 'required|date|after_or_equal:today',
            'tgl_rencana_kembali' => 'required|date|after:tgl_rencana_pinjam',
            'keperluan' => 'required|string|max:500',
        ], [
            'items.required' => 'Pilih minimal 1 alat.',
            'items.min' => 'Pilih minimal 1 alat.',
            'tgl_rencana_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_rencana_kembali.after' => 'Tanggal kembali harus setelah tanggal pinjam.',
        ]);

        // Check active borrowing
        $hasActive = Borrowing::where('mahasiswa_id', auth()->id())
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui', 'Dipinjam'])
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'Anda masih memiliki pengajuan aktif.')->withInput();
        }

        try {
            $borrowing = DB::transaction(function () use ($request) {
                $borrowing = Borrowing::create([
                    'mahasiswa_id' => auth()->id(),
                    'tgl_pengajuan' => now(),
                    'tgl_rencana_pinjam' => $request->tgl_rencana_pinjam,
                    'tgl_rencana_kembali' => $request->tgl_rencana_kembali,
                    'keperluan' => $request->keperluan,
                    'status' => 'Menunggu Persetujuan',
                ]);

                foreach ($request->items as $itemData) {
                    $tool = Tool::lockForUpdate()->findOrFail($itemData['tool_id']);

                    if ($tool->stok_tersedia < $itemData['jumlah_unit']) {
                        throw new \Exception("Stok alat {$tool->nama_alat} tidak mencukupi atau baru saja dipinjam orang lain. Tersisa: {$tool->stok_tersedia}");
                    }

                    BorrowingItem::create([
                        'borrowing_id' => $borrowing->id,
                        'tool_id' => $tool->id,
                        'jumlah_unit' => $itemData['jumlah_unit'],
                    ]);

                    // Reserve stock
                    $tool->decrement('stok_tersedia', $itemData['jumlah_unit']);
                }

                AuditLog::record('Peminjaman', 'CREATE', (string) $borrowing->id, null, $borrowing->load('items')->toArray());
                return $borrowing;
            });

            $this->fireWebhook('borrowing.submitted', $borrowing);

            return redirect()->route('mahasiswa.borrowings.index')->with('success', 'Pengajuan peminjaman berhasil disubmit!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
