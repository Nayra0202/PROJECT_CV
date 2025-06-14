<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailBarangMasuk extends Model
{
    protected $table = 'detail_barang_masuks';
    protected $primaryKey = 'id_detail_masuk';
    protected $fillable = ['id_masuk', 'id_barang', 'jumlah','satuan'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    public function barangMasuk()
    {
        return $this->belongsTo(BarangMasuk::class, 'id_masuk', 'id_masuk');
    }
}
