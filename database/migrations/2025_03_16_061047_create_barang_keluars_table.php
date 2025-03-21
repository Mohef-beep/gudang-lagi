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
            $table->id();
            $table->string('id_pengiriman')->unique();
            $table->foreignId('nama_cabang_id')->constrained('nama_cabangs');
            $table->date('tanggal_keluar');
            $table->foreignId('nama_barang_id')->constrained('nama_barangs');
            $table->integer('jumlah');
            $table->string('no_batch');
            $table->date('tanggal_expired');
            $table->timestamps();
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
