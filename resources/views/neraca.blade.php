@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Neraca
@endsection

@section('neracaShow')
show
@endsection

@if($currentTipe)
@section('neraca'.$currentTipe->tipe)
active
@endsection
@else
@section('neraca')
active
@endsection
@endif

@section('modal')

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
            @if($currentTipe)
            <h4 class="card-title">Neraca {{ucwords($currentTipe->tipe)}}</h4>
            @else
            <h4 class="card-title">Neraca Gabungan</h4>
            @endif
        </div> -->
        <div class="card-body">
            <div class="toolbar mb-5">
                <div class="row ">
                    <div class="col-6">
                        @if($currentTipe)
                        <form action="{{route('neraca.filter', ['tipe' => $currentTipe->tipe])}}" method="GET">
                        @else
                        <form action="{{route('neraca.filter.gabungan')}}" method="GET">
                        @endif
                        <div class="form-group d-inline-block">
                            <input required name="date_month" autocomplete="off" type="text" class="form-control monthpicker" value="{{$month_literal}}">
                        </div>
                        <!-- <div class="form-group d-inline-block">
                            <input required name="date_year" autocomplete="off" type="text" class="form-control yearpicker" value="{{$year}}">
                        </div> -->
                        <!-- <div class="form-group d-inline-block">
                            <input name="date" type="text" class="form-control monthyearpicker" value="{{$date}}" id="date-filter">
                        </div> -->
                        <button type="submit" class="btn btn-primary btn-round"><i class="material-icons">filter_alt</i> Proses</button>
                        </form>
                    </div>
                    <div class="col-6 text-right">
                        @if($currentTipe)
                        <form class="d-inline-block" action="{{route('neraca.download', ['tipe' => $currentTipe->tipe])}}" method="POST" onsubmit="setFormDate(event)">
                        @else
                        <form class="d-inline-block" action="{{route('neraca.download.gabungan')}}" method="POST" onsubmit="setFormDate(event)">
                        @endif
                        @csrf
                        <input required name="date_month" type="hidden" value="{{$month_literal}}">
                        <input type="hidden" name="date">
                        <button type="submit" class="btn btn-success btn-sm">Download</button>
                        </form>
                        @if($currentTipe)
                        <form target="_blank" class="d-inline-block" action="{{route('neraca.excel', ['tipe' => $currentTipe->tipe])}}" method="GET" onsubmit="setFormDate(event)">
                        @else
                        <form target="_blank" class="d-inline-block" action="{{route('neraca.excel.gabungan')}}" method="GET" onsubmit="setFormDate(event)">
                        @endif
                        @csrf
                        <input required name="date_month" type="hidden" value="{{$month_literal}}">
                        <input type="hidden" name="date">
                        <button type="submit" class="btn btn-primary btn-sm">View Neraca</button>
                        </form>
                    </div>
                </div>
            </div>
            <ul class="nav nav-pills nav-pills-primary justify-content-center" role="tablist">
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
            <div id="statusContainer" class="mt-3">
                
            </div>
            <div class="tab-content tab-space">
                <div id="debit-container" class="tab-pane active" >
                    <div class="material-datatables" >
                    <table class="table table-no-bordered table-hover dataTable" cellspacing="0" width="100%" style="width:100%">
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
                        @foreach($aset as $k => $kd)
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
                                <td colspan="4"><b>{{$kd['name']}}</b></td>
                            </tr>
                            </tbody>
                            <tbody class="no-anim collapse" id="collapseDebit{{$k}}">
                            @foreach($kd['data'] as $akun)
                                @php
                                $saldo_awal += $akun['saldo_awal'];
                                $saldo_berjalan += $akun['saldo'];
                                @endphp
                                <tr>
                                    <td></td>
                                    <td>{{ $akun['name'] }}</td>
                                    <td class="text-right">Rp {{ indo_num_format($akun['saldo_awal'],2) }}</td>
                                    <td class="text-right {{ $akun['saldo']<0 ? 'text-danger':'text-success' }} ">Rp {{ indo_num_format($akun['saldo'],2) }}</td>
                                    <td class="text-right">Rp {{ indo_num_format($akun['saldo_awal']+$akun['saldo'],2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tbody>
                            <tr class="table-info">
                                <td class="text-right" colspan="2">Jumlah {{$kd['name']}} </td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_awal,2) }}</td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_berjalan,2) }}</td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_awal+$saldo_berjalan,2) }}</td>
                            </tr>   
                            </tbody>
                            @php
                            $total_saldo_berjalan+=$saldo_berjalan;
                            $total_saldo_awal+=$saldo_awal;
                            @endphp
                        @endforeach
                        @php
                        $akhir_aset=$total_saldo_awal+$total_saldo_berjalan;
                        @endphp
                        <tfoot>
                        <tr class="bg-dark text-white">
                            <th class="text-right" colspan="2">Jumlah Aset</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_awal,2) }}</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_berjalan,2) }}</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_awal+$total_saldo_berjalan,2) }}</th>
                        </tr>   
                        </tfoot>
                    </table>
                    </div>
                </div>
                <div id="kredit-container" class="tab-pane" >
                <div class="material-datatables" >
                    <table class="table table-no-bordered table-hover dataTable" cellspacing="0" width="100%" style="width:100%">
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
                        @foreach($beban as $k => $kd)
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
                                <td colspan="4"><b>{{$kd['name']}}</b></td>
                            </tr>
                            </tbody>
                            <tbody class="no-anim collapse" id="collapseKredit{{$k}}">
                            @foreach($kd['data'] as $akun)
                                @php
                                $saldo_awal += $akun['saldo_awal'];
                                $saldo_berjalan += $akun['saldo'];
                                @endphp
                                <tr>
                                    <td></td>
                                    <td>{{ $akun['name'] }}</td>
                                    <td class="text-right">Rp {{ indo_num_format($akun['saldo_awal'],2) }}</td>
                                    <td class="text-right {{ $akun['saldo']<0 ? 'text-danger':'text-success' }} ">Rp {{ indo_num_format($akun['saldo'],2) }}</td>
                                    <td class="text-right">Rp {{ indo_num_format($akun['saldo_awal']+$akun['saldo'],2) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tbody>
                            <tr class="table-info">
                                <td class="text-right" colspan="2">Jumlah {{$kd['name']}} </td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_awal,2) }}</td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_berjalan,2) }}</td>
                                <td class="text-right">Rp {{ indo_num_format($saldo_awal+$saldo_berjalan,2) }}</td>
                            </tr>   
                            </tbody>
                            @php
                            $total_saldo_berjalan+=$saldo_berjalan;
                            $total_saldo_awal+=$saldo_awal;
                            @endphp
                        @endforeach
                        @php
                        $akhir_kewajiban=$total_saldo_awal+$total_saldo_berjalan;
                        @endphp
                        <tfoot>
                        <tr class="bg-dark text-white">
                            <th class="text-right" colspan="2">Jumlah Kewajiban</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_awal,2) }}</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_berjalan,2) }}</th>
                            <th class="text-right">Rp {{ indo_num_format($total_saldo_awal+$total_saldo_berjalan,2) }}</th>
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
const statusDanger=`<div class="alert alert-danger mb-0 p-2 ">
<span class="d-inline-block" style="vertical-align:middle;"><i class="material-icons text-white">highlight_off</i></span>
<span class="d-inline-block"><b>  &nbsp Saldo Tidak Seimbang</b></span>
</div>`;
const statusSuccess=`<div class="alert alert-success mb-0 p-2 ">
<span class="d-inline-block" style="vertical-align:middle;"><i class="material-icons text-white">check_circle_outline</i></span>
<span class="d-inline-block"><b>  &nbsp Saldo Seimbang</b></span>
</div>`;

//fungsi nge-set informasi date di dalam form
const setFormDate = function(e){
    var date = $('#date-filter').val();
    $self=$(e.target);
    $self.find('input[name=date]').val(date)
    return true;
}

$(document).ready(function() {
    my.initFormExtendedDatetimepickers({{$year}});
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

    // set alert status neraca
    let asetTotal = @json($akhir_aset);
    let bebanTotal = @json($akhir_kewajiban);

    let saldo_awal_aset_total = @json($saldo_awal_aset_total);
    let saldo_awal_beban_total = @json($saldo_awal_beban_total);

    if((roundNum(asetTotal) - roundNum(bebanTotal)) != (roundNum(saldo_awal_aset_total) - roundNum(saldo_awal_beban_total))){
        $('#statusContainer').html(statusDanger);
    }else{
        $('#statusContainer').html(statusSuccess);
    }
} );
</script>
@endsection