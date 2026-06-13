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
        $query = User::where('role', 'mahasiswa')
            ->withExists(['borrowings as has_active_borrowing' => function ($q) {
                $q->where('status', 'Dipinjam');
            }]);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")->orWhere('nim', 'like', "%{$search}%"));
        }
        $users = $query->withTrashed()->latest()->paginate(15)->withQueryString();
        return view('admin.users.index', compact('users'));
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
