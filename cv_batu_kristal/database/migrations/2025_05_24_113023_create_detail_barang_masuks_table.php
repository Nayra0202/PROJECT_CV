<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_barang_masuks', function (Blueprint $table) {
            $table->bigIncrements('id_detail_masuk');
            $table->string('id_masuk', 10);
            $table->string('id_barang', 10);
            $table->integer('jumlah');
            $table->string('satuan', 255); 
            $table->timestamps();

            $table->foreign('id_masuk')->references('id_masuk')->on('barang_masuks')->onDelete('cascade');
            $table->foreign('id_barang')->references('id_barang')->on('barangs')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_barang_masuks');
    }
};
