<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_barang', 'nama_barang', 'kategori', 'stok', 'kondisi', 'lokasi',
    ];

    protected function casts(): array
    {
        return ['stok' => 'integer'];
    }

    public function mutations()
    {
        return $this->hasMany(ItemMutation::class)->orderByDesc('timestamp');
    }
}
