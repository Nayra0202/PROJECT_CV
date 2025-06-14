<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSuratJalan extends Model
{
    protected $fillable = ['id_surat_jalan', 'nama_barang', 'jumlah', 'satuan'];

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class, 'id_surat_jalan');
    }
}
