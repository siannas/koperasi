@extends('layouts.layout')
@extends('layouts.sidebar')

@php
$role = Auth::user()->role;
$role = explode(', ', $role);
@endphp

@section('title')
User
@endsection

@section('userStatus')
active
@endsection

@section('modal')
<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah User </h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal input-margin-additional" method="POST" action="{{route('user.store')}}">
        @csrf
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="nama" class="bmd-label-floating">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="nip" class="bmd-label-floating">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select id="selectrole" name="role[]" class="selectpicker" data-style="select-with-transition" multiple title="Role" data-size="7">
                            <option>Pusat</option>
                            <option>Spesial</option>
                            <option>Supervisor</option>
                            <option>Reguler-USP</option>
                            <option>Reguler-FC</option>
                            <option>Reguler-TK</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="bmd-label-floating">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-link text-primary">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </form>
        </div>
    </div>
</div>
<!--  End Modal Tambah -->

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit User </h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal input-margin-additional" method="POST" action="{{route('user.store')}}">
        @csrf
        @method('PUT')
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="nama" class="bmd-label-floating">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                </div>
                <div class="col">
                    <div class="form-group">
                        <label for="nip" class="bmd-label-floating">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <select id="selectroles" name="role[]" class="selectpicker" data-style="select-with-transition" multiple="multiple" title="Role" data-size="7" required>
                            <option>Pusat</option>
                            <option>Spesial</option>
                            <option>Supervisor</option>
                            <option>Reguler-USP</option>
                            <option>Reguler-FC</option>
                            <option>Reguler-TK</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="username" class="bmd-label-floating">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-link text-primary">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </form>
        </div>
    </div>
</div>
<!-- End Modal Edit -->

<!-- Modal Hapus -->
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
<!--  end modal Hapus -->
<!-- Modal Validasi -->
<div class="modal fade modal-mini modal-primary" id="modalValidasi" tabindex="-1" role="dialog" aria-labelledby="myDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-small">
        <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="material-icons">clear</i></button>
        </div>
        <div class="modal-body text-center">
            <p id="peringatanValidasi"></p>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-link" data-dismiss="modal">Tidak</button>
            <button type="submit" class="btn btn-warning btn-link" onclick="$('#formValidasi').trigger('submit')">Ya
                <div class="ripple-container"></div>
            </button>
        </div>
        <form id="formValidasi" method="POST" action=""></form>
        </div>
    </div>
</div>
<!--  end modal Validasi -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <!-- <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
                <i class="material-icons">people</i>
            </div>
            <h4 class="card-title">User</h4>
        </div> -->
        <div class="card-body">
            <div class="toolbar text-right">
                <button class="btn btn-primary btn-sm btn-text-14" data-toggle="modal" data-target="#modalTambah"><i class="material-icons">add</i>Kategori</button>
            </div>
            
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th data-priority="1">Username</th>
                    <th data-priority="2">NIP</th>
                    <th data-priority="3">Nama</th>
                    <th data-priority="1">Role</th>
                    <th data-priority="3" class="disabled-sorting text-right">Actions</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>Username</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Role</th>
                    <th class="disabled-sorting text-right">Actions</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($user as $key=>$unit)
                <tr>
                    <td>{{$unit->username}}</td>
                    <td>{{$unit->nip }}</td>
                    <td>{{$unit->nama}}</td>
                    <td>@foreach($unit->role as $unitrole)
                        <div class="bootstrap-tagsinput info-badge" style="padding:0 0;">
                            <span class="tag badge">{{$unitrole}}</span>
                        </div>
                        @endforeach
                    </td>
                    <td class="text-right">
                        @if($unit->role[0]=="Admin")
                        <a href="#" class="btn btn-link btn-sm text-dark btn-just-icon disabled"><i class="material-icons">lock</i></a>
                        @else
                        <a href="#" class="btn btn-link btn-warning btn-just-icon edit btn-sm" key="{{$key}}" onclick="onEdit(this)"><i class="material-icons">edit</i></a>
                        <a href="#" class="btn btn-link btn-danger btn-just-icon remove btn-sm" key="{{$key}}" onclick="onDelete(this)"><i class="material-icons">delete</i></a>
                        @endif
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
<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{asset('js/plugins/bootstrap-tagsinput.js')}}"></script>
<script>
var table;
var myUsers = @json($user);

//ketika klik edit
function onEdit(self) {
    var key = $(self).attr('key');
    var j = myUsers[key];
    
    $modal=$('#modalEdit');
    
    $modal.find('[name=username]').val(j['username']).change();
    $modal.find('[name=nip]').val(j['nip']).change();
    $modal.find('[name=nama]').val(j['nama']).change();
    $modal.find('[name=\'role[]\']').val(j['role']).change().blur();
    
    $modal.find('form').attr('action', "{{route('user.update', ['id'=>''])}}/"+j['id']);
    $modal.modal('show');
} 

//ketika klik delete
function onDelete(self) {
    var key = $(self).attr('key');
    var j = myUsers[key];
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('user.destroy', ['id'=>''])}}/"+j['id']);
    $modal.modal('show');
} 

$(document).ready(function() {
    my.initFormExtendedDatetimepickers();
    if ($('.slider').length != 0) {
        md.initSliders();
    }

    table = $('#datatables').DataTable({
        responsive:{
            details: false
        },
        columnDefs: [
            {   
                class: "details-control",
                orderable: false,
                targets: 0
            }
        ]
    });

} );
</script>
@endsection