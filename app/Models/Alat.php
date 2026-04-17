<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alat extends Model
{
    protected $fillable = [
        'nama_alat', 
        'spesifikasi', 
        'stok', 
        'harga_sewa_per_hari', 
        'denda_per_hari',
        'kategori_id', 
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
