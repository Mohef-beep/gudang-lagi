@extends('layouts.admin')

@section('title', 'Penanggung Jawab Cabang')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Penanggung Jawab Cabang</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                    <li class="breadcrumb-item active">Penanggung Jawab Cabang</li>
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
                        <h3 class="card-title">Daftar Penanggung Jawab Cabang</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">
                                <i class="fas fa-plus"></i> Tambah Penanggung Jawab
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <table id="example1" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Cabang</th>
                                    <th>Nama Penanggung Jawab</th>
                                    <th>No. Telepon Cabang (CS)</th>
                                    <th>No. Telepon Penanggung Jawab (PJ)</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->namaCabang->nama_cabang }}</td>
                                    <td>{{ $item->nama_penanggung_jawab }}</td>
                                    <td>{{ $item->no_telepon_cs }}</td>
                                    <td>{{ $item->no_telepon_pj }}</td>
                                    <td>
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal" onclick="editPJ({{ $item->id }})">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <form action="{{ url('/master-data/penanggung-jawab-cabang/delete/'.$item->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal Tambah -->
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Penanggung Jawab</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ url('/master-data/penanggung-jawab-cabang/add') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nama_cabang_id">Nama Cabang</label>
                        <select class="form-control" id="nama_cabang_id" name="nama_cabang_id" required>
                            <option value="">Pilih Cabang</option>
                            @foreach($nama_cabang as $cabang)
                                <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nama_penanggung_jawab">Nama Penanggung Jawab</label>
                        <input type="text" class="form-control" id="nama_penanggung_jawab" name="nama_penanggung_jawab" required>
                    </div>
                    <div class="form-group">
                        <label for="no_telepon_cs">No. Telepon Cabang (CS)</label>
                        <input type="text" class="form-control" id="no_telepon_cs" name="no_telepon_cs" required maxlength="15">
                    </div>
                    <div class="form-group">
                        <label for="no_telepon_pj">No. Telepon Penanggung Jawab (PJ)</label>
                        <input type="text" class="form-control" id="no_telepon_pj" name="no_telepon_pj" required maxlength="15">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Penanggung Jawab</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nama_cabang_id">Nama Cabang</label>
                        <select class="form-control" id="edit_nama_cabang_id" name="nama_cabang_id" required>
                            @foreach($nama_cabang as $cabang)
                                <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_penanggung_jawab">Nama Penanggung Jawab</label>
                        <input type="text" class="form-control" id="edit_nama_penanggung_jawab" name="nama_penanggung_jawab" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_no_telepon_cs">No. Telepon Cabang (CS)</label>
                        <input type="text" class="form-control" id="edit_no_telepon_cs" name="no_telepon_cs" required maxlength="15">
                    </div>
                    <div class="form-group">
                        <label for="edit_no_telepon_pj">No. Telepon Penanggung Jawab (PJ)</label>
                        <input type="text" class="form-control" id="edit_no_telepon_pj" name="no_telepon_pj" required maxlength="15">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- DataTables & Plugins -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

<!-- Font Awesome -->
<link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">

<script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

<script>
$(function () {
    $('#example1').DataTable({
        "responsive": true,
        "lengthChange": true,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
});

function editPJ(id) {
    $.get('/master-data/penanggung-jawab-cabang/' + id, function(data) {
        $('#edit_nama_cabang_id').val(data.nama_cabang_id);
        $('#edit_nama_penanggung_jawab').val(data.nama_penanggung_jawab);
        $('#edit_no_telepon_cs').val(data.no_telepon_cs);
        $('#edit_no_telepon_pj').val(data.no_telepon_pj);
        $('#editForm').attr('action', '/master-data/penanggung-jawab-cabang/update/' + id);
    });
}

$('#modal, #editModal').on('shown.bs.modal', function () {
    $(this).find('select, input:text').first().focus();
});

$('#modal, #editModal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
});
</script>
@endsection