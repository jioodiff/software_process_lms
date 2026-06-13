<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemMutation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'item_id', 'tipe_mutasi', 'jumlah', 'stok_sebelum',
        'stok_sesudah', 'keterangan', 'dilakukan_oleh', 'timestamp',
    ];

    protected function casts(): array
    {
        return [
            'timestamp' => 'datetime',
            'jumlah' => 'integer',
            'stok_sebelum' => 'integer',
            'stok_sesudah' => 'integer',
        ];
    }

    public function item()
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
