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
            <button type="button" class="close" data-dismiss="modal">
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
                    <label for="keterangan" class="bmd-label-floating">REF</label>
                    <input type="text" class="form-control" id="ref" maxlength=18 name="no-ref">
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
                                <option value="{{$a->id}}">{{$a->{'no-akun'} .  " - " .  $a->{'nama-akun'} }}</option>
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
                            <option value="{{$a->id}}">{{$a->{'no-akun'} .  " - " .  $a->{'nama-akun'} }}</option>
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
            <button type="button" class="close" data-dismiss="modal">
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
                <label for="keterangan" class="bmd-label-floating">REF</label>
                <input type="text" class="form-control" id="ref" maxlength=18 name="no-ref">
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
                            <option value="{{$a->id}}">{{$a->{'no-akun'} .  " - " .  $a->{'nama-akun'} }}</option>
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
                            <option value="{{$a->id}}">{{$a->{'no-akun'} .  " - " .  $a->{'nama-akun'} }}</option>
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
            <button type="button" class="close" data-dismiss="modal"><i class="material-icons">clear</i></button>
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
            <button type="button" class="close" data-dismiss="modal"><i class="material-icons">clear</i></button>
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
                                <input required id="dateawal" name="dateawal" autocomplete="off" type="text" class="form-control monthpicker" value="{{$dateawal}}">
                                <!-- <input name="dateawal" id="dateawal" type="text" class="form-control datepicker" placeholder="PILIH TANGGAL" value="{{$dateawal}}" > -->
                            </div>
                        </div>
                        <!-- <div class="col-md-3">
                            <div class="form-group" >    
                                <label for="dateakhir" style="top:-16px!important;">Akhir</label>
                                <input name="date" id="dateakhir" type="text" class="form-control datepicker" placeholder="PILIH TANGGAL" value="{{$date}}" >
                            </div>
                        </div> -->
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
            <!-- data-col="11" means column index for role filter -->
            <!-- <div class="filter-tags" data-select="#selectrole" data-tags="#tagsinput" data-col="12">
                <div class="form-group d-inline-block" style="width: 120px;">
                    <select id="selectrole" class="selectpicker" data-style2="btn-default btn-round btn-sm text-white" data-style="select-with-transition" multiple title="Filter" data-size="7">
                        @foreach($byroleFilter as $r)
                        <option value="{{$r}}">{{$r}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="h-100 d-inline-block">
                    <input id="tagsinput" hidden type="text" value="" class="form-control tagsinput" data-role="tagsinput" data-size="md" data-color="info" data-role="filter">
                </div>
            </div> -->
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th data-priority="1" width="1" class="disabled-sorting"></th>
                    <th data-priority="1">Tanggal</th>
                    <th data-priority="4" class="center">Ref</th>
                    <th data-priority="2">Keterangan</th>
                    <th data-priority="3">Akun Debit</th>
                    <th data-priority="1">Debit (Rp)</th>
                    <th data-priority="3">Akun Kredit</th>
                    <th data-priority="1">Kredit (Rp)</th>
                    <th data-priority="4" class="center">Validasi</th>
                    <th data-priority="4" class="disabled-sorting text-right">Actions</th>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th></th>
                    <th>Tanggal</th>
                    <th>Ref</th>
                    <th>Keterangan</th>
                    <th>Akun Debit</th>
                    <th>Debit (Rp)</th>
                    <th>Akun Kredit</th>
                    <th>Kredit (Rp)</th>
                    <th class="center">Validasi</th>
                    <th class="disabled-sorting text-right">Actions</th>
                </tr>
                </tfoot>
                <tbody>
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
const role = @json($role);
const byroles = (@json($byrole)).concat([null]);

//ketika klik edit
function onEdit(self) {
    var key = $(self).attr('key');
    let tr = $(self).closest('tr');
    var j = table.row(tr).data();
    $modal=$('#modalEdit');
    
    $modal.find('[name=tanggal]').val(moment(j['tanggal']).format('L')).change();
    $modal.find('[name=keterangan]').val(j['keterangan']).change();
    $modal.find('[name=no-ref]').val(j['no-ref']).change();
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
    let tr = $(self).closest('tr');
    var j = table.row(tr).data();
    $modal=$('#modalDelete');

    $modal.find('form').attr('action', "{{route('jurnal.destroy', ['tipe'=>$currentTipe->tipe , 'jurnal'=>''])}}/"+j['id']+"?"+"dateawal="+dateawal+"&"+"date="+date);
    $modal.modal('show');
} 

//ketika klik duplicate
function onDuplicate(self) {
    var key = $(self).attr('key');
    let tr = $(self).closest('tr');
    var j = table.row(tr).data();
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
    
    $modal.find('[name=tanggal]').val('{{$defaultDateInput}}').change();
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
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        let dateawalParam = urlParams.get('dateawal');
        if(!dateawalParam){
            window.location.replace(location.pathname.split( '?' )[0]+"?"+"dateawal="+dateawal);
        }else if(dateawal != dateawalParam){
            localStorage.setItem('dateawal', dateawalParam);
            dateawal =dateawalParam;
        }
    }else{
        dateawal = @json($dateawal);
        localStorage.setItem('dateawal', dateawal);
    }
    window.history.replaceState('', '', location.pathname.split( '?' )[0]+"?"+"dateawal="+dateawal);
    // END of KONTROL STATE DATE FILTER

    my.initFormExtendedDatetimepickers({{$year}});
    if ($('.slider').length != 0) {
        md.initSliders();
    }
    var isInitialLoad = true; // Flag to check if it's the initial load
    table = $('#datatables').DataTable({
        responsive:{
            details: false
        },
        "processing": true,
        "serverSide": true,
        "language" : {
            "infoFiltered": "",
        },
        "ajax": {
            "url": "{{ route('jurnal.data', ['tipe'=>$currentTipe->tipe]) }}",
            "type": "POST",
            "data": {
                _token: "{{ csrf_token() }}"
            }
        },
        "columns": [
            { "data": null, "render": function(data, type, row) {
                return `<button class="btn btn-success btn-round btn-fab btn-sm mr-1">
                    <i class="material-icons">add</i>
                </button>`;
            }},
            { "data": "tanggal_formatted" },
            { "data": "no-ref" },
            { "data": "keterangan" },
            { "data": "akun_debit" },
            { "data": "debit", "render": function(data, type, row) {
                return formatCurrency(roundNum(data));
            }},
            { "data": "akun_kredit" },
            { "data": "kredit", "render": function(data, type, row) {
                return formatCurrency(roundNum(data));
            }},
            { 
                "data": "validasi",
                "orderable": true,
                "searchable": false,
                "render": function(data, type, row) {
                    return (data == 1) ? '<i class="material-icons">task_alt</i>' : '';
                }
            },
            { 
                "data": null,
                "orderable": false,
                "searchable": false,
                "render": function(data, type, row) {
                    if (row['validasi'] == 1) {
                        return '<a href="#" class="btn btn-link text-dark btn-just-icon disabled"><i class="material-icons">lock</i></a>'
                    } else if (role.includes('Supervisor') && row['validasi'] == 1) {
                        return '<a href="#" class="btn btn-link text-warning btn-just-icon unvalidasi" data-toggle="modal" data-target="#modalValidasi" data-id="' + data['id'] + '"><i class="material-icons">lock_open</i></a>'
                    } else if (!role.includes('Supervisor') && row['validasi'] == 1) {
                        return '<a href="#" class="btn btn-link text-dark btn-just-icon disabled" data-toggle="modal" data-target="#modalValidasi"><i class="material-icons">lock</i></a>'
                    } else if (role.includes('Supervisor')) {
                        return `<div class="form-check">
                          <label class="form-check-label" style="padding-right:5px;">
                            <input class="form-check-input sub_chk" type="checkbox" data-id="${row['id']}">
                            <span class="form-check-sign"><span class="check"></span></span>
                          </label>
                        </div>`;
                    } else {
                        if(!(['Supervisor', 'Spesial']).filter(x => role.includes(x)) || !byroles.includes(row['by-role'])) {
                            return '<button class="btn btn-link text-dark btn-just-icon disabled"><i class="material-icons">block</i></button>'
                        } else {
                            return `<button data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-link btn-warning btn-just-icon edit btn-sm" onclick="onEdit(this)"><i class="material-icons">edit</i></button>
                            <button data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-link btn-danger btn-just-icon remove btn-sm" onclick="onDelete(this)"><i class="material-icons">delete</i></button>
                            <button data-toggle="tooltip" data-placement="left" title="Duplicate" class="btn btn-link btn-danger btn-just-icon duplicate btn-sm" onclick="onDuplicate(this)"><i class="material-icons">content_copy</i></button>`;
                        }
                    }
                }
            },
        ],
        "lengthMenu": [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "All"]
        ],
        iDisplayLength: 50,
        columnDefs: [
            {   
                "orderable": false,
                "targets": 0,
                "className": "dt-control",
                "defaultContent": ''
            },
            { "targets": [8], "className": "text-center" },
            { "targets": [4, 5, 6, 7, 9], "className": "text-right" } // Right align the last column
        ],
        searchDelay: 500, // Delay in milliseconds
        stateSave: true,
        stateSaveCallback: function(settings, data) {
            data['page'] = table.page.info().page;
            if (isInitialLoad) return;
            // console.log("save ", isInitialLoad, data['page']);
            localStorage.setItem('DT_jurnal', JSON.stringify(data));
        },
        stateLoadCallback: function(settings) {
            // return JSON.parse(localStorage.getItem('DT_jurnal'));
            return JSON.parse(localStorage.getItem('DT_jurnal'));
        },
        initComplete: function (settings, json) {
            if (localStorage.getItem('DT_jurnal') == null) {
                isInitialLoad = false;
                return;
            }
            data = JSON.parse(localStorage.getItem('DT_jurnal'));
            // console.log("complete", data['page']);
            if (data['page'] != null && data['page'] != 0) {
                // console.log("set");
                this.fnPageChange(data['page'], true);
                isInitialLoad = false;
            } else {
                isInitialLoad = false;
            }
        }
    });
    function format (d) {
        return '<table class="table table-no-style"><tbody>'+
            '<tr><td width="15%"><b>'+'Tanggal'+'</b></td><td>'+d['tanggal_formatted']+'</td></tr>'+
            '<tr><td><b>'+'Keterangan'+'</b></td><td>'+d['keterangan']+'</td></tr>'+
            '<tr><td><b>'+'Akun Debit'+'</b></td><td>'+d['akun_debit']+'</td></tr>'+
            '<tr><td><b>'+'Debit'+'</b></td><td>'+formatCurrency(roundNum(d['debit']))+'</td></tr>'+
            '<tr><td><b>'+'Akun Kredit'+'</b></td><td>'+d['akun_kredit']+'</td></tr>'+
            '<tr><td><b>'+'Kredit'+'</b></td><td>'+formatCurrency(roundNum(d['kredit']))+'</td></tr>'+
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
    @if($byrole)
    $('#selectrole').selectpicker('val', @json($byrole));
    @else
    $('#selectrole').selectpicker('val', 'Reguler');
    @endif
} );
</script>
<script type="text/javascript">
        
    var allVals = [];

    $('.validasi').on('click', function(e) {
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