<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\JsonResponse;

class StockController extends Controller
{
    public function getTotalStock($namaBarangId): JsonResponse
    {
        $totalStock = Stock::where('nama_barang_id', $namaBarangId)->sum('jumlah');
        
        return response()->json([
            'total_stock' => $totalStock
        ]);
    }
}