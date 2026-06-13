<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Tool;
use App\Models\ToolMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ToolController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::withTrashed();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_alat', 'like', "%{$search}%")
                  ->orWhere('kode_alat', 'like', "%{$search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('status')) {
            if ($request->status === 'Dinonaktifkan') {
                $query->onlyTrashed();
            } else {
                $query->where('status_alat', $request->status)->whereNull('deleted_at');
            }
        }

        $tools = $query->latest()->paginate(10)->withQueryString();
        $kategoris = Tool::distinct()->pluck('kategori')->filter();

        return view('admin.tools.index', compact('tools', 'kategoris'));
    }

    public function create()
    {
        return view('admin.tools.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_alat' => 'required|string|max:20|unique:tools,kode_alat',
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'stok_total' => 'required|integer|min:0',
            'lokasi' => 'nullable|string|max:50',
            'foto_alat' => 'nullable|image|max:2048',
        ]);

        $validated['stok_tersedia'] = $validated['stok_total'];
        $validated['status_alat'] = 'Tersedia';

        if ($request->hasFile('foto_alat')) {
            $validated['foto_alat'] = $request->file('foto_alat')->store('tools', 'public');
        }

        $tool = Tool::create($validated);

        AuditLog::record('Alat', 'CREATE', (string) $tool->id, null, $tool->toArray());

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil ditambahkan.');
    }

    public function edit(Tool $tool)
    {
        return view('admin.tools.edit', compact('tool'));
    }

    public function update(Request $request, Tool $tool)
    {
        $validated = $request->validate([
            'kode_alat' => 'required|string|max:20|unique:tools,kode_alat,' . $tool->id,
            'nama_alat' => 'required|string|max:100',
            'kategori' => 'required|string|max:50',
            'deskripsi' => 'nullable|string',
            'status_alat' => 'required|in:Tersedia,Tidak Tersedia,Rusak,Dalam Perbaikan',
            'lokasi' => 'nullable|string|max:50',
            'foto_alat' => 'nullable|image|max:2048',
        ]);

        $before = $tool->toArray();

        if ($request->hasFile('foto_alat')) {
            if ($tool->foto_alat) {
                Storage::disk('public')->delete($tool->foto_alat);
            }
            $validated['foto_alat'] = $request->file('foto_alat')->store('tools', 'public');
        }

        $tool->update($validated);

        AuditLog::record('Alat', 'UPDATE', (string) $tool->id, $before, $tool->fresh()->toArray());

        return redirect()->route('admin.tools.index')->with('success', 'Data alat berhasil diupdate.');
    }

    public function destroy(Tool $tool)
    {
        // Check if tool is currently borrowed
        $activeBorrowings = $tool->borrowingItems()
            ->whereHas('borrowing', fn($q) => $q->whereIn('status', ['Disetujui', 'Dipinjam']))
            ->exists();

        if ($activeBorrowings) {
            return back()->with('error', 'Alat sedang dipinjam, tidak bisa dinonaktifkan.');
        }

        $before = $tool->toArray();
        $tool->delete(); // soft delete

        AuditLog::record('Alat', 'DELETE', (string) $tool->id, $before, ['deleted_at' => now()]);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil dinonaktifkan.');
    }

    public function showMutasi(Tool $tool)
    {
        $mutations = $tool->mutations()->with('user')->paginate(15);
        return view('admin.tools.mutasi', compact('tool', 'mutations'));
    }

    public function storeMutasi(Request $request, Tool $tool)
    {
        $request->validate([
            'tipe_mutasi' => 'required|in:Masuk,Keluar,Penyesuaian',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $stokSebelum = $tool->stok_total;

        if ($request->tipe_mutasi === 'Keluar' && $request->jumlah > $tool->stok_tersedia) {
            return back()->with('error', "Stok tersedia tidak mencukupi. Saat ini: {$tool->stok_tersedia} unit.")->withInput();
        }

        $stokSesudah = match ($request->tipe_mutasi) {
            'Masuk' => $stokSebelum + $request->jumlah,
            'Keluar' => $stokSebelum - $request->jumlah,
            'Penyesuaian' => $request->jumlah,
        };

        // For adjustments, we need to calculate the change to appropriately modify available stock.
        $difference = $stokSesudah - $stokSebelum;
        $stokTersediaBaru = $tool->stok_tersedia + $difference;

        if ($stokTersediaBaru < 0) {
            return back()->with('error', "Penyesuaian tidak valid. Stok tersedia akan menjadi negatif.")->withInput();
        }

        DB::transaction(function () use ($tool, $request, $stokSebelum, $stokSesudah, $stokTersediaBaru) {
            $tool->update([
                'stok_total' => $stokSesudah,
                'stok_tersedia' => $stokTersediaBaru,
            ]);

            ToolMutation::create([
                'tool_id' => $tool->id,
                'tipe_mutasi' => $request->tipe_mutasi,
                'jumlah' => $request->jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $request->keterangan,
                'dilakukan_oleh' => auth()->id(),
                'timestamp' => now(),
            ]);

            AuditLog::record('Alat', 'MUTATE', (string) $tool->id,
                ['stok_total' => $stokSebelum],
                ['stok_total' => $stokSesudah, 'tipe' => $request->tipe_mutasi, 'jumlah' => $request->jumlah]
            );
        });

        return back()->with('success', "Mutasi stok berhasil. Stok Total: {$stokSebelum} → {$stokSesudah}");
    }

    public function restore($id)
    {
        $tool = Tool::withTrashed()->findOrFail($id);
        $tool->restore();

        AuditLog::record('Alat', 'RESTORE', (string) $tool->id, ['deleted_at' => $tool->deleted_at], ['deleted_at' => null]);

        return redirect()->route('admin.tools.index')->with('success', 'Alat berhasil diaktifkan kembali.');
    }
}
