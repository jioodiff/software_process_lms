<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Tool;
use App\Models\ToolMutation;
use App\Models\ItemMutation;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $jenis_laporan = $request->input('jenis_laporan', 'rekap_peminjaman');
        $status = $request->input('status');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $is_export = $request->input('export') === 'csv';

        $data = [];
        $view_data = compact('jenis_laporan', 'status', 'start_date', 'end_date');

        switch ($jenis_laporan) {
            case 'rekap_peminjaman':
                $query = Borrowing::with(['mahasiswa', 'items.tool']);
                if ($start_date) $query->whereDate('tgl_pengajuan', '>=', $start_date);
                if ($end_date) $query->whereDate('tgl_pengajuan', '<=', $end_date);
                if ($status) $query->where('status', $status);
                
                if ($is_export) {
                    $borrowings = $query->latest('tgl_pengajuan')->get();
                    return $this->exportCsv('rekap_peminjaman', ['Tanggal Pengajuan', 'Mahasiswa', 'Alat Dipinjam', 'Status', 'Tanggal Kembali'], $borrowings->map(function ($b) {
                        return [
                            $b->tgl_pengajuan->format('Y-m-d'),
                            $b->mahasiswa->nama_lengkap ?? '-',
                            $b->items->map(function($i) { return $i->tool->nama_alat . ' (' . $i->jumlah_unit . ')'; })->implode(', '),
                            $b->status,
                            $b->tgl_rencana_kembali ? $b->tgl_rencana_kembali->format('Y-m-d') : '-'
                        ];
                    }));
                }
                $data = $query->latest('tgl_pengajuan')->paginate(10)->withQueryString();
                break;

            case 'alat_sering_dipinjam':
                $query = Tool::withCount(['borrowingItems as borrowing_count'])
                    ->withSum('borrowingItems as total_dipinjam', 'jumlah_unit')
                    ->orderByDesc('borrowing_count');
                
                if ($is_export) {
                    $tools = $query->get();
                    return $this->exportCsv('alat_sering_dipinjam', ['Kode Alat', 'Nama Alat', 'Kategori', 'Total Unit Dipinjam', 'Frekuensi Peminjaman'], $tools->map(function ($t) {
                        return [
                            $t->kode_alat,
                            $t->nama_alat,
                            $t->kategori,
                            $t->total_dipinjam ?? 0,
                            $t->borrowing_count
                        ];
                    }));
                }
                $data = $query->paginate(10)->withQueryString();
                break;

            case 'status_inventaris':
                $query = Item::query();
                if ($status) $query->where('kondisi', $status);
                
                if ($is_export) {
                    $items = $query->latest()->get();
                    return $this->exportCsv('status_inventaris_barang', ['Kode Barang', 'Nama Barang', 'Kategori', 'Lokasi', 'Kondisi', 'Stok'], $items->map(function ($i) {
                        return [
                            $i->kode_barang,
                            $i->nama_barang,
                            $i->kategori,
                            $i->lokasi,
                            $i->kondisi,
                            $i->stok
                        ];
                    }));
                }
                $data = $query->latest()->paginate(10)->withQueryString();
                break;

            case 'log_mutasi_stok':
                // Merge Tool and Item mutations manually
                $tQuery = ToolMutation::with(['tool', 'user']);
                $iQuery = ItemMutation::with(['item', 'user']);

                if ($start_date) {
                    $tQuery->whereDate('timestamp', '>=', $start_date);
                    $iQuery->whereDate('timestamp', '>=', $start_date);
                }
                if ($end_date) {
                    $tQuery->whereDate('timestamp', '<=', $end_date);
                    $iQuery->whereDate('timestamp', '<=', $end_date);
                }

                $tMutations = $tQuery->get()->map(function($m) {
                    return (object)[
                        'tanggal' => $m->timestamp,
                        'nama_barang' => $m->tool->nama_alat ?? 'Unknown',
                        'tipe_barang' => 'Alat',
                        'tipe_mutasi' => $m->tipe_mutasi,
                        'jumlah' => $m->jumlah,
                        'stok_sebelum' => $m->stok_sebelum,
                        'stok_sesudah' => $m->stok_sesudah,
                        'keterangan' => $m->keterangan,
                        'user' => $m->user->nama_lengkap ?? '-'
                    ];
                });

                $iMutations = $iQuery->get()->map(function($m) {
                    return (object)[
                        'tanggal' => $m->timestamp,
                        'nama_barang' => $m->item->nama_barang ?? 'Unknown',
                        'tipe_barang' => 'Barang',
                        'tipe_mutasi' => $m->tipe_mutasi,
                        'jumlah' => $m->jumlah,
                        'stok_sebelum' => $m->stok_sebelum,
                        'stok_sesudah' => $m->stok_sesudah,
                        'keterangan' => $m->keterangan,
                        'user' => $m->user->nama_lengkap ?? '-'
                    ];
                });

                $allMutations = $tMutations->merge($iMutations)->sortByDesc('tanggal')->values();

                if ($is_export) {
                    return $this->exportCsv('log_mutasi_stok', ['Tanggal', 'Barang/Alat', 'Tipe', 'Tipe Mutasi', 'Jumlah', 'Stok Awal', 'Stok Akhir', 'Keterangan', 'Oleh'], $allMutations->map(function ($m) {
                        return [
                            $m->tanggal->format('Y-m-d H:i:s'),
                            $m->nama_barang,
                            $m->tipe_barang,
                            $m->tipe_mutasi,
                            $m->jumlah,
                            $m->stok_sebelum,
                            $m->stok_sesudah,
                            $m->keterangan,
                            $m->user
                        ];
                    }));
                }

                // Simple pagination array
                $page = $request->input('page', 1);
                $perPage = 10;
                $data = new \Illuminate\Pagination\LengthAwarePaginator(
                    $allMutations->forPage($page, $perPage),
                    $allMutations->count(),
                    $perPage,
                    $page,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                break;

            case 'alat_sedang_dipinjam':
                $query = Borrowing::with(['mahasiswa', 'items.tool'])
                    ->where('status', 'Dipinjam');
                if ($start_date) $query->whereDate('tgl_pengajuan', '>=', $start_date);
                if ($end_date) $query->whereDate('tgl_pengajuan', '<=', $end_date);
                
                if ($is_export) {
                    $borrowings = $query->orderBy('tgl_rencana_kembali')->get();
                    return $this->exportCsv('alat_sedang_dipinjam', ['Tanggal Pengajuan', 'Mahasiswa', 'Alat Dipinjam', 'Rencana Kembali', 'Tenggat Waktu'], $borrowings->map(function ($b) {
                        return [
                            $b->tgl_pengajuan->format('Y-m-d'),
                            $b->mahasiswa->nama_lengkap ?? '-',
                            $b->items->map(function($i) { return $i->tool->nama_alat . ' (' . $i->jumlah_unit . ')'; })->implode(', '),
                            $b->tgl_rencana_kembali->format('Y-m-d'),
                            $b->tgl_rencana_kembali->isPast() ? 'Overdue' : 'Aman'
                        ];
                    }));
                }
                $data = $query->orderBy('tgl_rencana_kembali')->paginate(10)->withQueryString();
                break;

            case 'rekap_per_mahasiswa':
                $query = User::whereIn('role', ['mahasiswa', 'dosen'])->withCount('borrowings')->orderByDesc('borrowings_count');
                
                if ($is_export) {
                    $users = $query->get();
                    return $this->exportCsv('rekap_per_mahasiswa', ['NIM', 'Nama Mahasiswa', 'Program Studi', 'Total Peminjaman'], $users->map(function ($u) {
                        return [
                            $u->nim,
                            $u->nama_lengkap,
                            $u->program_studi ?? '-',
                            $u->borrowings_count
                        ];
                    }));
                }
                $data = $query->paginate(10)->withQueryString();
                break;

            case 'alat_rusak':
                $query = \App\Models\BorrowingItem::with(['borrowing.mahasiswa', 'tool'])
                    ->whereHas('borrowing', function ($q) {
                        $q->where('status', 'Dikembalikan');
                    })
                    ->where(function ($q) {
                        $q->where('kondisi_saat_kembali', '!=', 'Baik')
                          ->whereNotNull('kondisi_saat_kembali');
                    });
                
                if ($start_date) {
                    $query->whereHas('borrowing', function ($q) use ($start_date) {
                        $q->whereDate('updated_at', '>=', $start_date);
                    });
                }
                if ($end_date) {
                    $query->whereHas('borrowing', function ($q) use ($end_date) {
                        $q->whereDate('updated_at', '<=', $end_date);
                    });
                }

                if ($is_export) {
                    $items = $query->latest('id')->get();
                    return $this->exportCsv('laporan_alat_rusak', ['Tanggal Pengembalian', 'Mahasiswa', 'Alat', 'Jumlah Rusak', 'Detail Kerusakan', 'Catatan'], $items->map(function ($item) {
                        $rusakDetail = '';
                        $totalRusak = 0;
                        if ($item->kondisi_detail) {
                            $detail = json_decode($item->kondisi_detail, true);
                            $parts = [];
                            foreach (['Rusak Ringan', 'Rusak Berat', 'Hilang'] as $k) {
                                if (!empty($detail[$k]) && $detail[$k] > 0) {
                                    $parts[] = $k . ': ' . $detail[$k];
                                    $totalRusak += $detail[$k];
                                }
                            }
                            $rusakDetail = implode(', ', $parts);
                        } else {
                            $rusakDetail = $item->kondisi_saat_kembali;
                            if ($item->kondisi_saat_kembali === 'Sebagian Rusak/Hilang') {
                                $totalRusak = '-';
                            } else {
                                $totalRusak = $item->jumlah_unit;
                            }
                        }
                        
                        return [
                            $item->borrowing->updated_at->format('Y-m-d'),
                            $item->borrowing->mahasiswa->nama_lengkap ?? '-',
                            $item->tool->nama_alat ?? '-',
                            $totalRusak,
                            $rusakDetail,
                            $item->catatan_pengembalian ?? '-'
                        ];
                    }));
                }
                
                $data = $query->latest('id')->paginate(10)->withQueryString();
                break;
        }

        $view_data['data'] = $data;

        return view('admin.reports.index', $view_data);
    }

    private function exportCsv($filename, $headers, $rows)
    {
        $response = new StreamedResponse(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '_' . date('Ymd_His') . '.csv"');

        return $response;
    }
}
