<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tool extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_alat', 'nama_alat', 'kategori', 'deskripsi',
        'stok_total', 'stok_tersedia', 'status_alat', 'lokasi', 'foto_alat',
    ];

    protected function casts(): array
    {
        return [
            'stok_total' => 'integer',
            'stok_tersedia' => 'integer',
        ];
    }

    public function borrowingItems()
    {
        return $this->hasMany(BorrowingItem::class);
    }

    public function mutations()
    {
        return $this->hasMany(ToolMutation::class);
    }

    public function scopeTersedia($query)
    {
        return $query->where('status_alat', 'Tersedia')->where('stok_tersedia', '>', 0);
    }
}
