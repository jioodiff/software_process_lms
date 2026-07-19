<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\BorrowingItem;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
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
            'tgl_rencana_kembali' => 'required|date|after_or_equal:tgl_rencana_pinjam',
            'keperluan' => 'required|string|max:500',
        ], [
            'items.required' => 'Pilih minimal 1 alat.',
            'items.min' => 'Pilih minimal 1 alat.',
            'tgl_rencana_pinjam.after_or_equal' => 'Tanggal pinjam minimal hari ini.',
            'tgl_rencana_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        // Check active borrowing
        $hasActive = Borrowing::where('mahasiswa_id', auth()->id())
            ->whereIn('status', ['Menunggu Persetujuan', 'Disetujui', 'Dipinjam'])
            ->exists();

        if ($hasActive) {
            return back()->with('error', 'Anda masih memiliki pengajuan aktif.')->withInput();
        }

        try {
            DB::transaction(function () use ($request) {
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

                    if ($tool->status_alat !== 'Tersedia') {
                        throw new \Exception("Alat {$tool->nama_alat} tidak tersedia untuk dipinjam.");
                    }

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
                
                // Fire webhook
                $webhookUrl = config('services.n8n.webhook_url');
                if ($webhookUrl) {
                    try {
                        $toolNames = $borrowing->items->pluck('tool.nama_alat')->implode(', ');
                        
                        $itemsDetailString = $borrowing->items->map(function ($item) {
                            return "- {$item->tool->nama_alat} ({$item->jumlah_unit} unit)";
                        })->implode("\n");

                        // Generate HTML Table for n8n emails
                        $itemsHtmlTable = '<table style="width: 100%; border-collapse: collapse; margin: 15px 0; border: 1px solid #e2e8f0; font-family: sans-serif;">';
                        $itemsHtmlTable .= '<thead><tr>';
                        $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: left; border: 1px solid #bfdbfe; font-size: 13px;">Nama Alat</th>';
                        $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: center; border: 1px solid #bfdbfe; font-size: 13px;">Unit</th>';
                        $itemsHtmlTable .= '</tr></thead><tbody>';
                        
                        foreach ($borrowing->items as $item) {
                            $itemsHtmlTable .= '<tr>';
                            $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px;">' . $item->tool->nama_alat . '</td>';
                            $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px; text-align: center;">' . $item->jumlah_unit . '</td>';
                            $itemsHtmlTable .= '</tr>';
                        }
                        $itemsHtmlTable .= '</tbody></table>';

                        $itemsData = $borrowing->items->map(function ($item) {
                            return [
                                'tool_name' => $item->tool->nama_alat,
                                'jumlah_unit' => $item->jumlah_unit,
                                'kondisi' => null,
                                'kondisi_detail' => null,
                            ];
                        })->toArray();

                        \Illuminate\Support\Facades\Http::timeout(5)->post($webhookUrl, [
                            'event' => 'borrowing.submitted',
                            'borrowing_id' => $borrowing->id,
                            'student_name' => auth()->user()->nama_lengkap,
                            'student_email' => auth()->user()->email,
                            'student_whatsapp' => auth()->user()->no_whatsapp,
                            'tool_name' => $toolNames,
                            'items_detail_string' => $itemsDetailString,
                            'items_html_table' => $itemsHtmlTable,
                            'items' => $itemsData,
                            'borrow_date' => $borrowing->tgl_rencana_pinjam->toDateString(),
                            'return_date' => $borrowing->tgl_rencana_kembali->toDateString(),
                            'admin_note' => null,
                            'timestamp' => now()->toISOString(),
                        ]);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning('N8N Webhook failed (submitted): ' . $e->getMessage());
                    }
                }
            });

            return redirect()->route('mahasiswa.borrowings.index')->with('success', 'Pengajuan peminjaman berhasil disubmit!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }
}
