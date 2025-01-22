@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Visibilitas Neraca
@endsection

@section('visibilitasAkunNeracaStatus')
active
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
    <div class="col-md-12">
        <div class="card">
        <div class="card-body">
        <form method="GET" action="{{url($year . '/visibilitas')}}" >
            <div class="toolbar row">
                <div class="col">
                    <div class="form-group row">
                        <div class="col-md-6" style="padding-left:0;">
                            <select id="tipe" name="tipe" class="selectpicker" data-size="7" data-style="btn btn-dark btn-round" title="Pilih Unit" required>
                                @foreach($types as $unit)
                                <option value="{{$unit->id}}" @if($unit->id == $idTipe) selected @endif>
                                    {{$unit->{'tipe'} }}
                                </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary btn-round" formaction="#"><i class="material-icons">filter_alt</i> Proses</button>
                        </div>
                    </div>
                    </form>
                </div>
                @if ($aset && $beban)
                <div class="col-2 text-right">
                    <button type="button" class="btn btn-primary" formaction="{{url($year . '/visibilitas')}}" id="simpan">Simpan</button>
                </div>
                @endif
            </div>
            </form>
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="material-icons">close</i>
                </button>
                <span><b> Info - </b> Silahkan Centang <b>[V]</b> untuk Mengatur Visibilitas Akun di Neraca</span>
            </div>
            <form method="POST" action="{{url($year . '/visibilitas')}}" id="simpanform">
            @csrf
            @if($idTipe)
            <input type="hidden" name="idtipe" value="{{$idTipe}}">
            @endif
            <div class="row">
            <div class="col-md-6">
            @if($aset)
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead class="thead-dark">
                <tr>
                    <th colspan="2">Aset</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($aset as $a)
                    <tr>
                        <td colspan="2"><b>{{$a['name']}}</b></td>
                    </tr>
                    @foreach($a['data'] as $aa)
                    <tr>
                        <td width="1px">
                            <div class="form-check">
                                <label class="form-check-label px-0" style="width:20px;">
                                    <input class="form-check-input" type="checkbox" name="v[{{$aa['id']}}]" value="1" @if($aa['show']) checked="" @endif>
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>{{$aa['name']}}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
            </div>
            <div class="col-md-6">
            @if($beban)
            <div class="material-datatables">
            <table id="datatables" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%" style="width:100%">
                <thead class="thead-dark">
                <tr>
                    <th colspan="2">Kewajiban</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($beban as $a)
                    <tr>
                        <td colspan="2"><b>{{$a['name']}}</b></td>
                    </tr>
                    @foreach($a['data'] as $aa)
                    <tr>
                        <td width="1px">
                            <div class="form-check">
                                <label class="form-check-label px-0" style="width:20px;">
                                    <input class="form-check-input" type="checkbox" name="v[{{$aa['id']}}]" value="1" @if($aa['show']) checked="" @endif>
                                    <span class="form-check-sign">
                                        <span class="check"></span>
                                    </span>
                                </label>
                            </div>
                        </td>
                        <td>{{$aa['name']}}</td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
            </div>
            @endif
            </div>
            </div>
            </form>
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
    $('#simpan').on('click', function(e) {
        $('#simpanform').trigger('submit');
    });
});
</script>
@endsection