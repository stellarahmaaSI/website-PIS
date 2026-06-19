<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomOrder extends Model
{
    protected $table = 'custom_order';

    protected $primaryKey = 'id_custom'; // WAJIB

    protected $fillable = [
        'id_pelanggan',
        'jenis_batik',
        'jenis_kain',
        'teknik',
        'teks_tambahan',
        'status'
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan');
    }
}
