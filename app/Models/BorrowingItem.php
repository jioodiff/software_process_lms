<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowingItem extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'borrowing_id', 'tool_id', 'jumlah_unit',
        'kondisi_saat_kembali', 'kondisi_detail', 'catatan_pengembalian',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_unit' => 'integer',
        ];
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }

    public function tool()
    {
        return $this->belongsTo(Tool::class)->withTrashed();
    }
}
