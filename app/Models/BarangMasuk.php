<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class BarangMasuk extends Model
{
    protected $fillable = [
        'tanggal_masuk',
        'nama_barang_id',
        'jumlah',
        'no_batch',
        'tanggal_expired'
    ];

    protected $dates = [
        'tanggal_masuk',
        'tanggal_expired'
    ];

    protected $casts = [
        'tanggal_masuk' => 'datetime',
        'tanggal_expired' => 'datetime'
    ];

    public function namaBarang(): BelongsTo
    {
        return $this->belongsTo(NamaBarang::class, 'nama_barang_id');
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class, ['nama_barang_id', 'no_batch'], ['nama_barang_id', 'no_batch']);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($barangMasuk) {
            DB::transaction(function () use ($barangMasuk) {
                Stock::updateOrCreateStock(
                    $barangMasuk->nama_barang_id,
                    $barangMasuk->no_batch,
                    $barangMasuk->jumlah,
                    $barangMasuk->tanggal_expired
                );
            });
        });
    }
}
