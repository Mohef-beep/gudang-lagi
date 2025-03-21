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
        Schema::create('penanggung_jawab_cabangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nama_cabang_id')->constrained('nama_cabangs')->onDelete('cascade');
            $table->string('nama_penanggung_jawab');
            $table->string('no_telepon_cs', 15);
            $table->string('no_telepon_pj', 15);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penanggung_jawab_cabangs');
    }
};
