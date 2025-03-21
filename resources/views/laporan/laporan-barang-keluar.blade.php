@extends('layouts.admin')
@section('title', 'Laporan Barang Keluar')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Barang Keluar</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Barang Keluar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-boxes mr-1"></i>
                        Data Barang Keluar
                    </h3>
                </div>
                <div class="card-body">
            <form action="{{ route('laporan.barang-keluar') }}" method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Tanggal Awal</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="end_date" class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </div>
            </form>

            <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Batch</th>
                        <th>Tanggal Keluar</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Keluar</th>
                        <th>Satuan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barangKeluars as $index => $barangKeluar)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $barangKeluar->no_batch }}</td>
                        <td>{{ date('Y-m-d', strtotime($barangKeluar->created_at)) }}</td>
                        <td>{{ $barangKeluar->kode_barang }}</td>
                        <td>{{ $barangKeluar->nama_barang }}</td>
                        <td>{{ abs($barangKeluar->jumlah) }}</td>
                        <td>{{ $barangKeluar->namaBarang->satuanBarang->nama_satuan }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-3 d-flex gap-2">
                    <button onclick="exportToExcel()" class="btn btn-success">
                        <i class="fas fa-file-excel mr-1"></i> Export Excel
                    </button>
                    <button onclick="printTable()" class="btn btn-primary">
                        <i class="fas fa-print mr-1"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script>
    function exportToExcel() {
        const table = document.getElementById('datatablesSimple');
        const wb = XLSX.utils.table_to_book(table, { sheet: 'Laporan Barang Keluar' });
        XLSX.writeFile(wb, 'laporan-barang-keluar.xlsx');
    }

    function printTable() {
        window.print();
    }
</script>

<style media="print">
    @page { size: landscape; }
    body * { visibility: hidden; }
    #datatablesSimple, #datatablesSimple * { visibility: visible; }
    #datatablesSimple { position: absolute; left: 0; top: 0; }
</style>
@endsection