@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Jurnal
@endsection

@section('content')
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
        <div class="modal-body">
            <form class="form-horizontal">
                <div class="form-group">
                    <input type="text" class="form-control datepicker" id="date">
                </div>
                <div class="form-group">
                    <label for="keterangan" class="bmd-label-floating">Keterangan</label>
                    <input type="text" class="form-control" id="keterangan">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-link text-primary">Simpan</button>
            <button type="button" class="btn btn-danger btn-link" data-dismiss="modal">Tutup</button>
        </div>
        </div>
    </div>
</div>
<!--  End Modal -->
<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-header card-header-primary card-header-icon">
            <div class="card-header card-header-primary card-header-icon">
                <div class="card-icon">
                <i class="material-icons">assignment</i>
                </div>
                <h4 class="card-title">Jurnal</h4>
            </div>
        </div>
        <div class="card-body">
            <div class="toolbar text-right">
                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#modalTambah" onclick="document.getElementById('date').value = my.getDate()">Tambah</button>
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
                <tr>
                    <td class="dt-control">
                        <button class="btn btn-success btn-round btn-fab btn-sm mr-1">
                        <i class="material-icons">add</i>
                        </button>
                    </td>
                    <td>21-Jan-20</td>
                    <td>Penjualan Tunai</td>
                    <td>1-11110</td>
                    <td>20000</td>
                    <td>2-91010</td>
                    <td>7200</td>
                    <td class="text-right">
                    <a href="#" class="btn btn-link btn-warning btn-just-icon edit"><i class="material-icons">dvr</i></a>
                    <a href="#" class="btn btn-link btn-danger btn-just-icon remove"><i class="material-icons">close</i></a>
                    </td>
                    <td>Kas</td>
                    <td>Pendapatan Fotocopy</td>
                </tr>
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
    my.initFormExtendedDatetimepickers();
    if ($('.slider').length != 0) {
        md.initSliders();
    }

    const table = $('#datatables').DataTable({
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
            '<tr><td><b>'+'Akun Debit'+'</b></td><td>'+d[3]+' '+d[8]+'</td></tr>'+
            '<tr><td><b>'+'Debit'+'</b></td><td>'+d[4]+'</td></tr>'+
            '<tr><td><b>'+'Akun Kredit'+'</b></td><td>'+d[5]+' '+d[9]+'</td></tr>'+
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

    // Add event listener for opening and closing details
    // $('#datatables tbody').on('click', 'td.dt-control', function () {
    //     var tr = $(this).closest('tr');
    //     var row = table.row( tr );
    //     console.log(row);
 
    //     if ( row.child.isShown() ) {
    //         // This row is already open - close it
    //         row.child.hide();
    //         tr.removeClass('shown');
    //     }
    //     else {
    //         // Open this row
    //         row.child( format(row.data()) ).show();
    //         tr.addClass('shown');
    //     }
    // } );
} );
</script>
@endsection