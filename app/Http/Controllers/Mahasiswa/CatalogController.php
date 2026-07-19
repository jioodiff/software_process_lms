<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Tool::tersedia();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama_alat', 'like', "%{$search}%")->orWhere('kode_alat', 'like', "%{$search}%"));
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $tools = $query->orderBy('nama_alat')->paginate(10)->withQueryString();
        $kategoris = Tool::tersedia()->distinct()->pluck('kategori')->filter();

        return view('mahasiswa.catalog.index', compact('tools', 'kategoris'));
    }
}
