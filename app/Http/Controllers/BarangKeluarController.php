<?php

namespace App\Http\Controllers;

use App\Models\BarangKeluar;
use App\Models\NamaBarang;
use App\Models\NamaCabang;
use App\Models\Stock;
use Illuminate\Http\Request;

class BarangKeluarController extends Controller
{
    public function index()
    {
        $barang = NamaBarang::all();
        $cabang = NamaCabang::all();
        $transaksi = BarangKeluar::with(['namaBarang', 'namaCabang'])->get();
        return view('transaksi.barang-keluar', compact('barang', 'cabang', 'transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_cabang_id' => 'required|exists:nama_cabangs,id',
            'nama_barang_id' => 'required|exists:nama_barangs,id',
            'jumlah' => 'required|integer|min:1',
            'no_batch' => 'required|string',
            'tanggal_keluar' => 'required|date',
            'tanggal_expired' => 'required|date'
        ]);

        // Check stock availability
        $stock = Stock::where('nama_barang_id', $request->nama_barang_id)
            ->where('no_batch', $request->no_batch)
            ->first();

        if (!$stock || $stock->jumlah < $request->jumlah) {
            return redirect()->back()->with('error', 'Stock tidak mencukupi');
        }

        // Create barang keluar record
        $barangKeluar = BarangKeluar::create([
            'id_pengiriman' => $request->id_pengiriman,
            'nama_cabang_id' => $request->nama_cabang_id,
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_barang_id' => $request->nama_barang_id,
            'jumlah' => $request->jumlah,
            'no_batch' => $request->no_batch,
            'tanggal_expired' => $request->tanggal_expired
        ]);

        // Update stock
        Stock::decreaseStock($request->nama_barang_id, $request->no_batch, $request->jumlah);

        // Update total stock in nama_barang
        $namaBarang = NamaBarang::find($request->nama_barang_id);
        $namaBarang->decrement('stock', $request->jumlah);

        return redirect()->back()->with('success', 'Data barang keluar berhasil disimpan');
    }

    public function destroy($id)
    {
        $barangKeluar = BarangKeluar::findOrFail($id);

        // Restore stock
        Stock::updateOrCreateStock(
            $barangKeluar->nama_barang_id,
            $barangKeluar->no_batch,
            $barangKeluar->jumlah,
            $barangKeluar->tanggal_expired
        );

        // Update total stock in nama_barang
        $namaBarang = NamaBarang::find($barangKeluar->nama_barang_id);
        $namaBarang->increment('stock', $barangKeluar->jumlah);

        $barangKeluar->delete();
        return redirect()->back()->with('success', 'Data barang keluar berhasil dihapus');
    }

    public function getLastId($cabangId)
    {
        $lastId = BarangKeluar::where('nama_cabang_id', $cabangId)
            ->orderBy('id', 'desc')
            ->first();

        $newId = 'BK-' . str_pad($cabangId, 3, '0', STR_PAD_LEFT) . '-' .
            str_pad(($lastId ? intval(substr($lastId->id_pengiriman, -3)) + 1 : 1), 3, '0', STR_PAD_LEFT);

        return response()->json(['id_pengiriman' => $newId]);
    }

    public function getBatchStock($noBatch)
    {
        $stock = Stock::where('no_batch', $noBatch)->first();
        return response()->json(['stock' => $stock ? $stock->jumlah : 0]);
    }
}