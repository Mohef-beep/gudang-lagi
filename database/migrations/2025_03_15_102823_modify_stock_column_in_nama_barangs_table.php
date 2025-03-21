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
        Schema::table('nama_barangs', function (Blueprint $table) {
            if (!Schema::hasColumn('nama_barangs', 'stock')) {
                $table->integer('stock')->default(0)->after('satuan_barang');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nama_barangs', function (Blueprint $table) {
            if (Schema::hasColumn('nama_barangs', 'stock')) {
                $table->dropColumn('stock');
            }
        });
    }
};