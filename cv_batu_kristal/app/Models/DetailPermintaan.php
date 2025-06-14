<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPermintaan extends Model
{
    protected $table = 'detail_permintaans';
    protected $primaryKey = 'id_detail_permintaan';
    protected $fillable = ['id_permintaan', 'id_barang', 'jumlah','total_harga','satuan'];

    // Relasi ke Permintaan
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'id_permintaan');
    }

    // Relasi ke Barang 
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }
}
