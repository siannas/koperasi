@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Akun
@endsection

@section('akunStatus')
active
@endsection

@section('modal')
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
                <!-- <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-tipe" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe" required>
                            @foreach($tipe as $unit) <option value="{{$unit->id}}">{{ucwords($unit->tipe)}}</option> @endforeach
                        </select>
                    </div>
                </div> -->
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-kategori" class="selectpicker" data-style="btn btn-primary btn-round" title="Kategori" required>
                            @foreach($kategori as $unit) <option value="{{$unit->id}}">{{ucwords($unit->kategori)}}</option> @endforeach
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
            <div class="form-group">
                <label for="saldo" class="bmd-label-floating">Saldo</label>
                <input id="saldo" name="saldo" type="text" class="form-control rupiah-input" required>
                <input type="hidden" readonly name="saldo" required>
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
        <form action="{{route('akun.update', [$unit->id])}}" class="form-horizontal" method="post">
        @csrf
        @method('PUT')
        <input type="hidden" name="id-tipe">
        <input type="hidden" name="id-kategori">
        <div class="modal-body">
            <div class="row">
                <!-- <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-tipe-selector" class="selectpicker" data-style="btn btn-primary btn-round" title="Tipe" required disabled>
                            @foreach($tipe as $unitTipe) 
                            <option value="{{$unitTipe->id}}">{{ucwords($unitTipe->tipe)}}</option> 
                            @endforeach
                        </select>
                    </div>
                </div> -->
                <div class="col">
                    <div class="form-group is-filled">
                        <select name="id-kategori-selector" class="selectpicker" data-style="btn btn-primary btn-round" title="Kategori" required disabled>
                            @foreach($kategori as $unitKategori) 
                            <option value="{{$unitKategori->id}}">{{ucwords($unitKategori->kategori)}}</option> 
                            @endforeach
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
            <div class="form-group">
                <label for="saldo_awal" class="bmd-label-floating">Saldo Awal</label>
                <input id="_saldo_awal" name="saldo_awal" type="text" class="form-control rupiah-input" {{session()->get('role') == 'Admin' ? '' : 'readonly'}} required>
                <input type="hidden" {{session()->get('role') == 'Admin' ? '' : 'readonly'}} name="saldo_awal" required>
            </div>
            <!-- <div class="form-group">
                <label for="saldo" class="bmd-label-floating">Saldo</label>
                <input id="_saldo" name="saldo" type="text" class="form-control rupiah-input" readonly required>
                <input type="hidden" readonly name="saldo" required>
            </div> -->
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary btn-link">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </form>
        </div>
    </div>
</div>

<!-- Modal Hapus Akun -->
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
        <!-- <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
            <i class="material-icons">account_tree</i>
            </div>
            <h4 class="card-title">@yield('title') {{$year}}</h4>
        </div> -->
        <div class="card-body">
            <div class="toolbar text-right">
                <!-- Here you can write extra buttons/actions for the toolbar -->
                <button class="btn btn-primary btn-sm btn-text-14" data-toggle="modal" data-target="#tambahAkun"><i class="material-icons">add</i>Akun</button>
            </div>
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <!-- <th data-priority="3">Tipe</th> -->
                    <th data-priority="3">Kategori</th>
                    <th data-priority="2">No Akun</th>
                    <th data-priority="1">Nama Akun</th>
                    <th data-priority="4">Saldo Awal</th>
                    <!-- <th data-priority="4">Saldo</th> -->
                    <th data-priority="1" class="disabled-sorting text-right">Aksi</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <!-- <th>Tipe</th> -->
                    <th>Kategori</th>
                    <th>No Akun</th>
                    <th>Nama Akun</th>
                    <th>Saldo Awal (RP)</th>
                    <!-- <th>Saldo</th> -->
                    <th class="text-right">Aksi</th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($akun as $key=>$unit)
                <tr>
                    <!-- <td>{{$unit->getTipe ? ucwords($unit->getTipe->tipe) : ''}}</td> -->
                    <td>{{$unit->getKategori ? ucwords($unit->getKategori->kategori): ''}}</td>
                    <td>{{$unit->{'no-akun'} }}</td>
                    <td>{{$unit->{'nama-akun'} }}</td>
                    <td>{{ indo_num_format($unit->saldo_awal,2) }}</td>
                    <!-- <td>Rp {{ indo_num_format($unit->saldo,2) }}</td> -->
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
var myAkuns = @json($akun);

//ketika klik edit
function onEdit(self) {
    var key = $(self).attr('key');
    var j = myAkuns[key];
    console.log(j);
    $modal=$('#editAkun');

    $modal.find('[name=id-tipe]').val(j['id-tipe']);
    $modal.find('[name=id-kategori]').val(j['id-kategori']);
    $modal.find('[name=id-tipe-selector]').val(j['id-tipe']).change();
    $modal.find('[name=id-kategori-selector]').val(j['id-kategori']).change();
    $modal.find('[name=no-akun]').val(j['no-akun']).change();
    $modal.find('[name=nama-akun]').val(j['nama-akun']).change();
    $modal.find('[name=saldo_awal]').val(j['saldo_awal']).change().blur();
    // $modal.find('[name=saldo]').val(j['saldo']).change().blur();
    $modal.find('form').attr('action', "{{route('akun.update', ['akun'=>''])}}/"+j['id']);
    $modal.modal('show');
}

//ketika klik delete
function onDelete(self) {
    var key = $(self).attr('key');
    
    var j = myAkuns[key];
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('akun.destroy', ['akun'=>''])}}/"+j['id']);
    $modal.modal('show');
}

$(document).ready(function() {

    table = $('#datatables').DataTable({
    "pagingType": "full_numbers",
    "lengthMenu": [
        [10, 25, 50, -1],
        [10, 25, 50, "All"]
    ],
    iDisplayLength: -1,
    responsive: true,
    language: {
        search: "Search:",
    }
    });

});

</script>
<script>
    $('.rupiah-input').change(function(e){
        var self=e.target;
        var curval= self.value.replace(/Rp|,/g, "");
        if (curval.trim()==='' && self.hasOwnProperty("oldValue")) {   //is it valid float number?
            curval= self.oldValue.replace(/Rp|,/g, "");
        }
        self.nextSibling.nextSibling.value=parseFloat(curval).toFixed(2)
    });
</script>
@endsection