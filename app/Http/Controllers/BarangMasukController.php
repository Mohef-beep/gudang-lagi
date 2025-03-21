<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\NamaBarang;
use App\Models\Stock;
use Illuminate\Http\Request;

class BarangMasukController extends Controller
{
    public function index()
    {
        $barangs = NamaBarang::all();
        $transaksi = BarangMasuk::with('namaBarang')->get();
        return view('transaksi.barang-masuk', compact('barangs', 'transaksi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_barang_id' => 'required|exists:nama_barangs,id',
            'jumlah' => 'required|integer|min:1',
            'no_batch' => 'required|string',
            'tanggal_expired' => 'required|date'
        ]);

        // Create barang masuk record
        $barangMasuk = BarangMasuk::create([
            'tanggal_masuk' => now(),
            'nama_barang_id' => $request->nama_barang_id,
            'jumlah' => $request->jumlah,
            'no_batch' => $request->no_batch,
            'tanggal_expired' => $request->tanggal_expired
        ]);

        // Update or create stock record
        Stock::updateOrCreateStock(
            $request->nama_barang_id,
            $request->no_batch,
            $request->jumlah,
            $request->tanggal_expired
        );

        return redirect()->back()->with('success', 'Data barang masuk berhasil disimpan');
    }

    public function destroy($id)
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        
        // Update stock record
        $stock = Stock::where('nama_barang_id', $barangMasuk->nama_barang_id)
            ->where('no_batch', $barangMasuk->no_batch)
            ->first();

        if ($stock) {
            $stock->jumlah -= $barangMasuk->jumlah;
            if ($stock->jumlah <= 0) {
                $stock->delete();
            } else {
                $stock->save();
            }
        }

        $barangMasuk->delete();
        return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus');
    }
    }
