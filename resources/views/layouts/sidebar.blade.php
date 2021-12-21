@php
$role = Auth::user()->role;
$reguler = [];
@endphp
@section('sidebar')
<div class="sidebar" data-color="green" data-background-color="black" data-image="{{asset('public/img/sidebar-1.jpg')}}">
        <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
        -->
    <div class="logo">
        <a href="{{url('/')}}" class="simple-text logo-mini">K</a>
        <a href="{{url('/')}}" class="simple-text logo-normal">Koperasi</a>
    </div>
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="{{asset('public/img/logo.png')}}" />
            </div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>{{ucwords(Auth::user()->nama)}}</span>
                </a>
            </div>
        </div>
    <ul class="nav">
        <li class="nav-item @yield('dashboardStatus') ">
            <a class="nav-link" href="{{url('/')}}">
                <i class="material-icons">dashboard</i>
                <p> Dashboard </p>
            </a>
        </li>
        @if($role=='Admin')
        <li class="nav-item @yield('userStatus')">
            <a class="nav-link" href="{{url('/user')}}">
                <i class="material-icons">people</i>
                <p> User </p>
            </a>
        </li>
        <li class="nav-item @yield('kategoriStatus')">
            <a class="nav-link" href="{{url('/kategori')}}">
                <i class="material-icons">category</i>
                <p> Kategori </p>
            </a>
        </li>
        <li class="nav-item @yield('akunStatus')">
            <a class="nav-link" href="{{url('/akun')}}">
                <i class="material-icons">account_tree</i>
                <p> Akun </p>
            </a>
        </li>
        @endif
        @if($role!='Admin')
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#jurnal">
                <i class="material-icons">account_balance_wallet</i>
                <p> Jurnal
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse @yield('jurnalShow')" id="jurnal">
                <ul class="nav">
                @foreach($tipe as $unit)
                @if($role=='Supervisor' || $role=='Spesial')
                <li class="nav-item @yield('jurnal'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/jurnal')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @elseif($role!='Admin' && preg_match("/".$unit->slug."/i", $role))
                <li class="nav-item @yield('jurnal'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/jurnal')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @endif
                @endforeach
                </ul>
            </div>
        </li>
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#bukuBesar">
                <i class="material-icons">menu_book</i>
                <p> Buku Besar
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse @yield('bukuShow')" id="bukuBesar">
                <ul class="nav">
                @foreach($tipe as $unit)
                @if($role=='Supervisor' || $role=='Spesial')
                <li class="nav-item @yield('buku'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/buku-besar')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @elseif($role!='Admin' && preg_match("/".$unit->slug."/i", $role))
                <li class="nav-item @yield('buku'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/buku-besar')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @endif
                @endforeach
                </ul>
            </div>
        </li>
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#neraca">
                <i class="material-icons">balance</i>
                <p> Neraca
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse @yield('neracaShow')" id="neraca">
                <ul class="nav">
                @foreach($tipe as $unit)
                @if($role=='Supervisor' || $role=='Spesial')
                <li class="nav-item @yield('neraca'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/neraca')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @elseif($role!='Admin' && preg_match("/".$unit->slug."/i", $role))
                <li class="nav-item @yield('neraca'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/neraca')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @endif
                @endforeach
                @if($role=='Spesial')
                <li class="nav-item @yield('neraca')">
                    <a class="nav-link" href="{{url('/neraca')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> Neraca Gabungan </span>
                    </a>
                </li>
                @endif
                </ul>
            </div>
        </li>
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#shu">
                <i class="material-icons">receipt</i>
                <p> SHU
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse @yield('shuShow')" id="shu">
                <ul class="nav">
                @foreach($tipe as $unit)
                @if($role=='Supervisor' || $role=='Spesial')
                <li class="nav-item @yield('shu'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/shu')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @elseif($role!='Admin' && preg_match("/".$unit->slug."/i", $role))
                <li class="nav-item @yield('shu'.$unit->tipe)">
                    <a class="nav-link" href="{{url('/'.$unit->tipe.'/shu')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> {{$unit->tipe}} </span>
                    </a>
                </li>
                @endif
                @endforeach
                @if($role=='Spesial')
                <li class="nav-item @yield('shu')">
                    <a class="nav-link" href="{{url('/shu')}}">
                    <span class="sidebar-mini"> <span class="material-icons">radio_button_checked</span> </span>
                    <span class="sidebar-normal"> SHU Gabungan </span>
                    </a>
                </li>
                @endif
                </ul>
            </div>
        </li>
        @endif
    </ul>
        </div>
            <div class="sidebar-background"></div>
        </div>
@endsection