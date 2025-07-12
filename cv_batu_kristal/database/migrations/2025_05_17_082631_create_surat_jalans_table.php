<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->string('id_surat_jalan', 10)->primary();
            $table->string('id_keluar', 10);
            $table->date('tanggal');

            // ✅ Kolom tambahan untuk kebutuhan cetak
            $table->string('nama_pemesan', 255);
            $table->string('alamat', 255);
            $table->string('nama_barang', 255);
            $table->integer('jumlah');
            $table->string('satuan', 255);

            $table->timestamps();

            $table->foreign('id_keluar')->references('id_keluar')->on('barang_keluars')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
