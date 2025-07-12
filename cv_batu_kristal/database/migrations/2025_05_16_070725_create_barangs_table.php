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
        Schema::create('barangs', function (Blueprint $table) {
            $table->string('id_barang', 10)->primary();
            $table->string('nama_barang', 255);
            $table->string('satuan', 255);
            $table->string('gambar')->nullable();
            $table->integer('harga');
            $table->integer('stok')->default(0);
            $table->string('status', 20)->default('Menunggu');
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('tgl_input')->nullable();
            $table->timestamp('tgl_disetujui')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            // Foreign key ke tabel users
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
