<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisBarang extends Model
{
    protected $table = 'jenis_barangs';
    protected $fillable = ['jenis', 'nama_jenis'];

    public function namaBarangs()
    {
        return $this->hasMany(NamaBarang::class, 'jenis_barang', 'jenis');
    }
}
