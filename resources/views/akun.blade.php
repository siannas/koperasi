@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Akun
@endsection

@section('akunStatus')
active
@endsection

@section('content')
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Akun</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form action="{{route('akun.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group bmd-form-group is-filled">
                        <select name="id-tipe" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe" required>
                            @foreach($tipe as $unit) <option value="{{$unit->id}}">{{$unit->tipe}}</option> @endforeach
                        </select>
                        <span class="material-input"></span>
                        <span class="material-input"></span>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group bmd-form-group is-filled">
                        <select name="id-kategori" class="selectpicker" data-style="btn btn-primary btn-round" title="Kategori" required>
                            @foreach($kategori as $unit) <option value="{{$unit->id}}">{{$unit->kategori}}</option> @endforeach
                        </select>
                        <span class="material-input"></span>
                        <span class="material-input"></span>
                    </div>
                </div>
            </div>
            <div class="form-group bmd-form-group is-filled">
                <label class="label-control">Kode Akun</label>
                <input name="no-akun" type="text" class="form-control" required>
                <span class="material-input"></span>
                <span class="material-input"></span>
            </div>
            <div class="form-group bmd-form-group">
                <label class="label-control">Nama Akun</label>
                <input name="nama-akun" type="text" class="form-control" required>
                <span class="material-input"></span>
                <span class="material-input"></span>
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
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModal">Tambah</button>
                <button class="btn btn-primary btn-sm" onclick="md.showNotification(3,'top')">3 Top</button>
                <button class="btn btn-primary btn-sm" onclick="md.showNotification(4,'top')">4 Left</button>
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
                    <td>{{$unit->{'id-tipe'} }}</td>
                    <td>{{$unit->{'id-kategori'} }}</td>
                    <td>{{$unit->{'no-akun'} }}</td>
                    <td>{{$unit->{'nama-akun'} }}</td>
                    <td class="text-right">
                    <a href="#" class="btn btn-link btn-warning btn-just-icon"><i class="material-icons">edit</i></a>
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
    $(document).ready(function(){
        md.showNotification(3,'top');
    });
    
</script>
@endsection