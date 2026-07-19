<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['mahasiswa', 'items.tool']);

        if ($request->filled('status')) {
            if ($request->status === 'Terlambat') {
                $query->overdue();
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('overdue')) {
            $query->overdue();
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
            'catatan.*' => 'nullable|string|max:500',
        ]);

        $before = $borrowing->toArray();

        DB::transaction(function () use ($borrowing, $request) {
            $validationErrors = [];
            
            \Illuminate\Support\Facades\Log::info('Submit Pengembalian:', $request->all());

            // 1. Validation Loop
            foreach ($borrowing->items as $item) {
                $kondisiInput = $request->kondisi[$item->id];
                $totalReturned = 0;

                if (is_array($kondisiInput)) {
                    foreach (['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'] as $k) {
                        $qty = (int)($kondisiInput[$k] ?? 0);
                        if ($qty > 0) {
                            $totalReturned += $qty;
                        }
                    }
                } else {
                    $totalReturned = $item->jumlah_unit;
                }

                if ($totalReturned !== $item->jumlah_unit) {
                    $validationErrors["kondisi.{$item->id}"] = "Total unit pengembalian untuk alat {$item->tool->nama_alat} harus {$item->jumlah_unit}.";
                }
            }

            if (!empty($validationErrors)) {
                throw \Illuminate\Validation\ValidationException::withMessages($validationErrors);
            }

            // 2. Processing Loop
            foreach ($borrowing->items as $item) {
                $kondisiInput = $request->kondisi[$item->id];
                $catatan = $request->catatan[$item->id] ?? null;

                $kondisiDetail = [];
                $jmlBaik = 0;
                $jmlRusak = 0;

                if (is_array($kondisiInput)) {
                    foreach (['Baik', 'Rusak Ringan', 'Rusak Berat', 'Hilang'] as $k) {
                        $qty = (int)($kondisiInput[$k] ?? 0);
                        if ($qty > 0) {
                            $kondisiDetail[$k] = $qty;
                            if ($k === 'Baik') {
                                $jmlBaik += $qty;
                            } else {
                                $jmlRusak += $qty;
                            }
                        }
                    }
                } else {
                    $kondisiDetail[$kondisiInput] = $item->jumlah_unit;
                    if ($kondisiInput === 'Baik') {
                        $jmlBaik += $item->jumlah_unit;
                    } else {
                        $jmlRusak += $item->jumlah_unit;
                    }
                }

                if ($jmlBaik === $item->jumlah_unit) {
                    $mainKondisi = 'Baik';
                } elseif ($jmlRusak === $item->jumlah_unit) {
                    $mainKondisi = array_key_first($kondisiDetail);
                } else {
                    $mainKondisi = 'Sebagian Rusak/Hilang';
                }

                $item->update([
                    'kondisi_saat_kembali' => $mainKondisi,
                    'kondisi_detail' => json_encode($kondisiDetail),
                    'catatan_pengembalian' => $catatan,
                ]);

                if ($jmlBaik > 0) {
                    Tool::where('id', $item->tool_id)->increment('stok_tersedia', $jmlBaik);
                }
                if ($jmlRusak > 0) {
                    // Rusak/Hilang: kurangi stok_total, stok_tersedia tidak ditambah
                    Tool::where('id', $item->tool_id)->decrement('stok_total', $jmlRusak);
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

    private function fireWebhook(string $event, Borrowing $borrowing): void
    {
        $webhookUrl = config('services.n8n.webhook_url');
        if (!$webhookUrl) return;

        try {
            $borrowing->load(['mahasiswa', 'items.tool']);
            $toolNames = $borrowing->items->pluck('tool.nama_alat')->implode(', ');
            
            $itemsDetailString = $borrowing->items->map(function ($item) {
                $detail = "- {$item->tool->nama_alat} ({$item->jumlah_unit} unit)";
                if ($item->kondisi_saat_kembali) {
                    $detail .= "\n  Kondisi: {$item->kondisi_saat_kembali}";
                    if ($item->kondisi_detail) {
                        $kd = json_decode($item->kondisi_detail, true);
                        if (is_array($kd)) {
                            $kdStr = [];
                            foreach ($kd as $k => $v) {
                                $kdStr[] = "$v $k";
                            }
                            $detail .= " (" . implode(', ', $kdStr) . ")";
                        }
                    }
                }
                return $detail;
            })->implode("\n");

            // Generate HTML Table for n8n emails
            $isReturned = ($event === 'borrowing.returned');
            
            $itemsHtmlTable = '<table style="width: 100%; border-collapse: collapse; margin: 15px 0; border: 1px solid #e2e8f0; font-family: sans-serif;">';
            $itemsHtmlTable .= '<thead><tr>';
            $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: left; border: 1px solid #bfdbfe; font-size: 13px;">Nama Alat</th>';
            $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: center; border: 1px solid #bfdbfe; font-size: 13px;">Unit</th>';
            if ($isReturned) {
                $itemsHtmlTable .= '<th style="background-color: #eff6ff; color: #1e40af; padding: 10px; text-align: left; border: 1px solid #bfdbfe; font-size: 13px;">Kondisi</th>';
            }
            $itemsHtmlTable .= '</tr></thead><tbody>';
            
            foreach ($borrowing->items as $item) {
                $itemsHtmlTable .= '<tr>';
                $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px;">' . $item->tool->nama_alat . '</td>';
                $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px; text-align: center;">' . $item->jumlah_unit . '</td>';
                
                if ($isReturned) {
                    $kondisi = $item->kondisi_saat_kembali ?? '-';
                    if ($item->kondisi_detail) {
                        $kd = json_decode($item->kondisi_detail, true);
                        if (is_array($kd)) {
                            $kdStr = [];
                            foreach ($kd as $k => $v) {
                                $kdStr[] = "$v $k";
                            }
                            $kondisi .= "<br><small style='color:#64748b;'>(" . implode(', ', $kdStr) . ")</small>";
                        }
                    }
                    $itemsHtmlTable .= '<td style="padding: 10px; border: 1px solid #e2e8f0; font-size: 13px;">' . $kondisi . '</td>';
                }
                $itemsHtmlTable .= '</tr>';
            }
            $itemsHtmlTable .= '</tbody></table>';

            $itemsData = $borrowing->items->map(function ($item) {
                return [
                    'tool_name' => $item->tool->nama_alat,
                    'jumlah_unit' => $item->jumlah_unit,
                    'kondisi' => $item->kondisi_saat_kembali,
                    'kondisi_detail' => $item->kondisi_detail ? json_decode($item->kondisi_detail, true) : null,
                ];
            })->toArray();

            \Illuminate\Support\Facades\Http::timeout(5)->post($webhookUrl, [
                'event' => $event,
                'borrowing_id' => $borrowing->id,
                'student_name' => $borrowing->mahasiswa->nama_lengkap,
                'student_email' => $borrowing->mahasiswa->email,
                'student_whatsapp' => $borrowing->mahasiswa->no_whatsapp,
                'tool_name' => $toolNames,
                'items_detail_string' => $itemsDetailString,
                'items_html_table' => $itemsHtmlTable,
                'items' => $itemsData,
                'borrow_date' => $borrowing->tgl_rencana_pinjam->toDateString(),
                'return_date' => $borrowing->tgl_rencana_kembali->toDateString(),
                'admin_note' => $borrowing->catatan_admin,
                'timestamp' => now()->toISOString(),
            ]);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('N8N Webhook failed: ' . $e->getMessage());
        }
    }
}
