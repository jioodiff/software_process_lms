<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
        'mahasiswa_id', 'tgl_pengajuan', 'tgl_rencana_pinjam', 'tgl_rencana_kembali',
        'keperluan', 'status', 'diproses_oleh', 'catatan_admin',
    ];

    protected function casts(): array
    {
        return [
            'tgl_pengajuan' => 'datetime',
            'tgl_rencana_pinjam' => 'date',
            'tgl_rencana_kembali' => 'date',
        ];
    }

    public function mahasiswa()
    {
        return $this->belongsTo(User::class, 'mahasiswa_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    public function items()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function isOverdue(): bool
    {
        return $this->status === 'Dipinjam' && $this->tgl_rencana_kembali->isPast();
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'Dipinjam')
                     ->where('tgl_rencana_kembali', '<', now()->toDateString());
    }
}
