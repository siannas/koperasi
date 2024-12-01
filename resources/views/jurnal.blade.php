@extends('layouts.layout')
@extends('layouts.sidebar')

@php
$role = Auth::user()->role;
$role = explode(', ', $role);
@endphp

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
        <form class="form-horizontal input-margin-additional" method="POST" action="{{route('jurnal.store', ['tipe'=>$currentTipe->tipe]).'?dateawal='.$dateawal.'&date='.$date}}">
            @csrf
        <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control datepicker" id="date" name="tanggal" required placeholder="Tanggal">
                </div>
                <div class="form-group">
                    <label for="keterangan" class="bmd-label-floating">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                </div>
                <div class="row" style="margin-top: -8px;">
                    <div class="col-md-6">
                        <div class="form-group">
                            <select id="selectdebit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Akun Debit" name="id-debit" required>
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
                        <select id="selectdebit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Akun Kredit" name="id-kredit" required>
                            @foreach($akuns as $a)
                            <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kredit" class="bmd-label-floating">Kredit</label>
                        <input type="text" class="form-control rupiah-input" id="kredit" name="kredit-dummy" required readonly>
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
        <input type="hidden" class="form-control datepicker" id="date" name="dateawal" value="{{$dateawal}}">
        <input type="hidden" class="form-control datepicker" id="date" name="date" value="{{$date}}">
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
                        <select id="selectdebit-edit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Akun Debit" name="id-debit" required>
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
                        <select id="selectdebit-edit" class="selectpicker" data-size="7" data-style="btn btn-primary btn-round" title="Akun Kredit" name="id-kredit" required>
                            @foreach($akuns as $a)
                            <option value="{{$a->id}}">{{$a->{'nama-akun'} }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="kredit-edit" class="bmd-label-floating">Kredit</label>
                        <input type="text" class="form-control rupiah-input" id="kredit-edit" name="kredit-dummy" required readonly>
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
        <form id="formValidasi" method="POST" action="{{ route('jurnal.validasi', ['tipe'=>$currentTipe->tipe]).'?dateawal='.$dateawal.'&date='.$date }}">
        @csrf
            <div class="modal-footer justify-content-center" id="btnValidasi">
                <button type="button" class="btn btn-link" data-dismiss="modal">Tidak</button>
                <button type="submit" class="btn btn-warning btn-link">Ya
                    <div class="ripple-container"></div>
                </button>
            </div>
        </form>
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
                <i class="material-icons">account_balance_wallet</i>
            </div>
            <h4 class="card-title">Jurnal {{ucwords($currentTipe->tipe)}}</h4>
        </div> -->
        <div class="card-body">
            <form action="{{route('jurnal.filter', ['tipe'=>$currentTipe->tipe]).'?dateawal='.$dateawal.'&date='.$date}}" method="POST">
            @csrf
            <div class="toolbar row">
                <div class="col">
                    <div class="row mt-2">
                        <div class="col-md-3" >    
                            <div class="form-group" >    
                                <label for="dateawal" style="top:-16px!important;">Awal</label>
                                <input name="dateawal" id="dateawal" type="text" class="form-control datepicker" placeholder="PILIH TANGGAL" value="{{$dateawal}}" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" >    
                                <label for="dateakhir" style="top:-16px!important;">Akhir</label>
                                <input name="date" id="dateakhir" type="text" class="form-control datepicker" placeholder="PILIH TANGGAL" value="{{$date}}" >
                            </div>
                        </div>
                        <div class="col" style="padding-left:0;">
                            <button type="submit" class="btn btn-primary btn-round" formaction="{{route('jurnal.filter', ['tipe'=>$currentTipe->tipe]).'?dateawal='.$dateawal.'&date='.$date }}"><i class="material-icons">filter_alt</i> Proses</button>    
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <button type="submit" class="btn btn-success btn-sm" formaction="{{url($year . '/' . $currentTipe->tipe.'/jurnal/excel')}}">Download</button>
                    @if(!array_intersect($role, ['Supervisor', 'Admin']))
                    <button type="button" class="btn btn-primary btn-sm" onclick="onTambah(this)">Tambah</button>
                    @endif
                    @if(in_array('Supervisor', $role))
                    <button type="button" class="btn btn-warning btn-sm validasi" data-toggle="modal" data-target="#modalValidasi" >Validasi</button>
                    @endif
                </div>
            </div>
            </form>
            <div class="filter-tags" data-select="#selectrole" data-tags="#tagsinput" data-col="10">
                <div class="form-group d-inline-block" style="width: 120px;">
                    <select id="selectrole" class="selectpicker" data-style2="btn-default btn-round btn-sm text-white" data-style="select-with-transition" multiple title="Filter" data-size="7">
                        <option value="Reguler">Reguler</option>
                        @foreach($byroleFilter as $r)
                        <option value="{{$r}}">{{$r}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="h-100 d-inline-block">
                    <input id="tagsinput" hidden type="text" value="" class="form-control tagsinput" data-role="tagsinput" data-size="md" data-color="info" data-role="filter">
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
                    <th data-priority="4" class="center">Validasi</th>
                    <th data-priority="4" class="disabled-sorting text-right">Actions</th>
                    <th></th>
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
                    <th class="center">Validasi</th>
                    <th class="disabled-sorting text-right">Actions</th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
                </tfoot>
                <tbody>
                @foreach($jurnals as $key=>$j)
                <tr>
                    @php
                    $m2 = Carbon\Carbon::createFromDate($j->tanggal);
                    @endphp
                    <td class="dt-control">
                        <button class="btn btn-success btn-round btn-fab btn-sm mr-1">
                        <i class="material-icons">add</i>
                        </button>
                    </td>
                    <td>{{$m2->format('d/m/Y')}}</td>
                    <td>{{$j->keterangan}}</td>
                    <td>{{$j->akunDebit->{'no-akun'} }}</td>
                    <td>Rp {{ number_format($j->debit, 2) }}</td>
                    <td>{{$j->akunKredit->{'no-akun'} }}</td>
                    <td>Rp {{ number_format($j->kredit, 2) }}</td>
                    @if($j->validasi==1)
                    <td class="center"><i class="material-icons">task_alt</i></td>
                    @else
                    <td></td>
                    @endif
                    <td class="text-right">
                    
                    @if($j->validasi==1 && $m2->lessThan($datelock))
                    <a href="#" class="btn btn-link text-dark btn-just-icon disabled"><i class="material-icons">lock</i></a>
                    @elseif(in_array('Supervisor', $role) && $j->validasi==1)
                    <a href="#" class="btn btn-link text-warning btn-just-icon unvalidasi" data-toggle="modal" data-target="#modalValidasi" data-id="{{$j->id}}"><i class="material-icons">lock_open</i></a>
                    @elseif(!in_array('Supervisor', $role) && $j->validasi==1)
                    <a href="#" class="btn btn-link text-dark btn-just-icon disabled" data-toggle="modal" data-target="#modalValidasi"><i class="material-icons">lock</i></a>
                    @elseif(in_array('Supervisor', $role))
                    <div class="form-check">
                      <label class="form-check-label" style="padding-right:5px;">
                        <input class="form-check-input sub_chk" type="checkbox" data-id="{{$j->id}}">
                        <span class="form-check-sign">
                          <span class="check"></span>
                        </span>
                      </label>
                    </div>
                    @else
                        @if(!array_intersect($role, ['Supervisor', 'Spesial']) && $j->{'by-role'} !== $byrole)
                        <button class="btn btn-link text-dark btn-just-icon disabled"><i class="material-icons">block</i></button>
                        @else
                        <button data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-link btn-warning btn-just-icon edit btn-sm" key="{{$key}}" onclick="onEdit(this)"><i class="material-icons">edit</i></button>
                        <button data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-link btn-danger btn-just-icon remove btn-sm" key="{{$key}}" onclick="onDelete(this)"><i class="material-icons">delete</i></button>
                        <button data-toggle="tooltip" data-placement="left" title="Duplicate" class="btn btn-link btn-danger btn-just-icon duplicate btn-sm" key="{{$key}}" onclick="onDuplicate(this)"><i class="material-icons">content_copy</i></button>
                        @endif
                    @endif
                    
                    </td>
                    <td hidden>{{$j->akunDebit->{'nama-akun'} }}</td>
                    <td hidden>{{$j->akunKredit->{'nama-akun'} }}</td>
                    <td hidden>{{$j->{'by-role'} ? $j->{'by-role'} : 'Reguler' }}</td>
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
    
    $modal.find('[name=tanggal]').val(moment(j['tanggal']).format('L')).change();
    $modal.find('[name=keterangan]').val(j['keterangan']).change();
    $modal.find('[name=id-debit]').val(j['id-debit']).change();
    $modal.find('[name=debit-dummy]').val(j['debit']).change().blur();
    $modal.find('[name=id-kredit]').val(j['id-kredit']).change();
    $modal.find('[name=kredit-dummy]').val(j['kredit']).change().blur();
    $modal.find('form').attr('action', "{{route('jurnal.update', ['tipe'=>$currentTipe->tipe , 'jurnal'=>''])}}/"+j['id']+"?"+"dateawal="+dateawal+"&"+"date="+date);
    $modal.modal('show');
} 

//ketika klik delete
function onDelete(self) {
    var key = $(self).attr('key');
    var j = myJurnals[key];
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('jurnal.destroy', ['tipe'=>$currentTipe->tipe , 'jurnal'=>''])}}/"+j['id']+"?"+"dateawal="+dateawal+"&"+"date="+date);
    $modal.modal('show');
} 

