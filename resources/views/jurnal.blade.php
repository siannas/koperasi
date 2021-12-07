@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Jurnal
@endsection

@section('jurnalShow')
show
@endsection

@section('jurnal'.$currentTipe->tipe)
active
@endsection

@section('modal')
<!-- Classic Modal -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Tambah Jurnal </h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal input-margin-additional" method="POST" action="{{route('jurnal.store', ['tipe'=>$currentTipe->tipe])}}">
            @csrf
        <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control datepicker" id="date" name="tanggal" required>
                </div>
                <div class="form-group">
                    <label for="keterangan" class="bmd-label-floating">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                </div>
                <div class="row" style="margin-top: -8px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="selectdebit" class="bmd-label-floating" style="font-size:11px;">Akun Debit</label>
                            <select id="selectdebit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Single Select" name="id-debit" required>
                                @foreach($akuns as $a)
                                <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="debit" class="bmd-label-floating">Debit</label>
                            <input type="text" class="form-control rupiah-input" id="debit" name="debit-dummy" required >
                            <input type="hidden" readonly name="debit" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="form-group">
                        <label for="selectdebit" class="bmd-label-floating" style="font-size:11px;">Akun Kredit</label>
                            <select id="selectdebit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Single Select" name="id-kredit" required>
                                @foreach($akuns as $a)
                                <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kredit" class="bmd-label-floating">Kredit</label>
                            <input type="text" class="form-control rupiah-input" id="kredit" name="kredit-dummy" required >
                            <input type="hidden" readonly name="kredit" required>
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
<!--  End Modal -->
<!-- Edit Modal -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Edit Jurnal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
            <i class="material-icons">clear</i>
            </button>
        </div>
        <form class="form-horizontal input-margin-additional" method="POST" action="">
            @method('PUT')
            @csrf
        <div class="modal-body">
            <div class="form-group">
                <input type="text" class="form-control datepicker" id="date" name="tanggal" required>
            </div>
            <div class="form-group">
                <label for="keterangan-edit" class="bmd-label-floating">Keterangan</label>
                <input type="text" class="form-control" id="keterangan-edit" name="keterangan" required>
            </div>
            <div class="row" style="margin-top: -8px;">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="selectdebit-edit" class="bmd-label-floating" style="font-size:11px;">Akun Debit</label>
                        <select id="selectdebit-edit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Single Select" name="id-debit" required>
                            @foreach($akuns as $a)
                            <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="debit-edit" class="bmd-label-floating">Debit</label>
                        <input type="text" class="form-control rupiah-input" id="debit-edit" name="debit-dummy" required >
                        <input type="hidden" readonly name="debit" required>
                    </div>
                </div>
                <div class="col-md-6">
                <div class="form-group">
                    <label for="selectdebit-edit" class="bmd-label-floating" style="font-size:11px;">Akun Kredit</label>
                        <select id="selectdebit-edit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Single Select" name="id-kredit" required>
                            @foreach($akuns as $a)
                            <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kredit-edit" class="bmd-label-floating">Kredit</label>
                        <input type="text" class="form-control rupiah-input" id="kredit-edit" name="kredit-dummy" required >
                        <input type="hidden" readonly name="kredit" required>
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
<!--  End Edit Modal -->
<!-- small modal -->
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
<!--    end small modal -->
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-icon">
                <i class="material-icons">account_balance_wallet</i>
            </div>
            <h4 class="card-title">Jurnal {{ucwords($currentTipe->tipe)}}</h4>
        </div>
        <div class="card-body">
            <div class="toolbar row">
                <div class="col">
                    <form action="{{route('jurnal.filter', ['tipe'=>$currentTipe->tipe])}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="form-group col-md-3" style="padding-right:0;">    
                            <input name="date" id="date" type="text" class="form-control monthyearpicker" placeholder="PILIH BULAN" value="{{$date}}">
                        </div>
                        <div class="col" style="padding-left:0;">
                            <button class="btn btn-primary btn-round"><i class="material-icons">filter_alt</i> Proses</button>    
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col-2 text-right">
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah" onclick="document.getElementById('date').value = '{{$date}}'">Tambah</button>
                </div>
            </div>
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th data-priority="1" width="1" class="disabled-sorting"></th>
                    <th data-priority="1">Tanggal</th>
                    <th data-priority="2">Keterangan</th>
                    <th data-priority="3">Akun Debit</th>
                    <th data-priority="1">Debit</th>
                    <th data-priority="3">Akun Kredit</th>
                    <th data-priority="1">Kredit</th>
                    <th data-priority="4" class="disabled-sorting text-right">Actions</th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Akun Debit</th>
                    <th>Debit</th>
                    <th>Akun Kredit</th>
                    <th>Kredit</th>
                    <th class="disabled-sorting text-right">Actions</th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($jurnals as $key=>$j)
                <tr>
                    <td class="dt-control">
                        <button class="btn btn-success btn-round btn-fab btn-sm mr-1">
                        <i class="material-icons">add</i>
                        </button>
                    </td>
                    <td>{{$j->tanggal}}</td>
                    <td>{{$j->keterangan}}</td>
                    <td>{{$j->akunDebit->{'no-akun'} }}</td>
                    <td>Rp {{ number_format($j->debit, 2) }}</td>
                    <td>{{$j->akunKredit->{'no-akun'} }}</td>
                    <td>Rp {{ number_format($j->kredit, 2) }}</td>
                    <td class="text-right">
                    @if($j->isOld)
                    <a href="#" class="btn btn-link text-info btn-just-icon"><i class="material-icons">lock</i></a>
                    @else
                    <a href="#" class="btn btn-link btn-warning btn-just-icon edit btn-sm" key="{{$key}}" onclick="onEdit(this)"><i class="material-icons">edit</i></a>
                    <a href="#" class="btn btn-link btn-danger btn-just-icon remove btn-sm" key="{{$key}}" onclick="onDelete(this)"><i class="material-icons">delete</i></a>
                    @endif
                    </td>
                    <td hidden>{{$j->akunDebit->{'nama-akun'} }}</td>
                    <td hidden>{{$j->akunKredit->{'nama-akun'} }}</td>
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
var myJurnals = @json($jurnals);

