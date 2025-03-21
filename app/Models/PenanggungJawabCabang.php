<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenanggungJawabCabang extends Model
{
    protected $fillable = [
        'nama_cabang_id',
        'nama_penanggung_jawab',
        'no_telepon_cs',
        'no_telepon_pj',
    ];

    public function namaCabang()
    {
        return $this->belongsTo(NamaCabang::class);
    }
}
