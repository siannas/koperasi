@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Buku Besar
@endsection

@section('bukuShow')
show
@endsection

@section('buku'.$currentTipe->tipe)
active
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
            <h4 class="card-title">Buku Besar {{ucwords($currentTipe->tipe)}}</h4>
        </div>
        <div class="card-body">
            <div class="toolbar row">
                <div class="col">
                    <form action="{{url('/'.$currentTipe->tipe.'/buku-besar')}}" method="POST">
                    @csrf
                    <div class="form-group row">
                        <div class="col-md-3" style="padding-right:0;">
                            <div class="form-group">
                                <!-- <label for="bulan" class="bmd-label-floating">PILIH BULAN</label> -->
                                <input id="bulan" name="bulan" type="text" class="form-control monthyearpicker" placeholder="PILIH BULAN" value="{{$bulan}}" required>
                            </div>
                        </div>
                        <div class="col-md-6" style="padding-left:0;">
                            <select id="akun" name="akun" class="selectpicker" data-size="7" data-style="btn btn-dark btn-round" title="Pilih Akun" required>
                                @foreach($akun as $unit)
                                @if($unit->getKategori->parent!=17)
                                <option value="{{$unit->id}}" @if($unit->id==$curAkun->id) selected @endif>
                                    {{$unit->{'no-akun'} }} - {{$unit->{'nama-akun'} }}
                                </option>
                                @endif
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-round"><i class="material-icons">filter_alt</i> Proses</button>
                        </div>
                    </div>
                    </form>
                </div>
                <div class="col-2 text-right">
                    <form action="{{url($currentTipe->tipe.'/buku-besar/excel')}}" method="post">@csrf <button class="btn btn-sm btn-success">Download</button></form>
                </div>
            </div>
            <div class="material-datatables">
                <div style="overflow-x:scroll;">
            <table id="datatables" class="table table-striped table-no-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead>
                <tr>
                    <th data-priority="1" width="1" class="disabled-sorting"></th>
                    <th data-priority="1">Tanggal</th>
                    <th data-priority="3">Uraian Transaksi</th>
                    <th data-priority="2" class="text-right">Debit</th>
                    <th data-priority="2" class="text-right">Kredit</th>
                    <th data-priority="1" class="text-right disabled-sorting">Saldo</th>
                </tr>
                </thead>
                <tbody>
                @php 
                
                $jumlah = $saldoAwal->saldo;
                @endphp
                <tr class="bg-dark text-white">
                    <th></th>
                    <th>Saldo Awal</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th class="text-right">Rp {{number_format($saldoAwal->saldo,2)}} </th>
                </tr>
                @foreach($jurnal as $unit)
                <tr>
                    <td></td>
                    <td>{{date_format(date_create($unit->tanggal), "d-m-Y")}}</td>
                    <td>{{$unit->keterangan}}</td>
                    <td class="text-right">
                        @if($unit->{'id-debit'}==$curAkun->id) 
                        {{number_format($unit->debit,2)}}
                        @php
                        if($curAkun->getKategori->{'tipe-pendapatan'} == 'debit'){
                            $jumlah += intval($unit->debit);
                        }
                        elseif($curAkun->getKategori->{'tipe-pendapatan'} == 'kredit'){
                            $jumlah -= intval($unit->debit);
                        }
                        @endphp
                        @else - 
                        @endif</td>
                    <td class="text-right">
                        @if($unit->{'id-kredit'}==$curAkun->id)
                        {{number_format($unit->kredit,2)}}
                        @php
                        if($curAkun->getKategori->{'tipe-pendapatan'} == 'debit'){
                            $jumlah -= intval($unit->kredit);
                        }
                        elseif($curAkun->getKategori->{'tipe-pendapatan'} == 'kredit'){
                            $jumlah += intval($unit->kredit);
                        }
                        @endphp
                        @else - 
                        @endif</td>
                    <td class="text-right">
                        @php
                        echo number_format($jumlah,2);
                        @endphp</td>
                </tr>
                @endforeach
                </tbody>
                <tfoot>
                <tr class="bg-dark text-white">
                    <th></th>
                    <th colspan="4">Saldo Akhir</th>
                    <th class="text-right">Rp {{number_format($jumlah,2)}}</th>
                </tr>
                </tfoot>
            </table>
            </div>
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
            details: true
        },
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        columnDefs: [
            {   
                class: "details-control",
                orderable: false,
                targets: 0
            },
            { "visible": true, "targets": [8,9] }
        ]
    });

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
    
} );
</script>
@endsection