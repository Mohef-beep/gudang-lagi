<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $fillable = [
        'nama_barang_id',
        'no_batch',
        'jumlah',
        'tanggal_expired'
    ];

    protected $casts = [
        'tanggal_expired' => 'date'
    ];

    public function namaBarang(): BelongsTo
    {
        return $this->belongsTo(NamaBarang::class);
    }

    public static function updateOrCreateStock($namaBarangId, $noBatch, $jumlah, $tanggalExpired)
    {
        return self::updateOrCreate(
            ['nama_barang_id' => $namaBarangId, 'no_batch' => $noBatch],
            [
                'jumlah' => $jumlah,
                'tanggal_expired' => $tanggalExpired
            ]
        );
    }

    public static function decreaseStock($namaBarangId, $noBatch, $jumlah)
    {
        $stock = self::where('nama_barang_id', $namaBarangId)
            ->where('no_batch', $noBatch)
            ->first();

        if ($stock && $stock->jumlah >= $jumlah) {
            $stock->jumlah -= $jumlah;
            $stock->save();
            return true;
        }

        return false;
    }
}