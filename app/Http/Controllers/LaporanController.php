<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NamaBarang;
use App\Models\Stock;
use App\Models\BarangKeluar;


class LaporanController extends Controller
{
    public function laporanstock(Request $request)
    {
        $filter = $request->input('filter', 'all');
        $query = NamaBarang::with(['jenisBarang', 'satuanBarang'])
            ->select('nama_barangs.*')
            ->selectRaw('COALESCE(SUM(stocks.jumlah), 0) as stock')
            ->leftJoin('stocks', 'nama_barangs.id', '=', 'stocks.nama_barang_id')
            ->groupBy('nama_barangs.id');
        
        if ($filter === 'minimum') {
            $query->having('stock', '<=', 10);
        }
        
        $barangs = $query->get();
        return view('laporan.laporan-stock', compact('barangs', 'filter'));
    }

    public function laporanbarangmasuk(Request $request)
    {
        $query = Stock::with('namaBarang')
            ->select('stocks.*', 'nama_barangs.nama_barang')
            ->join('nama_barangs', 'stocks.nama_barang_id', '=', 'nama_barangs.id');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('stocks.created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $barangMasuks = $query->orderBy('stocks.created_at', 'desc')->get();
        return view('laporan.laporan-barang-masuk', compact('barangMasuks'));
    }

    public function laporanbarangkeluar(Request $request)
    {
        $query = Stock::with(['namaBarang', 'namaBarang.satuanBarang'])
            ->select('stocks.*', 'nama_barangs.nama_barang', 'nama_barangs.kode_barang')
            ->join('nama_barangs', 'stocks.nama_barang_id', '=', 'nama_barangs.id');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('stocks.created_at', [
                $request->start_date,
                $request->end_date
            ]);
        }

        $barangKeluars = $query->orderBy('stocks.created_at', 'desc')->get();
        return view('laporan.laporan-barang-keluar', compact('barangKeluars'));
    }
}
