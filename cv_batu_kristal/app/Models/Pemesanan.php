<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Pemesanan extends Model
{
    protected $table = 'pemesanans'; // atau sesuaikan dengan nama tabel di migration

    protected $primaryKey = 'id_pemesanan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_pemesanan',
        'user_id',
        'nama_pemesan',
        'alamat',
        'tgl_pemesanan',
        'total_bayar',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke barang (jika ada model Barang)
    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'id_barang', 'id_barang');
    }

    // Relasi ke detail_pemesanan
    public function detailPemesanan()
    {
        return $this->hasMany(DetailPemesanan::class, 'id_pemesanan', 'id_pemesanan');
    }
    
    public function barangKeluar()
    {
        return $this->hasOne(BarangKeluar::class, 'id_pemesanan', 'id_pemesanan');
    }
}
