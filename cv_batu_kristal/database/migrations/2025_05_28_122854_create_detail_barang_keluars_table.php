<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_keluars', function (Blueprint $table) {
            $table->bigIncrements('id_detail_keluar');
            $table->string('id_keluar', 10);            // FK ke barang_keluars
            $table->string('id_pemesanan', 10);         // ✅ Tambahan FK ke pemesanans
            $table->string('id_barang', 10);            // FK ke barangs
            $table->integer('jumlah');
            $table->string('satuan', 255);
            $table->timestamps();

            $table->foreign('id_keluar')->references('id_keluar')->on('barang_keluars')->onDelete('cascade');
            $table->foreign('id_pemesanan')->references('id_pemesanan')->on('pemesanans')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_keluars');
    }
};
