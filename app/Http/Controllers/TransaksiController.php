<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NamaBarang;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\NamaCabang;
use App\Models\BarangKeluar;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Stock;

class TransaksiController extends Controller
{
    public function barangMasuk()
    {
        $barangs = NamaBarang::all();
        $transaksi = BarangMasuk::with('namaBarang')->orderBy('created_at', 'desc')->get();
        return view('transaksi.barang-masuk', compact('barangs', 'transaksi'));
    }

    public function tambahBarangMasuk(Request $request)
{
    $request->validate([
        'nama_barang_id' => 'required|exists:nama_barangs,id',
        'jumlah' => 'required|integer|min:1',
        'no_batch' => 'required|string',
        'tanggal_expired' => 'required|date|after:today',
    ]);
        DB::transaction(function () use ($request) {
            // Create barang masuk record
            BarangMasuk::create([
                'tanggal_masuk' => Carbon::now(),
                'nama_barang_id' => $request->nama_barang_id,
                'jumlah' => $request->jumlah,
                'no_batch' => $request->no_batch,
                'tanggal_expired' => $request->tanggal_expired,
            ]);
        });
    
        return redirect()->back()->with('success', 'Data barang masuk berhasil ditambahkan');
    }

    public function deleteBarangMasuk($id)
{
    DB::transaction(function () use ($id) {
        $barangMasuk = BarangMasuk::findOrFail($id);
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
    });
    
    return redirect()->back()->with('success', 'Data barang masuk berhasil dihapus');
}

// Barang Keluar
public function barangKeluar()
{
    $cabang = NamaCabang::all();
    $barang = NamaBarang::with('satuanBarang')->get();
    $transaksi = BarangKeluar::with(['namaCabang', 'namaBarang'])->orderBy('created_at', 'desc')->get();
    return view('transaksi.barang-keluar', compact('cabang', 'barang', 'transaksi'));
}

public function getStock($barang_id)
{
    $stocks = Stock::where('nama_barang_id', $barang_id)
        ->where('jumlah', '>', 0)
        ->orderBy('tanggal_expired')
        ->get();
    
    $totalStock = $stocks->sum('jumlah');
    
    $batchData = $stocks->map(function($item) {
        return [
            'no_batch' => $item->no_batch,
            'tanggal_expired' => $item->tanggal_expired,
            'stock' => $item->jumlah
        ];
    });

    return response()->json([
        'batch_data' => $batchData,
        'stock' => $totalStock
    ]);

}

public function tambahBarangKeluar(Request $request)
{
    $request->validate([
        'nama_cabang_id' => 'required|exists:nama_cabangs,id',
        'tanggal_keluar' => 'required|date',
        'nama_barang_id' => 'required|exists:nama_barangs,id',
        'jumlah' => 'required|integer|min:1',
    ]);

    $barang = NamaBarang::find($request->nama_barang_id);
    if ($barang->stock < $request->jumlah) {
        return redirect()->back()->with('error', 'Stock tidak mencukupi');
    }

    $cabang = NamaCabang::find($request->nama_cabang_id);
    $lastId = BarangKeluar::where('nama_cabang_id', $request->nama_cabang_id)
        ->count();
    $idPengiriman = $cabang->kode_cabang . sprintf("%03d", $lastId + 1);

    DB::transaction(function () use ($request, $idPengiriman, $barang) {
        BarangKeluar::create([
            'id_pengiriman' => $idPengiriman,
            'nama_cabang_id' => $request->nama_cabang_id,
            'tanggal_keluar' => $request->tanggal_keluar,
            'nama_barang_id' => $request->nama_barang_id,
            'jumlah' => $request->jumlah,
            'no_batch' => $request->no_batch,
            'tanggal_expired' => $request->tanggal_expired,
        ]);

        $barang->stock -= $request->jumlah;
        $barang->save();
    });

    return redirect()->back()->with('success', 'Data barang keluar berhasil ditambahkan');
}

public function deleteBarangKeluar($id)
{
    DB::transaction(function () use ($id) {
        $barangKeluar = BarangKeluar::findOrFail($id);
        $barang = NamaBarang::find($barangKeluar->nama_barang_id);
        $barang->stock += $barangKeluar->jumlah;
        $barang->save();
        $barangKeluar->delete();
    });

    return redirect()->back()->with('success', 'Data barang keluar berhasil dihapus');
}

public function getBarangMasukData($id)
{
    $stocks = Stock::where('nama_barang_id', $id)
        ->where('jumlah', '>', 0)
        ->orderBy('tanggal_expired')
        ->get();
    
    $batchData = $stocks->map(function($item) {
        return [
            'no_batch' => $item->no_batch,
            'tanggal_expired' => $item->tanggal_expired,
            'stock' => $item->jumlah
        ];
    });

    return response()->json([
        'batch_data' => $batchData
    ]);
}

public function getLastId($cabang_id)
{
    $cabang = NamaCabang::find($cabang_id);
    $today = now()->format('Y-m-d');
    
    $lastShipmentToday = BarangKeluar::where('nama_cabang_id', $cabang_id)
        ->whereDate('tanggal_keluar', $today)
        ->first();
    
    if ($lastShipmentToday) {
        return response()->json(['id_pengiriman' => $lastShipmentToday->id_pengiriman]);
    }
    
    $lastId = BarangKeluar::where('nama_cabang_id', $cabang_id)
        ->orderBy('id', 'desc')
        ->first();
    
    $nextId = $lastId ? (int)substr($lastId->id_pengiriman, -3) + 1 : 1;
    $idPengiriman = $cabang->kode_cabang . sprintf("%03d", $nextId);
    
    return response()->json(['id_pengiriman' => $idPengiriman]);
}

public function exportBarangKeluar()
{
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Headers
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'ID Pengiriman');
    $sheet->setCellValue('C1', 'Cabang');
    $sheet->setCellValue('D1', 'Tanggal Keluar');
    $sheet->setCellValue('E1', 'Nama Barang');
    $sheet->setCellValue('F1', 'Jumlah');
    $sheet->setCellValue('G1', 'No. Batch');
    $sheet->setCellValue('H1', 'Expired');

    $transaksi = BarangKeluar::with(['namaCabang', 'namaBarang'])->get();
    $row = 2;
    foreach ($transaksi as $index => $item) {
        $sheet->setCellValue('A' . $row, $index + 1);
        $sheet->setCellValue('B' . $row, $item->id_pengiriman);
        $sheet->setCellValue('C' . $row, $item->namaCabang->nama_cabang);
        $sheet->setCellValue('D' . $row, $item->tanggal_keluar->format('Y-m-d'));
        $sheet->setCellValue('E' . $row, $item->namaBarang->nama_barang);
        $sheet->setCellValue('F' . $row, $item->jumlah);
        $sheet->setCellValue('G' . $row, $item->no_batch);
        $sheet->setCellValue('H' . $row, $item->tanggal_expired->format('Y-m-d'));
        $row++;
    }

    // Auto-size columns
    foreach (range('A', 'H') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $writer = new Xlsx($spreadsheet);
    $filename = 'barang-keluar-' . date('Y-m-d') . '.xlsx';

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');

    $writer->save('php://output');
    exit;
}

}