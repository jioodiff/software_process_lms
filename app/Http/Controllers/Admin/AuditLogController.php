<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::query();

        if ($request->filled('modul')) $query->where('modul', $request->modul);
        if ($request->filled('aksi')) $query->where('aksi', $request->aksi);
        if ($request->filled('start_date')) $query->whereDate('timestamp', '>=', $request->start_date);
        if ($request->filled('end_date')) $query->whereDate('timestamp', '<=', $request->end_date);

        $logs = $query->latest('timestamp')->paginate(20)->withQueryString();
        $moduls = AuditLog::distinct()->pluck('modul');
        $aksis = AuditLog::distinct()->pluck('aksi');

        return view('admin.audit-logs.index', compact('logs', 'moduls', 'aksis'));
    }

    public function show(AuditLog $auditLog)
    {
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
