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
        'id_permintaan',
        'tanggal',
        'nama_pemesan',
        'alamat',
        'nama_barang',
        'jumlah',
        'satuan',
    ];

    // Relasi ke Permintaan
    public function permintaan(): BelongsTo
    {
        return $this->belongsTo(Permintaan::class, 'id_permintaan');
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
