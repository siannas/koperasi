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
                            <input type="text" class="form-control datepicker" value="10/06/2018">
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
                        @for($i=4;$i<6;$i++)
                        <tbody>
                        <tr>
                            <td><button class="btn btn-success btn-round btn-fab btn-sm mr-1 my-button-toggle"
                                data-toggle="collapse" data-target="#collapseExample{{$i}}" aria-expanded="false" aria-controls="collapseExample{{$i}}" >
                                <i class="material-icons">add</i>
                                </button></td>
                            <td ><b>Kas</b></td>
                            <td class="text-right text-success"><b>Rp2,923,232.00<b></td>
                            <td class="text-right"><b>Rp2,923,232.00<b></td>
                            <td class="text-right"><b>Rp2,923,232.00<b></td>
                        </tr>
                        </tbody>
                        <tbody class="no-anim collapse" id="collapseExample{{$i}}">
                        <tr>
                            <td></td>
                            <td>Kas Pusat</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Kas Pusat</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Kas Pusat</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>             
                        </tbody>
                        <tbody>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset Lancar</td>
                            <td class="text-right">Rp2,923,232.00</td>
                            <td class="text-right">Rp2,923,232.00</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>   
                        </tbody>
                        @endfor
                        <!-- <tfoot>
                        <tr class="text-white bg-secondary text-center">
                            <th colspan="3" class="text-center">TOTAL</th>
                            <th >Rp2,923,232.00</th>
                            <th >Rp2,923,232.00</th>
                            <th >Rp2,923,232.00</th>
                        </tr>
                        </tfoot> -->
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
                        @for($i=0;$i<1;$i++)
                        <tbody>
                        <tr>
                            <td><button class="btn btn-success btn-round btn-fab btn-sm mr-1 my-button-toggle"
                                data-toggle="collapse" data-target="#collapseExample{{$i}}" aria-expanded="false" aria-controls="collapseExample{{$i}}">
                                <i class="material-icons">add</i>
                                </button></td>
                            <td ><b>Kewajiban</b></td>
                            <td class="text-right text-success"><b>Rp2,923,232.00<b></td>
                            <td class="text-right"><b>Rp2,923,232.00<b></td>
                            <td class="text-right"><b>Rp2,923,232.00<b></td>
                        </tr>
                        </tbody>
                        <tbody class="no-anim collapse" id="collapseExample{{$i}}">
                        <tr>
                            <td></td>
                            <td>Kewajiban</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Kewajiban</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Kas Pusat</td>
                            <td class="text-right text-success">Rp2,923,232.00</td>
                            <td class="text-right">-</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>             
                        </tbody>
                        <tbody>
                        <tr class="table-info">
                            <td class="text-right" colspan="2">Jumlah Aset Lancar</td>
                            <td class="text-right">Rp2,923,232.00</td>
                            <td class="text-right">Rp2,923,232.00</td>
                            <td class="text-right">Rp2,923,232.00</td>
                        </tr>   
                        </tbody>
                        @endfor
                        <!-- <tfoot>
                        <tr class="text-white bg-secondary text-center">
                            <th colspan="3" class="text-center">TOTAL</th>
                            <th >Rp2,923,232.00</th>
                            <th >Rp2,923,232.00</th>
                            <th >Rp2,923,232.00</th>
                        </tr>
                        </tfoot> -->
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