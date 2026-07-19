<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereIn('role', ['mahasiswa', 'dosen'])
            ->withExists(['borrowings as has_active_borrowing' => function ($q) {
                $q->where('status', 'Dipinjam');
            }]);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"));
        }
        
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status == '1');
        }

        if ($request->filled('prodi')) {
            $query->where('program_studi', $request->prodi);
        }

        $users = $query->withTrashed()->latest()->paginate(10)->withQueryString();
        $prodiList = User::whereNotNull('program_studi')->where('program_studi', '!=', '')->distinct()->orderBy('program_studi')->pluck('program_studi');

        return view('admin.users.index', compact('users', 'prodiList'));
    }

    public function show(User $user)
    {
        $user->loadCount('borrowings');
        return view('admin.users.show', compact('user'));
    }

    public function toggleActive(User $user)
    {
        if ($user->isAdmin()) {
            return back()->with('error', 'Tidak dapat mengubah status admin.');
        }

        $before = ['is_active' => $user->is_active];
        $user->update(['is_active' => !$user->is_active]);
        $after = ['is_active' => $user->is_active];

        AuditLog::record('User', 'UPDATE', (string) $user->id, $before, $after);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Akun {$user->nama_lengkap} berhasil {$status}.");
    }

}
