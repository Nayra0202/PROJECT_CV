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
        Schema::create('permintaans', function (Blueprint $table) {
        $table->id('id_permintaan')->primary();
        $table->string('id_barang');
        $table->string('nama_pemesan');
        $table->text('alamat');
        $table->date('tgl_permintaan');
        $table->decimal('total_bayar', 15, 2)->nullable(); // total bayar bisa dihitung dari detail
        $table->enum('status', ['Sedang Proses', 'Sedang Perjalanan', 'Selesai'])->default('Sedang Proses');
        $table->timestamps();

        $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaans');
    }
};
