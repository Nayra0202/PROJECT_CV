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
        Schema::create('detail_surat_jalans', function (Blueprint $table) {
            $table->string('id_detail_surat_jalan')->primary();
            $table->unsignedBigInteger('id_surat_jalan'); // foreign key
            $table->string('nama_barang');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->timestamps();

            $table->foreign('id_surat_jalan')->references('id_surat_jalan')->on('surat_jalans')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_surat_jalans');
    }
};
