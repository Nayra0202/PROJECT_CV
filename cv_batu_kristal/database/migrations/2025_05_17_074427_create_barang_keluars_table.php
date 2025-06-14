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
        Schema::create('barang_keluars', function (Blueprint $table) {
            $table->string('id_keluar')->primary();
            $table->string('id_permintaan');
            $table->date('tgl_keluar');
            $table->timestamps();

            // Foreign key ke tabel permintaans (kolom id_permintaan)
            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
