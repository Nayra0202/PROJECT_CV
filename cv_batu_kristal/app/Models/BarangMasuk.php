<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BarangMasuk extends Model
{
    protected $table = 'barang_masuks'; // atau nama tabel sesuai migration
    protected $primaryKey = 'id_masuk'; // <-- INI PRIMARY KEY-NYA
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_masuk',
        'tgl_masuk',
    ];

    public function detailBarangMasuk()
    {
        return $this->hasMany(DetailBarangMasuk::class, 'id_masuk', 'id_masuk');
    }

    public function getRouteKeyName()
    {
        return 'id_masuk';
    }


}