//ketika klik duplicate
function onDuplicate(self) {
    var key = $(self).attr('key');
    var j = myJurnals[key];
    var $modal=$('#modalTambah');
    
    $modal.find('[name=tanggal]').val(moment(j['tanggal']).format('L')).change();
    $modal.find('[name=keterangan]').val(j['keterangan']).change();
    $modal.find('[name=id-debit]').val(j['id-debit']).change();
    $modal.find('[name=debit-dummy]').val(j['debit']).change().blur();
    $modal.find('[name=id-kredit]').val(j['id-kredit']).change();
    $modal.find('[name=kredit-dummy]').val(j['kredit']).change().blur();
    $modal.find('.modal-title').text('Duplikasi Jurnal');
    $modal.modal('show');
} 

//ketika klik tambah
function onTambah(self) {
    var $modal=$('#modalTambah');
    
    $modal.find('[name=tanggal]').val('').change();
    $modal.find('[name=keterangan]').val('').change();
    $modal.find('[name=id-debit]').val('').change();
    $modal.find('[name=id-kredit]').val('').change();

    $modal.find('[name=debit-dummy]')[0].oldValue = null;
    $modal.find('[name=kredit-dummy]')[0].oldValue = null;

    $modal.find('[name=kredit-dummy]').val('').change();   
    $modal.find('[name=debit-dummy]').val('').change().blur();

    $modal.find('.modal-title').text('Tambah Jurnal');
    $modal.modal('show');
} 

