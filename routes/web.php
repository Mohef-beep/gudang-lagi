<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth'])->group(function () {
        Route::get('/logout', [AuthController::class, 'logout']);
        Route::get('/dashboard', function () {
            return view('dashboard');
        });

Route::get('/masterdata/nama-barang', [MasterDataController::class, 'namaBarang']);
Route::get('/masterdata/jenis-barang', [MasterDataController::class, 'jenisBarang']);
Route::get('/masterdata/satuan-barang', [MasterDataController::class, 'satuanBarang']);

//tambah data
Route::get('/master-data/nama-barang', [MasterDataController::class, 'namaBarang']);
Route::post('/master-data/nama-barang/add', [MasterDataController::class, 'tambahNamaBarang']);
Route::get('/master-data/jenis-barang', [MasterDataController::class, 'jenisBarang']);
Route::post('/master-data/jenis-barang/add', [MasterDataController::class, 'tambahJenisBarang']);
Route::get('/master-data/satuan-barang', [MasterDataController::class, 'satuanBarang']);
Route::post('/master-data/satuan-barang/add', [MasterDataController::class, 'tambahSatuanBarang']);

//edit data dan Delete data nama barang
Route::get('/master-data/nama-barang/{kode_barang}', [MasterDataController::class, 'getBarang']);
Route::put('/master-data/nama-barang/update/{kode_barang}', [MasterDataController::class, 'updateBarang']);
Route::delete('/master-data/nama-barang/delete/{kode_barang}', [MasterDataController::class, 'deleteBarang']);

//edit data dan Delete data jenis barang
Route::get('/master-data/jenis-barang/{id}', [MasterDataController::class, 'getJenisBarang']);
Route::put('/master-data/jenis-barang/update/{id}', [MasterDataController::class, 'updateJenisBarang']);
Route::delete('/master-data/jenis-barang/delete/{id}', [MasterDataController::class, 'deleteJenisBarang']);

//edit data dan Delete data satuan barang
Route::get('/master-data/satuan-barang/{id}', [MasterDataController::class, 'getSatuanBarang']);
Route::put('/master-data/satuan-barang/update/{id}', [MasterDataController::class, 'updateSatuanBarang']);
Route::delete('/master-data/satuan-barang/delete/{id}', [MasterDataController::class, 'deleteSatuanBarang']);

// Cabang
// Nama Cabang routes
Route::get('/masterdata/nama-cabang', [MasterDataController::class, 'namaCabang']);
Route::post('/master-data/nama-cabang/add', [MasterDataController::class, 'tambahNamaCabang']);
Route::get('/master-data/nama-cabang/{id}', [MasterDataController::class, 'getNamaCabang']);
Route::put('/master-data/nama-cabang/update/{id}', [MasterDataController::class, 'updateNamaCabang']);
Route::delete('/master-data/nama-cabang/delete/{id}', [MasterDataController::class, 'deleteNamaCabang']);

// Alamat Cabang routes
Route::get('/masterdata/alamat-cabang', [MasterDataController::class, 'alamatCabang']);
Route::post('/master-data/alamat-cabang/add', [MasterDataController::class, 'tambahAlamatCabang']);
Route::get('/master-data/alamat-cabang/{id}', [MasterDataController::class, 'getAlamatCabang']);
Route::put('/master-data/alamat-cabang/update/{id}', [MasterDataController::class, 'updateAlamatCabang']);
Route::delete('/master-data/alamat-cabang/delete/{id}', [MasterDataController::class, 'deleteAlamatCabang']);

// Penanggung Jawab Cabang routes
Route::get('/masterdata/penanggung-jawab-cabang', [MasterDataController::class, 'penanggungJawabCabang']);
Route::post('/master-data/penanggung-jawab-cabang/add', [MasterDataController::class, 'tambahPenanggungJawabCabang']);
Route::get('/master-data/penanggung-jawab-cabang/{id}', [MasterDataController::class, 'getPenanggungJawabCabang']);
Route::put('/master-data/penanggung-jawab-cabang/update/{id}', [MasterDataController::class, 'updatePenanggungJawabCabang']);
Route::delete('/master-data/penanggung-jawab-cabang/delete/{id}', [MasterDataController::class, 'deletePenanggungJawabCabang']);

//transaksi barang masuk
Route::get('/transaksi/barang-masuk', [TransaksiController::class, 'barangMasuk']);
Route::post('/transaksi/barang-masuk/add', [TransaksiController::class, 'tambahBarangMasuk']);
Route::delete('/transaksi/barang-masuk/delete/{id}', [TransaksiController::class, 'deleteBarangMasuk']);
Route::post('/transaksi/barang-masuk/add', [TransaksiController::class, 'tambahBarangMasuk']);

//transaksi hapus data barang masuk
Route::delete('/transaksi/barang-masuk/delete/{id}', [TransaksiController::class, 'deleteBarangMasuk']);

// barang keluar
// Barang Keluar routes
Route::get('/transaksi/barang-keluar', [TransaksiController::class, 'barangKeluar']);
Route::get('/transaksi/get-barang-masuk/{id}', [TransaksiController::class, 'getBarangMasukData']);
Route::get('/transaksi/get-last-id/{id}', [TransaksiController::class, 'getLastId']);
Route::post('/transaksi/barang-keluar/add', [TransaksiController::class, 'tambahBarangKeluar']);
Route::delete('/transaksi/barang-keluar/delete/{id}', [TransaksiController::class, 'deleteBarangKeluar']);

//export excel
Route::get('/transaksi/barang-keluar/export-excel', [TransaksiController::class, 'exportBarangKeluar']);
    });

//laporan
Route::get('/laporan/laporan-stock', [LaporanController::class, 'laporanstock']);
Route::get('/laporan/laporan-barang-masuk', [LaporanController::class, 'laporanbarangmasuk']);
Route::get('/laporan/laporan-barang-keluar', [LaporanController::class, 'laporanbarangkeluar'])->name('laporan.barang-keluar');