<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ToolMutation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tool_id', 'tipe_mutasi', 'jumlah',
        'stok_sebelum', 'stok_sesudah',
        'keterangan', 'dilakukan_oleh', 'timestamp',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'stok_sebelum' => 'integer',
        'stok_sesudah' => 'integer',
        'timestamp' => 'datetime',
    ];

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
