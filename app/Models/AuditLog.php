<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    public $timestamps = false;
    protected $table = 'audit_logs';

    protected $fillable = [
        'timestamp', 'user_id', 'nama_user', 'role_pelaku',
        'modul', 'aksi', 'id_record', 'data_sebelum',
        'data_sesudah', 'ip_address',
    ];

    protected $casts = [
        'data_sebelum' => 'array',
        'data_sesudah' => 'array',
        'timestamp' => 'datetime',
    ];

    protected static function booted()
    {
        static::updating(function ($auditLog) {
            throw new \Exception('ORM Constraint: audit_logs table is Append Only (Read/Insert Only). UPDATE is strictly prohibited.');
        });

        static::deleting(function ($auditLog) {
            throw new \Exception('ORM Constraint: audit_logs table is Append Only (Read/Insert Only). DELETE is strictly prohibited.');
        });
    }

    /**
     * Helper untuk membuat audit log dari mana saja
     */
    public static function record(
        string $modul,
        string $aksi,
        ?string $idRecord = null,
        mixed $dataSebelum = null,
        mixed $dataSesudah = null
    ): self {
        $user = auth()->user();

        return self::create([
            'timestamp' => now(),
            'user_id' => $user?->id,
            'nama_user' => $user?->nama_lengkap ?? 'System',
            'role_pelaku' => $user ? ucfirst($user->role) : 'System',
            'modul' => $modul,
            'aksi' => $aksi,
            'id_record' => $idRecord,
            'data_sebelum' => $dataSebelum,
            'data_sesudah' => $dataSesudah,
            'ip_address' => request()->ip(),
        ]);
    }
}
