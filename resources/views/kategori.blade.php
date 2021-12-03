@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Kategori
@endsection

@section('kategoriStatus')
active
@endsection

@section('modal')
<!-- Modal Tambah Kategori -->
<div class="modal fade" id="tambahKategori" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal" action="{{route('kategori.store')}}" method="post">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="tipe-pendapatan" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe Pendapatan" required style="width:100%;">
                            <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="golongan" class="selectpicker" data-style="btn btn-primary btn-round" title="Golongan" required style="width:100%;">
                            @foreach($golongan as $unit)
                            <option value="{{$unit->id}}">{{$unit->kategori}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="kategori" class="bmd-label-floating">Nama Kategori</label>
                <input id="kategori" name="kategori" type="text" class="form-control" required>
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

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editKategori" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Kategori</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form  class="form-horizontal" method="post">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="tipe-pendapatan" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe Pendapatan" required>
                            <option value="debit">Debit</option>
                            <option value="kredit">Kredit</option>
                        </select>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="golongan" class="selectpicker" data-style="btn btn-primary btn-round" title="Golongan" required>
                            @foreach($golongan as $unit)
                            <option value="{{$unit->id}}">{{$unit->kategori}}</option>
                            @endforeach
                        </select>
                    </div>    
                </div>
            </div>
            <div class="form-group">
                <label for="kategori" class="bmd-label-floating">Nama Kategori</label>
                <input id="kategori" name="kategori" type="text" class="form-control" required>
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

<!-- Modal Hapus Kategori -->
<div class="modal fade modal-mini modal-primary" id="modalDelete" tabindex="-1" role="dialog" aria-labelledby="myDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
        </div>
        <form class="" method="POST" action="">
            @method('DELETE')
            @csrf
        <div class="modal-body text-center">
            <p>Yakin ingin menghapus?</p>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-link" data-dismiss="modal">Tidak</button>
            <button type="submit" class="btn btn-danger btn-link">Ya, Hapus
                <div class="ripple-container"></div>
            </button>
        </div>
        </form>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
            <i class="material-icons">category</i>
            </div>
            <h4 class="card-title">@yield('title')</h4>
        </div>
        <div class="card-body">
            <div class="toolbar text-right">
                <!-- Here you can write extra buttons/actions for the toolbar -->
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#tambahKategori">Tambah</button>
            </div>
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Golongan</th>
                    <th class="disabled-sorting text-right">Aksi</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Tipe</th>
                    <th>Kategori</th>
                    <th>Golongan</th>
                    <th class="text-right">Aksi</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($kategori as $key=>$unit)
                <tr>
                    <td>{{ucwords($unit->{'tipe-pendapatan'}) }}</td>
                    <td>{{$unit->kategori}}</td>
                    <td>
                        @php
                        foreach($golongan as $unitGol){
                            if($unit->parent==$unitGol->id){
                                echo $unitGol->kategori;
                            }
                        }
                        @endphp
                    </td>
                    <td class="text-right">
                        <button type="button" class="btn btn-sm btn-link btn-warning btn-just-icon edit" 
                            key="{{$key}}" onclick="onEdit(this)"><i class="material-icons">edit</i></button>
                        <button type="button" class="btn btn-sm btn-link btn-danger btn-just-icon remove" 
                            key="{{$key}}" onclick="onDelete(this)"><i class="material-icons">delete</i></button>
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
var table;
var myKategori = @json($kategori);

//ketika klik edit
function onEdit(self) {
    var key = $(self).attr('key');
    var j = myKategori[key];
    $modal=$('#editKategori');

    $modal.find('[name=tipe-pendapatan]').val(j['tipe-pendapatan']).change();
    $modal.find('[name=golongan]').val(j['parent']).change();
    $modal.find('[name=kategori]').val(j['kategori']).change();
    $modal.find('form').attr('action', "{{route('kategori.update', ['kategori'=>''])}}/"+j['id']);
    $modal.modal('show');
}

//ketika klik delete
function onDelete(self) {
    var key = $(self).attr('key');
    
    var j = myKategori[key];
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('kategori.destroy', ['kategori'=>''])}}/"+j['id']);
    $modal.modal('show');
}

$(document).ready(function() {
    $('#datatables').DataTable({
    "pagingType": "full_numbers",
    "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
    ],
    responsive: true,
    language: {
        search: "Search:",
    }
    });

});
</script>
@endsection