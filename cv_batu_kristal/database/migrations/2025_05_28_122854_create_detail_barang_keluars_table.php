<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('detail_barang_keluars', function (Blueprint $table) {
            $table->string('id_detail_keluar')->primary();
            $table->string('id_keluar');
            $table->string('id_barang');
            $table->integer('jumlah');
            $table->string('satuan');

            $table->foreign('id_keluar')->references('id_keluar')->on('barang_keluars')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_barang_keluars');
    }
};
