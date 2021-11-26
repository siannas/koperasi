@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Akun
@endsection

@section('akunStatus')
active
@endsection

@section('content')
<!-- Modal Tambah Akun -->
<div class="modal fade" id="tambahAkun" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Akun</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal" action="{{route('akun.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-tipe" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe" required>
                            @foreach($tipe as $unit) <option value="{{$unit->id}}">{{$unit->tipe}}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-kategori" class="selectpicker" data-style="btn btn-primary btn-round" title="Kategori" required>
                            @foreach($kategori as $unit) <option value="{{$unit->id}}">{{$unit->kategori}}</option> @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="no-akun" class="bmd-label-floating">Kode Akun</label>
                <input id="no-akun" name="no-akun" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama-akun" class="bmd-label-floating">Nama Akun</label>
                <input id="nama-akun" name="nama-akun" type="text" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-link">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </form>
        </div>
    </div>
</div>

<!-- Modal Edit Akun -->
<div class="modal fade" id="editAkun" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Akun</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form id="id" class="form-horizontal"  method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-tipe" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe" required>
                            @foreach($tipe as $unit) <option value="{{$unit->id}}">{{$unit->tipe}}</option> @endforeach
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-kategori" class="selectpicker" data-style="btn btn-primary btn-round" title="Kategori" required>
                            @foreach($kategori as $unit) <option value="{{$unit->id}}">{{$unit->kategori}}</option> @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="no-akun" class="bmd-label-floating">Kode Akun</label>
                <input id="no_akun" name="no-akun" type="text" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="nama-akun" class="bmd-label-floating">Nama Akun</label>
                <input id="nama_akun" name="nama-akun" type="text" class="form-control" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-link">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </form>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
            <i class="material-icons">assignment</i>
            </div>
            <h4 class="card-title">DataTables.net</h4>
        </div>
        <div class="card-body">
            <div class="toolbar text-right">
                <!-- Here you can write extra buttons/actions for the toolbar -->
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahAkun">Tambah</button>
            </div>
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>No Akun</th>
                    <th>Nama Akun</th>
                    <th class="disabled-sorting text-right">Aksi</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>No Akun</th>
                    <th>Nama Akun</th>
                    <th class="text-right">Aksi</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($akun as $unit)
                <tr>
                    <td>{{$unit->getTipe->tipe }}</td>
                    <td>{{$unit->getKategori->kategori }}</td>
                    <td>{{$unit->{'no-akun'} }}</td>
                    <td>{{$unit->{'nama-akun'} }}</td>
                    <td class="text-right">
                    <button class="btn btn-link btn-primary btn-just-icon" data-toggle="modal" data-target="#editAkun"
                        data-tipe="{{$unit->getTipe->tipe}}" data-kategori="{{$unit->getKategori->kategori}}"
                        data-id="{{route('akun.update', [$unit->id])}}" data-noAkun="{{$unit->{'no-akun'} }}"
                        data-namaAkun="{{$unit->{'nama-akun'} }}"><i class="material-icons">edit</i></button>
                    <a href="#" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
            </div>
        </div>
        <!-- end content-->
        </div>
        <!--  end card  -->
    </div>
    <!-- end col-md-12 -->
    </div>
    <!-- end row -->
</div>
@endsection

@section('script')
<script>
$(document).ready(function() {
    $('#datatables').DataTable({
    "pagingType": "full_numbers",
    "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
    ],
    responsive: true,
    language: {
        search: "INPUT",
        searchPlaceholder: "Search records",
    }
    });

    var table = $('#datatables').DataTable();

    // Edit record

    table.on('click', '.edit', function() {
    $tr = $(this).closest('tr');

    if ($($tr).hasClass('child')) {
        $tr = $tr.prev('.parent');
    }

    var data = table.row($tr).data();
    alert('You press on Row: ' + data[0] + ' ' + data[1] + ' ' + data[2] + '\'s row.');
    });

    // Delete a record

    table.on('click', '.remove', function(e) {
    $tr = $(this).closest('tr');

    if ($($tr).hasClass('child')) {
        $tr = $tr.prev('.parent');
    }

    table.row($tr).remove().draw();
    e.preventDefault();
    });

    //Like record

    table.on('click', '.like', function() {
    alert('You clicked on Like button');
    });
});
</script>
<script>
    $('#editAkun').on('show.bs.modal', function (event) {
    // Button utk trigger kirim data ke modal
    var button = $(event.relatedTarget)
    
    // Ekstrak data dari atribut data-
    var id = button.data('id')
    var tipe = button.data('tipe')
    var kategori = button.data('kategori')
    var noAkun = button.data('noAkun')
    var namaAkun = button.data('namaAkun')
    console.log(noAkun)
    // Update isi modal
    var modal = $(this)
    modal.find('#id-tipe').val(tipe)
    modal.find('#id-kategori').val(kategori)
    modal.find('#no_akun').val(noAkun)
    modal.find('#nama_akun').val(namaAkun)
    $("#id").attr("action", id)
    
})
</script>
@endsection