<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuratJalan extends Model
{
    protected $table = 'surat_jalans';

    protected $primaryKey = 'id_surat_jalan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id_surat_jalan',
        'id_keluar', // relasi ke barang keluar
        'tanggal',
        'nama_pemesan',
        'alamat',
    ];

    /**
     * Relasi ke BarangKeluar
     */
    public function barangKeluar(): BelongsTo
    {
        return $this->belongsTo(BarangKeluar::class, 'id_keluar', 'id_keluar');
    }

    /**
     * Relasi ke DetailSuratJalan jika kamu pakai tabel itu
     */

}
