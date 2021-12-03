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
                <h3 class="card-title">184</h3>
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
                <h3 class="card-title">521</h3>
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
                <h3 class="card-title">245</h3>
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
                    <h4 class="card-title">5 Akun Teratas Bulan Lalu</h4>
                </div>
                <div class="card-body">
                
                    <div class="table-responsive table-sales">
                        <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/US.png')}}"> </div>
                                </td>
                                <td>USA</td>
                                <td class="text-right">2.920</td>
                                <td class="text-right">53.23%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/DE.png')}}"> </div>
                                </td>
                                <td>Germany</td>
                                <td class="text-right">1.300</td>
                                <td class="text-right">20.43%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/AU.png')}}"> </div>
                                </td>
                                <td>Australia</td>
                                <td class="text-right">760</td>
                                <td class="text-right">10.35%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/GB.png')}}"> </div>
                                </td>
                                <td>United Kingdom</td>
                                <td class="text-right">690</td>
                                <td class="text-right">7.87%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/RO.png')}}"> </div>
                                </td>
                                <td>Romania</td>
                                <td class="text-right">600</td>
                                <td class="text-right">5.94%</td>
                            </tr>
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
                    <h4 class="card-title">5 Akun Teratas Bulan Ini</h4>
                </div>
                <div class="card-body">
                
                    <div class="table-responsive table-sales">
                        <table class="table">
                        <tbody>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/US.png')}}"> </div>
                                </td>
                                <td>USA</td>
                                <td class="text-right">2.920</td>
                                <td class="text-right">53.23%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/DE.png')}}"> </div>
                                </td>
                                <td>Germany</td>
                                <td class="text-right">1.300</td>
                                <td class="text-right">20.43%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/AU.png')}}"> </div>
                                </td>
                                <td>Australia</td>
                                <td class="text-right">760</td>
                                <td class="text-right">10.35%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/GB.png')}}"> </div>
                                </td>
                                <td>United Kingdom</td>
                                <td class="text-right">690</td>
                                <td class="text-right">7.87%</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="flag">
                                    <img src="{{asset('public/img/flags/RO.png')}}"> </div>
                                </td>
                                <td>Romania</td>
                                <td class="text-right">600</td>
                                <td class="text-right">5.94%</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <button type="button" class="btn btn-round btn-default dropdown-toggle btn-link" data-toggle="dropdown">
    7 days
    </button> -->
    <div class="row">
        <div class="col-md-4">
        <div class="card card-chart">
            <div class="card-header card-header-rose" data-header-animation="true">
            <div class="ct-chart" id="websiteViewsChart"></div>
            </div>
            <div class="card-body">
            <div class="card-actions">
                <button type="button" class="btn btn-danger btn-link fix-broken-card">
                <i class="material-icons">build</i> Fix Header!
                </button>
                <button type="button" class="btn btn-info btn-link" rel="tooltip" data-placement="bottom" title="Refresh">
                <i class="material-icons">refresh</i>
                </button>
                <button type="button" class="btn btn-default btn-link" rel="tooltip" data-placement="bottom" title="Change Date">
                <i class="material-icons">edit</i>
                </button>
            </div>
            <h4 class="card-title">Website Views</h4>
            <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
            <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
            </div>
            </div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="card card-chart">
            <div class="card-header card-header-success" data-header-animation="true">
            <div class="ct-chart" id="dailySalesChart"></div>
            </div>
            <div class="card-body">
            <div class="card-actions">
                <button type="button" class="btn btn-danger btn-link fix-broken-card">
                <i class="material-icons">build</i> Fix Header!
                </button>
                <button type="button" class="btn btn-info btn-link" rel="tooltip" data-placement="bottom" title="Refresh">
                <i class="material-icons">refresh</i>
                </button>
                <button type="button" class="btn btn-default btn-link" rel="tooltip" data-placement="bottom" title="Change Date">
                <i class="material-icons">edit</i>
                </button>
            </div>
            <h4 class="card-title">Daily Sales</h4>
            <p class="card-category">
                <span class="text-success"><i class="fa fa-long-arrow-up"></i> 55% </span> increase in today sales.</p>
            </div>
            <div class="card-footer">
            <div class="stats">
                <i class="material-icons">access_time</i> updated 4 minutes ago
            </div>
            </div>
        </div>
        </div>
        <div class="col-md-4">
        <div class="card card-chart">
            <div class="card-header card-header-info" data-header-animation="true">
            <div class="ct-chart" id="completedTasksChart"></div>
            </div>
            <div class="card-body">
            <div class="card-actions">
                <button type="button" class="btn btn-danger btn-link fix-broken-card">
                <i class="material-icons">build</i> Fix Header!
                </button>
                <button type="button" class="btn btn-info btn-link" rel="tooltip" data-placement="bottom" title="Refresh">
                <i class="material-icons">refresh</i>
                </button>
                <button type="button" class="btn btn-default btn-link" rel="tooltip" data-placement="bottom" title="Change Date">
                <i class="material-icons">edit</i>
                </button>
            </div>
            <h4 class="card-title">Completed Tasks</h4>
            <p class="card-category">Last Campaign Performance</p>
            </div>
            <div class="card-footer">
            <div class="stats">
                <i class="material-icons">access_time</i> campaign sent 2 days ago
            </div>
            </div>
        </div>
        </div>
    </div>
    
</div>
@endsection