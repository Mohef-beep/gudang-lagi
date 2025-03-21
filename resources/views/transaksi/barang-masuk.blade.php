@extends('layouts.admin')

@section('title', 'Barang Masuk')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Input Barang Masuk</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Barang Masuk</li>
                </ol>
            </div>
        </div>
    </div>
</div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Input Barang Masuk</h3>
        </div>
        <div class="card-body">
            <form action="{{ url('/transaksi/barang-masuk/add') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Tanggal Masuk</label>
                    <input type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                </div>
                <div class="form-group">
                    <label>Nama Barang</label>
                    <select name="nama_barang_id" class="form-control select2" id="nama_barang" required>
                        <option value="">Pilih Barang</option>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" data-stock="{{ $barang->stock }}">
                                {{ $barang->nama_barang }} - {{ $barang->kode_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Stok Saat Ini</label>
                    <input type="text" id="current_stock" class="form-control" readonly>
                </div>
                <div class="form-group">
                    <label>Jumlah Barang Masuk</label>
                    <input type="number" name="jumlah" class="form-control" required min="1">
                </div>

                <div class="form-group">
                    <label>No. Batch</label>
                    <input type="text" name="no_batch" id="no_batch" class="form-control" required>
                    <div id="batch_warning" class="alert alert-danger mt-2" style="display: none;">Nomor batch ini sudah ada!</div>
                </div>

                <div class="form-group">
                    <label>Tanggal Expired</label>
                    <input type="date" name="tanggal_expired" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
            </form>
        </div>
    </div>

    <h3 class="mt-4">Data Barang Masuk</h3>
    <div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Data Barang Masuk</h3>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Masuk</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang Masuk</th>
                <th>No. Batch</th>
                <th>Tanggal Expired</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_masuk)->format('Y-m-d') }}</td>
                <td>{{ $item->namaBarang->nama_barang }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>{{ $item->no_batch }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal_expired)->format('Y-m-d') }}</td>
                <td><form action="{{ url('/transaksi/barang-masuk/delete/'.$item->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Hapus</button>
                </form></td>
            </tr>
            @endforeach
        </tbody>
            </table>
    </div>
</div>

    <script>
        document.getElementById('nama_barang').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const currentStock = selectedOption.dataset.stock || 0;
            document.getElementById('current_stock').value = currentStock;
            calculateFinalStock();
        });
        
        document.getElementById('jumlah').addEventListener('input', function() {
            calculateFinalStock();
        });
        
        document.getElementById('no_batch').addEventListener('input', function() {
            const batchNumber = this.value.trim();
            const existingBatches = Array.from(document.querySelectorAll('table tbody tr td:nth-child(5)')).map(td => td.textContent.trim());
            
            const warningElement = document.getElementById('batch_warning');
            const submitButton = document.querySelector('button[type="submit"]');
            
            if (existingBatches.includes(batchNumber)) {
                warningElement.style.display = 'block';
                submitButton.disabled = true;
            } else {
                warningElement.style.display = 'none';
                submitButton.disabled = false;
            }
        });
        
        function calculateFinalStock() {
            const currentStock = parseInt(document.getElementById('current_stock').value) || 0;
            const inputAmount = parseInt(document.getElementById('jumlah').value) || 0;
            const finalStock = currentStock + inputAmount;
            document.getElementById('final_stock').textContent = finalStock;
        }
        </script>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#nama_barang').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: 'Pilih Barang',
            allowClear: true
        });

        // Reset form setelah submit
        $('form').on('submit', function() {
            setTimeout(function() {
                $('#nama_barang').val(null).trigger('change');
                $('#current_stock').val('');
                $('#no_batch').val('');
                $('input[name="jumlah"]').val('');
                $('input[name="tanggal_expired"]').val('');
            }, 100);
        });
        $('#dataTable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "order": [[0, 'desc']]
        });

        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>
@endpush