<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BarangKeluar extends Model
{
    protected $fillable = [
        'id_pengiriman',
        'nama_cabang_id',
        'tanggal_keluar',
        'nama_barang_id',
        'jumlah',
        'no_batch',
        'tanggal_expired'
    ];

    protected $casts = [
        'tanggal_keluar' => 'date',
        'tanggal_expired' => 'date'
    ];

    public function namaCabang(): BelongsTo
    {
        return $this->belongsTo(NamaCabang::class);
    }

    public function namaBarang(): BelongsTo
    {
        return $this->belongsTo(NamaBarang::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, ['nama_barang_id', 'no_batch'], ['nama_barang_id', 'no_batch']);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($barangKeluar) {
            DB::transaction(function () use ($barangKeluar) {
                $success = Stock::decreaseStock(
                    $barangKeluar->nama_barang_id,
                    $barangKeluar->no_batch,
                    $barangKeluar->jumlah
                );

                if (!$success) {
                    throw new \Exception('Stok tidak mencukupi');
                }
            });
        });
    }
}
