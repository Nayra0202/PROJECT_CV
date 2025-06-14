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
        Schema::create('detail_permintaans', function (Blueprint $table) {
        $table->id('id_detail_permintaan')->primary();
        $table->unsignedBigInteger('id_permintaan');
        $table->string('id_barang');
        $table->integer('jumlah');
        $table->decimal('total_harga', 15, 2)->nullable();
        $table->timestamps();

        $table->foreign('id_permintaan')->references('id_permintaan')->on('permintaans')->onDelete('cascade');
        $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_permintaans');
    }
};
