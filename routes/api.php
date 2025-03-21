<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\StockController;

Route::get('/stock/{namaBarangId}', [StockController::class, 'getTotalStock']);