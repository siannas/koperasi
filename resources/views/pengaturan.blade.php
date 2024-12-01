@extends('layouts.layout')
@extends('layouts.sidebar')

@php
$role = Auth::user()->role;
$role = explode(', ', $role);
@endphp

@section('title')
Pengaturan
@endsection

@section('pengaturanStatus')
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
                <i class="material-icons">settings</i>
            </div>
            <h4 class="card-title">Pengaturan</h4>
        </div>
        <div class="card-body">
            <div class="row mt-4">
                <div class="col-md-2">
                    <ul class="nav nav-pills nav-pills-primary flex-column" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kunci-tab" role="tablist">
                        Kunci Bulan
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#link5" role="tablist">
                        Settings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#link6" role="tablist">
                        Options
                        </a>
                    </li> -->
                    </ul>
                </div>
                <div class="col-md-10">
                    <div class="tab-content">
                    <div class="tab-pane active" id="kunci-tab">
                        <form action="{{route('pengaturan.update.datelock')}}" method="post">
                            @csrf
                            @method('PUT')
                        <div class="d-table-row">
                            <div class="d-table-cell" style="width:100px;">
                                <label for="datelock" >Bulan</label>
                            </div>
                            <div class="d-table-cell">
                                <input name="datelock" id="datelock" type="text" class="form-control monthyearpicker" placeholder="PILIH BULAN"  value="{{ isset($datelock) ? Carbon\Carbon::parse($datelock)->format('m/Y') : '' }}">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary float-right">Simpan</button>
                        </div>
                        </form>
                    </div>
                    <!-- <div class="tab-pane" id="link5">
                        Efficiently unleash cross-media information without cross-media value. Quickly maximize timely deliverables for real-time schemas.
                        <br>
                        <br>Dramatically maintain clicks-and-mortar solutions without functional solutions.
                    </div>
                    <div class="tab-pane" id="link6">
                        Completely synergize resource taxing relationships via premier niche markets. Professionally cultivate one-to-one customer service with robust ideas.
                        <br>
                        <br>Dynamically innovate resource-leveling customer service for state of the art customer service.
                    </div> -->
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
<!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
<script src="{{asset('js/plugins/bootstrap-tagsinput.js')}}"></script>
<script>
var table;

$(document).ready(function() {
    my.initFormExtendedDatetimepickers({{$year}});
} );
</script>
@endsection