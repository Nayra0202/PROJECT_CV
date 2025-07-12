<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barangs'; // pastikan sesuai dengan nama tabel di migration

    protected $primaryKey = 'id_barang'; // PK custom

    public $incrementing = false; // karena id_barang bukan auto increment

    protected $keyType = 'string'; // karena id_barang tipe string

    protected $fillable = [
        'id_barang',
        'nama_barang',
        'satuan',
        'gambar',
        'harga',
        'stok',
        'status',
        'keterangan',
        'tgl_input',
        'tgl_disetujui',
    ];

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'id_barang', 'id_barang');
    }
    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_barang', 'id_barang');
    }
    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'id_barang', 'id_barang');
    }
    public function detailBarangKeluar()
    {
        return $this->hasMany(DetailBarangKeluar::class, 'id_barang', 'id_barang');
    }
}