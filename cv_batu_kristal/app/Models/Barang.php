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
        'harga',
        'stok',
        'satuan',
        'status',
        'keterangan',
        'tgl_input',
        'tgl_disetujui',
        'id_user',
    ];

    // Jika ingin relasi ke User:
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}