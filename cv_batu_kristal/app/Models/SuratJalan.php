<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratJalan extends Model
{
    protected $table = 'surat_jalans'; // pastikan sesuai dengan nama tabel di migration

    protected $primaryKey = 'id_surat_jalan'; // sesuaikan dengan migration jika PK bukan 'id'
    protected $keyType = 'string'; 

    protected $fillable = [
        'id_surat_jalan',
        'id_pemesanan',
        'tanggal',
        'nama_pemesan',
        'alamat',
        'nama_barang',
        'jumlah',
        'satuan',
    ];

    // Relasi ke pemesanan
    public function pemesanan(): BelongsTo
    {
        return $this->belongsTo(Pemesanan::class, 'id_pemesanan');
    }

    public function detailBarang()
    {
        return $this->hasMany(DetailSuratJalan::class, 'id_surat_jalan');
    }

    public function detailSuratJalan()
    {
        return $this->hasMany(DetailSuratJalan::class, 'id_surat_jalan');
    }
}
