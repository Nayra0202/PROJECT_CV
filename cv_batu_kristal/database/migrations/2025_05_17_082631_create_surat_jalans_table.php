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
        Schema::create('surat_jalans', function (Blueprint $table) {
            $table->id('id_surat_jalan')->primary();
            $table->unsignedBigInteger('id_permintaan'); // foreign key
            $table->date('tanggal');
            $table->string('nama_pemesan');
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->timestamps();

            // Foreign key ke permintaans.id_permintaan (bukan id)
            $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalans');
    }
};
