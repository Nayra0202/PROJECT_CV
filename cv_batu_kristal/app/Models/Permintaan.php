<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Permintaan extends Model
{
    protected $table = 'permintaans'; // atau sesuaikan dengan nama tabel di migration

    protected $primaryKey = 'id_permintaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_permintaan',
        'nama_pemesan',
        'alamat',
        'tgl_permintaan',
        'total_bayar',
        'status',
    ];

    // Relasi ke barang (jika ada model Barang)
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Relasi ke detail_permintaan
    public function detailPermintaan()
    {
        return $this->hasMany(DetailPermintaan::class, 'id_permintaan', 'id_permintaan');
    }
}