$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip()

    // KONTROL STATE DATE FILTER
    let dateawal, date;
    if(localStorage.date){
        dateawal =localStorage.dateawal;
        date = localStorage.date;

        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let dateParam = urlParams.get('date');
        let dateawalParam = urlParams.get('dateawal');

        if( !dateParam || !dateawalParam){
            console.log(dateParam, dateawalParam, date, dateawal);
            window.location.replace(location.pathname.split( '?' )[0]+"?"+"dateawal="+dateawal+"&"+"date="+date);
        }else if(date != dateParam || dateawal != dateawalParam){
            localStorage.setItem('dateawal', dateawalParam);
            localStorage.setItem('date', dateParam);
            dateawal =dateawalParam;
            date = dateParam;
        }

    }else{
        dateawal = @json($dateawal);
        date = @json($date);
        localStorage.setItem('dateawal', dateawal);
        localStorage.setItem('date', date);
    }
    window.history.replaceState('', '', location.pathname.split( '?' )[0]+"?"+"dateawal="+dateawal+"&"+"date="+date);
    // END of KONTROL STATE DATE FILTER

    my.initFormExtendedDatetimepickers({{$year}});
    if ($('.slider').length != 0) {
        md.initSliders();
    }

    table = $('#datatables').DataTable({
        responsive:{
            details: false
        },
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 50,
        columnDefs: [
            {   
                class: "details-control",
                orderable: false,
                targets: 0
            },
            { "visible": false, "targets": [9, 10, 11] },
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
    
    // $('.rupiah-input').change(function(e){
    //     var self=e.target;
    //     var curval= self.value.replace(/Rp|,/g, "");
    //     if (curval.trim()==='' && self.hasOwnProperty("oldValue")) {   //is it valid float number?
    //         curval= self.oldValue.replace(/Rp|,/g, "");
    //     }
    //     self.nextSibling.nextSibling.value=parseFloat(curval).toFixed(2)
    // });

    const debitSamaDenganKredit = function(self, $target){
        var raw;
        var curval= self.value.replace(/Rp|,/g, "");

        try {
            if (curval.trim()==='' && self.hasOwnProperty("oldValue")) {   //is it valid float number?
                curval= self.oldValue.replace(/Rp|,/g, "");
            }

            raw=parseFloat(curval).toFixed(2);
        } catch (error) {
            raw='';
        }
        self.nextSibling.nextSibling.value=raw;

        $target.val(self.value).change().blur();
        $target[0].nextSibling.nextSibling.value=raw;
    }
    
    //PADA MODAL TAMBAH
    $('#debit').focusout(function(e) { debitSamaDenganKredit(e.target, $('#kredit')) } );
    //PADA MODAL EDIT
    $('#debit-edit').focusout(function(e) { debitSamaDenganKredit(e.target, $('#kredit-edit')) } );

    //event pada tags filter
    $(".filter-tags").each(function(){
        var sel= $($(this).data('select'));
        var put=$($(this).data('tags'));
        var col=parseInt($(this).data('col'));
        put.tagsinput('input').attr('hidden',true);
        
        sel.change(function(){
        put.tagsinput('removeAll');
        sel.val().forEach(function(s){
            put.tagsinput('add', s);
        })

        //search nya pakai regex misal "Pusat|Spesial" artinya boleh Pusat atau Spesial
        var searchStr=sel.val().join('|');
        table.column(col).search( searchStr , true, false).draw();
        });

        put.on('itemRemoved', function(event) {
        sel.selectpicker('deselectAll');
        sel.selectpicker('val', put.tagsinput('items'));
        });
    });

    //set filter dulu
    $('#selectrole').selectpicker('deselectAll');
    if(@json($byrole)){
        $('#selectrole').selectpicker('val', @json($byrole));
    }else{
        $('#selectrole').selectpicker('val', 'Reguler');
    }
} );
</script>
<script type="text/javascript">
        
    var allVals = [];

    $('.validasi').on('click', function(e) {
        console.log()
        allVals = [];
        $(".sub_chk:checked").each(function() {
            allVals.push($(this).attr('data-id'));
        });
        var sum_jurnal = allVals.length;
        
        var mainContainer = document.getElementById("peringatanValidasi");
        var submit = document.getElementById("btnValidasi");

        if(allVals.length <=0){
            mainContainer.innerHTML = 'Pilih Jurnal Terlebih Dahulu';
            submit.style.visibility = "hidden";
        }
        else{
            $('#jumlah').attr("value", sum_jurnal);
            mainContainer.innerHTML = 'Ingin Validasi '+ sum_jurnal + ' Jurnal Ini? <br><br><small><i>*Jurnal yang sudah tervalidasi tidak dapat diubah</i></small>';
            submit.style.visibility = "visible";
        }
    });
    $('#formValidasi').submit(function(e){
        $this=$(this);

        allVals.forEach(unit =>{
            $input = $("<input />").attr("type", "hidden")
                .attr("name", "id[]")
                .attr("value", unit);
            $this.append($input);
        });
        $('#modalValidasi').modal('hide');
    });

    $('.unvalidasi').on('click', function(e) {
        allVals = [];
        //$(".unvalidasi").each(function() {
        //    allVals.push($(this).attr('data-id'));
        //});
        allVals.push($(this).attr('data-id'));
        var mainContainer = document.getElementById("peringatanValidasi");
        var submit = document.getElementById("btnValidasi");

        mainContainer.innerHTML = 'Ingin Menghapus Status <b>Validasi</b> Pada Jurnal Ini?';
        submit.style.visibility = "visible";
    });
    
</script>
@endsection