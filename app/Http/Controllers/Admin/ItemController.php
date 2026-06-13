<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Item;
use App\Models\ItemMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query();
        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', "%{$request->search}%")
                  ->orWhere('kode_barang', 'like', "%{$request->search}%");
        }
        $items = $query->latest()->paginate(10)->withQueryString();
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        return view('admin.items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:items,kode_barang',
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'stok' => 'required|integer|min:0',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:50',
        ]);

        DB::transaction(function () use ($validated) {
            $item = Item::create($validated);

            if ($validated['stok'] > 0) {
                ItemMutation::create([
                    'item_id' => $item->id,
                    'tipe_mutasi' => 'Masuk',
                    'jumlah' => $validated['stok'],
                    'stok_sebelum' => 0,
                    'stok_sesudah' => $validated['stok'],
                    'keterangan' => 'Stok awal',
                    'dilakukan_oleh' => auth()->id(),
                    'timestamp' => now(),
                ]);
            }

            AuditLog::record('Inventaris', 'CREATE', (string) $item->id, null, $item->toArray());
        });

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('admin.items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:20|unique:items,kode_barang,' . $item->id,
            'nama_barang' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'kondisi' => 'nullable|string|max:50',
            'lokasi' => 'nullable|string|max:50',
        ]);

        $before = $item->toArray();
        $item->update($validated);
        AuditLog::record('Inventaris', 'UPDATE', (string) $item->id, $before, $item->fresh()->toArray());

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diupdate.');
    }

    public function destroy(Item $item)
    {
        $before = $item->toArray();
        $item->delete();
        AuditLog::record('Inventaris', 'DELETE', (string) $item->id, $before, ['deleted_at' => now()]);
        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil dinonaktifkan.');
    }

    public function showMutasi(Item $item)
    {
        $mutations = $item->mutations()->with('user')->paginate(15);
        return view('admin.items.mutasi', compact('item', 'mutations'));
    }

    public function storeMutasi(Request $request, Item $item)
    {
        $request->validate([
            'tipe_mutasi' => 'required|in:Masuk,Keluar,Penyesuaian',
            'jumlah' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $stokSebelum = $item->stok;

        if ($request->tipe_mutasi === 'Keluar' && $request->jumlah > $item->stok) {
            return back()->with('error', "Stok tidak mencukupi. Saat ini: {$item->stok} unit.")->withInput();
        }

        $stokSesudah = match ($request->tipe_mutasi) {
            'Masuk' => $stokSebelum + $request->jumlah,
            'Keluar' => $stokSebelum - $request->jumlah,
            'Penyesuaian' => $request->jumlah,
        };

        DB::transaction(function () use ($item, $request, $stokSebelum, $stokSesudah) {
            $item->update(['stok' => $stokSesudah]);

            ItemMutation::create([
                'item_id' => $item->id,
                'tipe_mutasi' => $request->tipe_mutasi,
                'jumlah' => $request->jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'keterangan' => $request->keterangan,
                'dilakukan_oleh' => auth()->id(),
                'timestamp' => now(),
            ]);

            AuditLog::record('Inventaris', 'MUTATE', (string) $item->id,
                ['stok' => $stokSebelum],
                ['stok' => $stokSesudah, 'tipe' => $request->tipe_mutasi, 'jumlah' => $request->jumlah]
            );
        });

        return back()->with('success', "Mutasi stok berhasil. Stok: {$stokSebelum} → {$stokSesudah}");
    }
}
