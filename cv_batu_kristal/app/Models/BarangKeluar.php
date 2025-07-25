<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Barang;
use App\Models\Pemesanan;
use App\Models\DetailBarangKeluar;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluars';
    protected $primaryKey = 'id_keluar';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_keluar',
        'id_pemesanan',
        'tgl_keluar',
    ];

    // Relasi ke tabel Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'id_barang');
    }

    // Relasi ke tabel Permintaan
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    public function detailBarangKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'id_keluar', 'id_keluar');
    }
}
