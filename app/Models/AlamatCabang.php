<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlamatCabang extends Model
{
    protected $fillable = ['nama_cabang_id', 'alamat', 'kota', 'provinsi', 'kode_pos'];

    public function namaCabang()
    {
        return $this->belongsTo(NamaCabang::class);
    }
}
