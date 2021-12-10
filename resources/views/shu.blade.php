@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Selisih Hasil Usaha
@endsection

@section('shuShow')
show
@endsection

@section('shu'.$currentTipe->tipe)
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
            <h4 class="card-title">SHU {{ucwords($currentTipe->tipe)}}</h4>
        </div>
        <div class="card-body">
            <div class="toolbar mb-5">
                <div class="row ">
                    <div class="col-6">
                        <form action="{{route('shu.filter', ['tipe' => $currentTipe->tipe])}}" method="POST">
                        @csrf
                        <div class="form-group d-inline-block">
                            <input name="date" type="text" class="form-control monthyearpicker" value="{{$date}}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-round"><i class="material-icons">filter_alt</i> Proses</button>
                        </form>
                    </div>
                    <div class="col-6 text-right">
                        <form action="{{route('shu.excel', ['tipe' => $currentTipe->tipe])}}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Download</button>
                        </form>
                    </div>
                </div>
            </div>
           
            <div id="shu-container" >
                <div class="material-datatables" >
                <table class="table table-no-bordered table-hover dataTable" cellspacing="0" width="100%" style="width:100%">
                    <thead>
                    <tr>
                        <th width="1"></th>
                        <th>Keterangan</th>
                        <th class="text-right" width="15%">Awal Periode</th>
                        <th class="text-right" width="15%">Periode Berjalan</th>
                        <th class="text-right" width="15%">Akhir Periode</th>
                        <th class="text-right" width="15%">Koreksi</th>
                        <th class="text-right" width="15%">Fiskal</th>
                    </tr>
                    </thead>
                    @php
                    $master=[];
                    @endphp
                    @foreach($kategoris as $k => $kd)
                    @if($kd->getAkun->isEmpty() === false and intval($kd->getAkun[0]->{'id-tipe'})===$currentTipe->id)
                    @php
                    $saldo_berjalan=0;
                    $saldo_awal=0;
                    $coef = ($kd->{'tipe-pendapatan'} ==='kredit') ? -1 : 1;
                    @endphp
                    <tbody>
                    <tr>
                        <td><button class="btn btn-success btn-round btn-fab btn-sm mr-1 my-button-toggle"
                            data-toggle="collapse" data-target="#collapse{{$k}}" aria-expanded="false" aria-controls="collapse{{$k}}" >
                            <i class="material-icons">add</i>
                            </button></td>
                        <td colspan="6"><b>{{$kd->kategori}}</b></td>
                    </tr>
                    </tbody>
                    <tbody class="no-anim collapse" id="collapse{{$k}}">
                    @foreach($kd->getAkun as $akun)
                    @php
                    $debit=$jurnal_debit->has($akun->id) ? $jurnal_debit[$akun->id]->debit : 0;
                    $kredit=$jurnal_kredit->has($akun->id) ? $jurnal_kredit[$akun->id]->kredit : 0;
                    $cur=$coef*($debit-$kredit);
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
                        <td></td>
                        <td></td>
                    </tr>     
                    @endforeach
                    </tbody>
                    <tbody>
                    <tr class="table-info">
                        <td class="text-right" colspan="2">Jumlah {{$kd->kategori}} </td>
                        <td class="text-right">Rp {{ number_format($saldo_awal,2) }}</td>
                        <td class="text-right">Rp {{ number_format($saldo_berjalan,2) }}</td>
                        <td class="text-right">Rp {{ number_format($saldo_awal+$saldo_berjalan,2) }}</td>
                        <td></td>
                        <td></td>
                    </tr>   
                    </tbody>
                    @php
                    $master[$kd->id]=[
                        'awal'=>$saldo_awal,
                        'berjalan'=>$saldo_berjalan,
                    ];
                    @endphp
                    @endif
                    @endforeach
                    @php
                    @endphp
                    <tfoot>
                    @foreach($meta as $i=>$m)
                    @php
                    $title=ucwords(str_replace("_"," ", substr($m->key, $metaKeyLen) ));
                    $res=\App\Http\Controllers\SHUController::calculate($master, $m->value);
                    @endphp
                    <tr class="bg-dark text-white">
                        <th class="text-right" colspan="2" >Jumlah {{$title}}</th>
                        <th class="text-right">Rp {{ number_format($res[0], 2) }}</th>
                        <th class="text-right">Rp {{ number_format($res[1], 2) }}</th>
                        <th class="text-right">Rp {{ number_format($res[2], 2) }}</th>
                        <th class="text-right"></th>
                        <th class="text-right"></th>
                    </tr>  
                    @endforeach
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