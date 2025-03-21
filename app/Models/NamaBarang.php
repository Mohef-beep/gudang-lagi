<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NamaBarang extends Model
{
    protected $table = 'nama_barangs';
    protected $fillable = ['kode_barang', 'nama_barang', 'jenis_barang', 'satuan_barang'];

    public function satuanBarang()
    {
        return $this->belongsTo(SatuanBarang::class, 'satuan_barang', 'satuan');
    }

    public function jenisBarang()
    {
        return $this->belongsTo(JenisBarang::class, 'jenis_barang', 'jenis');
    }
}
