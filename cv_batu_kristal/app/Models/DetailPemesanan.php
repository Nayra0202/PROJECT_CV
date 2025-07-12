<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPemesanan extends Model
{
    protected $table = 'detail_pemesanans';
    protected $primaryKey = 'id_detail_pemesanan';
    protected $fillable = ['id_pemesanan', 'id_barang', 'jumlah','total_harga','satuan'];

    // Relasi ke Pemesanan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    // Relasi ke Barang 
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
