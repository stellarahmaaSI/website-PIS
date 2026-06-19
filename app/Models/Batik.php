<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batik extends Model
{
    protected $table = 'batik';
    protected $primaryKey = 'id_batik';
    protected $fillable = ['nama_batik', 'gambar_batik', 'deskripsi', 'harga', 'stok'];
}
