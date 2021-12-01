@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Neraca
@endsection

@section('neracaShow')
show
@endsection

@section('neraca'.$currentTipe->tipe)
active
@endsection

@section('modal')

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
            <h4 class="card-title">Neraca {{ucwords($currentTipe->tipe)}}</h4>
        </div>
        <div class="card-body">
            <div class="toolbar">
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="form-group d-inline-block">
                            <input type="text" class="form-control monthyearpicker" value="{{$date}}">
                        </div>
                        <button type="button" class="btn btn-dark btn-round btn-sm"><i class="material-icons" >filter_alt</i> Filter</button>
                    </div>
                    <div class="col-6 text-right">
                        <button type="button" class="btn btn-success btn-sm">Download</button>
                    </div>
                </div>
            </div>
            <!-- <ul class="nav mb-4 justify-content-center" role="tablist">
                <li class="nav-item btn-group mb-0 ">
                    <a class="active btn btn-outline-info" data-toggle="tab" href="#debit-container" role="tablist">
                    Aset
                    </a>
                    <a href="#" class="btn" hidden></a>
                </li>
                <li class="nav-item btn-group mb-0 ">
                    <a href="#" class="btn" hidden></a>
                    <a class="btn btn-outline-info" data-toggle="tab" href="#kredit-container" role="tablist">
                    Kewajiban
                    </a>
                </li>
            </ul> -->
            <ul class="nav nav-pills nav-pills-info justify-content-center" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#debit-container" role="tablist">
                    Aset
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#kredit-container" role="tablist">
                    Kewajiban
                    </a>
                </li>
            </ul>
            <div class="tab-content tab-space">
                <div id="debit-container" class="tab-pane active" >
                    <div class="material-datatables" >
                    <table class="table table-no-bordered table-hover height-50" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                        <tr>
                            <th width="1">
                                <div class="form-check ml-auto mr-auto" style="width: 20px;">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox" value="">
                                    <span class="form-check-sign">
                                    <span class="check"></span>
                                    </span>
                                </label>
                                </div>
                            </th>
                            <th>Keterangan</th>
                            <th class="text-right" width="15%">Awal Periode</th>
                            <th class="text-right" width="15%">Periode Berjalan</th>
                            <th class="text-right" width="15%">Akhir Periode</th>
                        </tr>
                        </thead>
                        @php
                        $total_saldo_berjalan=0;
                        $total_saldo_awal=0;
                        @endphp
                        @foreach($kategoris_debit as $k => $kd)
                        @if($kd->getAkun->isEmpty() === false and $kd->getAkun[0]->{'id-tipe'}===$currentTipe->id)
                        @php
                        $saldo_berjalan=0;
                        $saldo_awal=0;
                        @endphp
                        <tbody>
                        <tr>
                            <td><button class="btn btn-success btn-round btn-fab btn-sm mr-1 my-button-toggle"
                                data-toggle="collapse" data-target="#collapseDebit{{$k}}" aria-expanded="false" aria-controls="collapseDebit{{$k}}" >
                                <i class="material-icons">add</i>
                                </button></td>
                            <td colspan="4"><b>{{$kd->kategori}}</b></td>
                            <!-- <td class="text-right text-success"><b>Rp00<b></td>
                            <td class="text-right"><b>Rp{{ number_format($saldo_berjalan,2) }}<b></td>
                            <td class="text-right"><b>Rp00<b></td> -->
                        </tr>
                        </tbody>
                        <tbody class="no-anim collapse" id="collapseDebit{{$k}}">
                        @foreach($kd->getAkun as $akun)
                        @php
                        $debit=$jurnal_debit->has($akun->id) ? $jurnal_debit[$akun->id]->debit : 0;
                        $kredit=$jurnal_kredit->has($akun->id) ? $jurnal_kredit[$akun->id]->kredit : 0;
                        $cur=$debit-$kredit;
                        $awal=$saldos->has($akun->id) ? $saldos[$akun->id]->saldo : 0;
                        $saldo_awal+=$awal;
                        $saldo_berjalan+=$cur;
                        @endphp
                        <tr>
                            <td></td>
                            <td>{{ $akun->{'nama-akun'} }}</td>
                            <td class="text-right">Rp {{ number_format($awal,2) }}</td>
                            <td class="text-right {{ $cur<0 ? 'text-danger':'text-success' }} ">Rp {{ number_format($cur,2) }}</td>
                            <td class="text-right">Rp {{ number_format($cur+$awal,2) }}</td>
                        </tr>     
                        @endforeach
                        </tbody>
                        <tbody>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset Lancar</td>
                            <td class="text-right">Rp {{ number_format($saldo_awal,2) }}</td>
                            <td class="text-right">Rp {{ number_format($saldo_berjalan,2) }}</td>
                            <td class="text-right">Rp {{ number_format($saldo_awal+$saldo_berjalan,2) }}</td>
                        </tr>   
                        </tbody>
                        @php
                        $total_saldo_berjalan+=$saldo_berjalan;
                        $total_saldo_awal+=saldo_awal;
                        @endphp
                        @endif
                        @endforeach
                        <tfoot>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_awal,2) }}</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_berjalan,2) }}</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_awal+$total_saldo_berjalan,2) }}</td>
                        </tr>   
                        </tfoot>
                    </table>
                    </div>
                </div>
                <div id="kredit-container" class="tab-pane" >
                    <div class="material-datatables" >
                    <table class="table table-no-bordered table-hover height-50" cellspacing="0" width="100%" style="width:100%">
                        <thead>
                        <tr>
                            <th width="1"></th>
                            <th>Keterangan</th>
                            <th class="text-right" width="15%">Awal Periode</th>
                            <th class="text-right" width="15%">Periode Berjalan</th>
                            <th class="text-right" width="15%">Akhir Periode</th>
                        </tr>
                        </thead>
                        @php
                        $total_saldo_berjalan=0;
                        $total_saldo_awal=0;
                        @endphp
                        @foreach($kategoris_kredit as $k => $kd)
                        @if($kd->getAkun->isEmpty() === false and $kd->getAkun[0]->{'id-tipe'}===$currentTipe->id)
                        @php
                        $saldo_berjalan=0;
                        $saldo_awal=0;
                        @endphp
                        <tbody>
                        <tr>
                            <td><button class="btn btn-success btn-round btn-fab btn-sm mr-1 my-button-toggle"
                                data-toggle="collapse" data-target="#collapseKredit{{$k}}" aria-expanded="false" aria-controls="collapseKredit{{$k}}" >
                                <i class="material-icons">add</i>
                                </button></td>
                            <td colspan="4"><b>{{$kd->kategori}}</b></td>
                            <!-- <td class="text-right text-success"><b>Rp00<b></td>
                            <td class="text-right"><b>Rp{{ number_format($saldo_berjalan,2) }}<b></td>
                            <td class="text-right"><b>Rp00<b></td> -->
                        </tr>
                        </tbody>
                        <tbody class="no-anim collapse" id="collapseKredit{{$k}}">
                        @foreach($kd->getAkun as $akun)
                        @php
                        $debit=$jurnal_debit->has($akun->id) ? $jurnal_debit[$akun->id]->debit : 0;
                        $kredit=$jurnal_kredit->has($akun->id) ? $jurnal_kredit[$akun->id]->kredit : 0;
                        $cur=$kredit-$debit;
                        $awal=$saldos->has($akun->id) ? $saldos[$akun->id]->saldo : 0;
                        $saldo_awal+=$awal;
                        $saldo_berjalan+=$cur;
                        @endphp
                        <tr>
                            <td></td>
                            <td>{{ $akun->{'nama-akun'} }}</td>
                            <td class="text-right">Rp {{ number_format($awal,2) }}</td>
                            <td class="text-right {{ $cur<0 ? 'text-danger':'text-success' }}">Rp {{ number_format($cur,2) }}</td>
                            <td class="text-right">Rp {{ number_format($cur+$awal,2) }}</td>
                        </tr>     
                        @endforeach
                        </tbody>
                        <tbody>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset Lancar</td>
                            <td class="text-right">Rp {{ number_format($saldo_awal,2) }}</td>
                            <td class="text-right">Rp {{ number_format($saldo_berjalan,2) }}</td>
                            <td class="text-right">Rp {{ number_format($saldo_awal+$saldo_berjalan,2) }}</td>
                        </tr>   
                        </tbody>
                        @php
                        $total_saldo_berjalan+=$saldo_berjalan;
                        $total_saldo_awal+=saldo_awal;
                        @endphp
                        @endif
                        @endforeach
                        <tfoot>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_awal,2) }}</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_berjalan,2) }}</td>
                            <td class="text-right">Rp {{ number_format($total_saldo_awal+$total_saldo_berjalan,2) }}</td>
                        </tr>   
                        </tfoot>
                    </table>
                    </div>
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

    // ganti warna toggle
    $('.my-button-toggle').click(function(e){
        const btn = $(e.currentTarget);
        if(btn.attr('aria-expanded')==='false'){
            btn.addClass('btn-danger');
            btn.removeClass('btn-success');
            btn.find('.material-icons').text('remove');
        }else{
            btn.addClass('btn-success');
            btn.removeClass('btn-danger');
            btn.find('.material-icons').text('add');
        }
    })
} );
</script>
@endsection