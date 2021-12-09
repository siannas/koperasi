@extends('layouts.layout')
@extends('layouts.sidebar')

@section('title')
Dashboard
@endsection

@section('dashboardStatus')
active
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">store</i>
                </div>
                <p class="card-category">Jurnal Toko</p>
                <h3 class="card-title">{{$meta[2]->value}}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Bulan Ini
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-rose card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">payments</i>
                </div>
                <p class="card-category">Jurnal Simpan-Pinjam</p>
                <h3 class="card-title">{{$meta[0]->value}}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Bulan Ini
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
                <div class="card-icon">
                    <i class="material-icons">content_copy</i>
                </div>
                <p class="card-category">Jurnal Fotocopy</p>
                <h3 class="card-title">{{$meta[1]->value}}</h3>
            </div>
            <div class="card-footer">
                <div class="stats">
                    <i class="material-icons">date_range</i> Bulan Ini
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
            <div class="card ">
                <div class="card-header card-header-info card-header-icon">
                    <div class="card-icon"><i class="material-icons">calendar_month</i></div>
                    <h4 class="card-title">5 Akun Teratas Bulan Ini</h4>
                </div>
                <div class="card-body">
                
                    <div class="table-responsive table-sales">
                        <table class="table">
                        <tbody>
                            @php $ah = json_decode($meta[3]->value); @endphp
                            @for($x=0;$x<count($ah);$x++)
                            <tr>
                                <td>{{$ah[$x]->{'no-akun'} }}</td>
                                <td>{{$ah[$x]->{'nama-akun'} }}</td>
                                <td class="text-right">Rp {{number_format($ah[$x]->saldo)}}</td>
                            </tr>
                            @endfor
                            
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card ">
                <div class="card-header card-header-success card-header-icon">
                    <div class="card-icon"><i class="material-icons">calendar_month</i></div>
                    <h4 class="card-title">Aktivitas Terkini</h4>
                </div>
                <div class="card-body">
                
                    <div class="table-responsive table-sales">
                        <table class="table">
                        <tbody>
                        @php $ah = json_decode($meta[4]->value); @endphp
                        @for($x=0;$x<count($ah);$x++)
                            <tr>
                                <td>{{$ah[$x]->created_at}}</td>
                                <td>{!!$ah[$x]->keterangan!!}</td>
                            </tr>
                        @endfor
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection