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
            $table->string('id_barang')->primary(); 
            $table->string('nama_barang');
            $table->string('satuan');
            $table->integer('harga');
            $table->integer('stok')->default(0);
            $table->enum('status', ['Menunggu', 'Disetejui', 'Ditolak'])->default('Menunggu');
            $table->text('keterangan')->nullable(); // <-- Tambahkan ini
            $table->timestamp('tgl_input')->nullable();
            $table->timestamp('tgl_disetujui')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
