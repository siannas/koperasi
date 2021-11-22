@section('sidebar')
<div class="sidebar" data-color="green" data-background-color="black" data-image="{{asset('public/img/sidebar-1.jpg')}}">
        <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
        -->
    <div class="logo">
        <a href="http://www.creative-tim.com" class="simple-text logo-mini">K</a>
        <a href="http://www.creative-tim.com" class="simple-text logo-normal">Koperasi</a>
    </div>
    <div class="sidebar-wrapper">
        <div class="user">
            <div class="photo">
                <img src="{{asset('public/img/faces/avatar.jpg')}}" />
            </div>
            <div class="user-info">
                <a data-toggle="collapse" href="#collapseExample" class="username">
                    <span>Tania Andrew<b class="caret"></b></span>
                </a>
            <div class="collapse" id="collapseExample">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                        <span class="sidebar-mini"> MP </span>
                        <span class="sidebar-normal"> My Profile </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                        <span class="sidebar-mini"> EP </span>
                        <span class="sidebar-normal"> Edit Profile </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                        <span class="sidebar-mini"> S </span>
                        <span class="sidebar-normal"> Settings </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <ul class="nav">
        <li class="nav-item active ">
            <a class="nav-link" href="">
                <i class="material-icons">dashboard</i>
                <p> Dashboard </p>
            </a>
        </li>
        <li class="nav-item ">
            <a class="nav-link" data-toggle="collapse" href="#pagesExamples">
                <i class="material-icons">image</i>
                <p> Pages
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse" id="pagesExamples">
                <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> P </span>
                    <span class="sidebar-normal"> Pricing </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> RS </span>
                    <span class="sidebar-normal"> RTL Support </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> T </span>
                    <span class="sidebar-normal"> Timeline </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> LP </span>
                    <span class="sidebar-normal"> Login Page </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> RP </span>
                    <span class="sidebar-normal"> Register Page </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> LSP </span>
                    <span class="sidebar-normal"> Lock Screen Page </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> UP </span>
                    <span class="sidebar-normal"> User Profile </span>
                    </a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="">
                    <span class="sidebar-mini"> E </span>
                    <span class="sidebar-normal"> Error Page </span>
                    </a>
                </li>
                </ul>
            </div>
        </li>
        
        <li class="nav-item ">
        <a class="nav-link" href="">
            <i class="material-icons">date_range</i>
            <p> Calendar </p>
        </a>
        </li>
    </ul>
        </div>
            <div class="sidebar-background"></div>
        </div>
@endsection