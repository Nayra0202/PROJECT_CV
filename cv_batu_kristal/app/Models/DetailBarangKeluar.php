<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangKeluar extends Model
{
    protected $fillable = ['id_keluar','id_pemesanan', 'id_barang', 'jumlah','satuan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang','id_barang');
    }

    public function barangKeluar()
    {
        return $this->belongsTo(BarangKeluar::class, 'id_keluar', 'id_keluar');
    }
}