//ketika klik edit
function onEdit(self) {
    var key = $(self).attr('key');
    var j = myJurnals[key];
    $modal=$('#modalEdit');

    $modal.find('[name=tanggal]').val(j['tanggal2']).change();
    $modal.find('[name=keterangan]').val(j['keterangan']).change();
    $modal.find('[name=id-debit]').val(j['id-debit']).change();
    $modal.find('[name=debit-dummy]').val(j['debit']).change().blur();
    $modal.find('[name=id-kredit]').val(j['id-kredit']).change();
    $modal.find('[name=kredit-dummy]').val(j['kredit']).change().blur();
    $modal.find('form').attr('action', "{{route('jurnal.update', ['tipe'=>$currentTipe->tipe , 'jurnal'=>''])}}/"+j['id']);
    $modal.modal('show');
} 

//ketika klik delete
function onDelete(self) {
    var key = $(self).attr('key');
    var j = myJurnals[key];
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('jurnal.destroy', ['tipe'=>$currentTipe->tipe , 'jurnal'=>''])}}/"+j['id']);
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
            },
            { "visible": false, "targets": [8,9] }
        ]
    });
    function format (d) {
        return '<table class="table table-no-style"><tbody>'+
            '<tr><td width="15%"><b>'+'Tanggal'+'</b></td><td>'+d[1]+'</td></tr>'+
            '<tr><td><b>'+'Keterangan'+'</b></td><td>'+d[2]+'</td></tr>'+
            '<tr><td><b>'+'Akun Debit'+'</b></td><td>'+d[3]+' - '+d[8]+'</td></tr>'+
            '<tr><td><b>'+'Debit'+'</b></td><td>'+d[4]+'</td></tr>'+
            '<tr><td><b>'+'Akun Kredit'+'</b></td><td>'+d[5]+' - '+d[9]+'</td></tr>'+
            '<tr><td><b>'+'Kredit'+'</b></td><td>'+d[6]+'</td></tr>'+
            '<tbody></table>';
    } 

    $('#datatables tbody').on('click', 'td.dt-control', function () {
		var tr = $(this).closest('tr');
		var row = table.row( tr );
        var btn=tr.find('.dt-control button');
        
		if ( row.child.isShown() ) {
			// This row is already open - close it
			row.child.hide();
            btn.removeClass('btn-danger');
            btn.addClass('btn-success');
            btn.html('<i class="material-icons">add</i>')
		}
		else {
			row.child( format(row.data())).show();
			tr.addClass('shown'); 
            btn.addClass('btn-danger');
            btn.removeClass('btn-success');
            btn.html('<i class="material-icons">remove</i>')
		}
	} );
    
    $('.rupiah-input').change(function(e){
        var self=e.target;
        var curval= self.value.replace(/Rp|,/g, "");
        if (curval.trim()==='' && self.hasOwnProperty("oldValue")) {   //is it valid float number?
            curval= self.oldValue.replace(/Rp|,/g, "");
        }
        self.nextSibling.nextSibling.value=parseFloat(curval).toFixed(2)
    });
    
} );
</script>
@endsection