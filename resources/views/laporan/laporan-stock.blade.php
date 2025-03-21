@extends('layouts.admin')
@section('title', 'Laporan Stock')
@section('content')
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Laporan Stock</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Laporan Stock</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-boxes mr-1"></i>
                            Data Stock Barang
                        </h3>
                        <div class="card-tools">
                            <form action="" method="GET" class="d-flex align-items-center">
                                <select name="filter" class="form-control select2" onchange="this.form.submit()">
                                    <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua Stock</option>
                                    <option value="minimum" {{ $filter == 'minimum' ? 'selected' : '' }}>Stock Minimum</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Jenis Barang</th>
                                    <th>Satuan</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($barangs as $index => $barang)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $barang->kode_barang }}</td>
                                    <td>{{ $barang->nama_barang }}</td>
                                    <td>{{ $barang->jenisBarang->jenis }}</td>
                                    <td>{{ $barang->satuanBarang->satuan }}</td>
                                    <td>{{ $barang->stock }}</td>
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
            </div>
        </div>
    </section>
</div>

<script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
<script>
    function exportToExcel() {
        const table = document.getElementById('example1');
        const wb = XLSX.utils.table_to_book(table, { sheet: 'Laporan Stock' });
        XLSX.writeFile(wb, 'laporan-stock.xlsx');
    }

    function printTable() {
        window.print();
    }
</script>

<style media="print">
    @page { size: landscape; }
    body * { visibility: hidden; }
    #example1, #example1 * { visibility: visible; }
    #example1 { position: absolute; left: 0; top: 0; }
</style>
@endsection