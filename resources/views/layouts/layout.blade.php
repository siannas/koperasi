@include('layouts.sidebar')


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('public/img/apple-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('public/img/favicon.png')}}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>Koperasi | @yield('title')</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <!-- CSS Files -->
    <link href="{{asset('public/css/material-dashboard.css?v=2.2.2')}}" rel="stylesheet" />
    <link href="{{asset('public/css/custom.css')}}" rel="stylesheet" />
</head>

<body class="">
  @yield('modal')
  <div class="wrapper ">
    @yield('sidebar')
    <div class="main-panel">
      <!-- Navbar -->
      <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <div class="navbar-minimize">
              <button id="minimizeSidebar" class="btn btn-just-icon btn-white btn-fab btn-round">
                <i class="material-icons text_align-center visible-on-sidebar-regular">more_vert</i>
                <i class="material-icons design_bullet-list-67 visible-on-sidebar-mini">view_list</i>
              </button>
            </div>
            <a class="navbar-brand" href="javascript:;">@yield('title')</a>
          </div>
          <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
          </button>
          <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
              <div class="input-group no-border">
                <input type="text" value="" class="form-control" placeholder="Search...">
                <button type="submit" class="btn btn-white btn-round btn-just-icon">
                  <i class="material-icons">search</i>
                  <div class="ripple-container"></div>
                </button>
              </div>
            </form>
            <ul class="navbar-nav">
              <li class="nav-item">
                <a class="nav-link" href="{{url('/')}}">
                  <i class="material-icons">dashboard</i>
                  <p class="d-lg-none d-md-block">
                    Dashboard
                  </p>
                </a>
              </li>
              
              <li class="nav-item dropdown">
                <a class="nav-link" href="javascript:;" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <i class="material-icons">person</i>
                  <p class="d-lg-none d-md-block">
                    Profil
                  </p>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                  <!-- <a class="dropdown-item" href="#">Profile</a>
                  <a class="dropdown-item" href="#">Settings</a>
                  <div class="dropdown-divider"></div> -->
                  <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();document.getElementById('logout-form').submit();">Log out</a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      @csrf
                  </form>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </nav>
      <!-- End Navbar -->
      <div class="content">
        <div class="content">
          @yield('content')
        </div>
      </div>
      <footer class="footer">
        <div class="container-fluid">
          <nav class="float-left">
            <!-- <ul>
              <li>
                <a href="https://www.creative-tim.com">
                  Creative Tim
                </a>
              </li>
              <li>
                <a href="https://creative-tim.com/presentation">
                  About Us
                </a>
              </li>
              <li>
                <a href="http://blog.creative-tim.com">
                  Blog
                </a>
              </li>
              <li>
                <a href="https://www.creative-tim.com/license">
                  Licenses
                </a>
              </li>
            </ul> -->
          </nav>
          <div class="copyright float-right">
            &copy;
            <script>
              document.write(new Date().getFullYear())
            </script>, made with <i class="material-icons">favorite</i> by D I A Corp.
            <!-- <a href="https://www.creative-tim.com" target="_blank">Creative Tim</a> for a better web. -->
          </div>
        </div>
      </footer>
    </div>
  </div>
  
  <!--   Core JS Files   -->
  <script src="{{asset('public/js/core/jquery.min.js')}}"></script>
  <script src="{{asset('public/js/core/popper.min.js')}}"></script>
  <script src="{{asset('public/js/core/bootstrap-material-design.min.js')}}"></script>
  <script src="{{asset('public/js/plugins/perfect-scrollbar.min.js')}}"></script>
  <!-- Plugin for the momentJs  -->
  <script src="{{asset('public/js/plugins/moment.min.js')}}"></script>
  <!-- Forms Validations Plugin -->
  <script src="{{asset('public/js/plugins/jquery.validate.min.js')}}"></script>
  <!-- Plugin for the Wizard, full documentation here: https://github.com/VinceG/twitter-bootstrap-wizard -->
  <script src="{{asset('public/js/plugins/jquery.bootstrap-wizard.js')}}"></script>
  <!--	Plugin for Select, full documentation here: http://silviomoreto.github.io/bootstrap-select -->
  <script src="{{asset('public/js/plugins/bootstrap-selectpicker.js')}}"></script>
  <!--  Plugin for the DateTimePicker, full documentation here: https://eonasdan.github.io/bootstrap-datetimepicker/ -->
  <script src="{{asset('public/js/plugins/bootstrap-datetimepicker.min.js')}}"></script>
  <!--  DataTables.net Plugin, full documentation here: https://datatables.net/  -->
  <script src="{{asset('public/js/plugins/jquery.dataTables.min.js')}}"></script>
  <!--	Plugin for Tags, full documentation here: https://github.com/bootstrap-tagsinput/bootstrap-tagsinputs  -->
  <script src="{{asset('public/js/plugins/bootstrap-tagsinput.js')}}"></script>
  <!-- Plugin for Fileupload, full documentation here: http://www.jasny.net/bootstrap/javascript/#fileinput -->
  <script src="{{asset('public/js/plugins/jasny-bootstrap.min.js')}}"></script>
  <!--  Full Calendar Plugin, full documentation here: https://github.com/fullcalendar/fullcalendar    -->
  <script src="{{asset('public/js/plugins/fullcalendar.min.js')}}"></script>
  <!-- Vector Map plugin, full documentation here: http://jvectormap.com/documentation/ -->
  <script src="{{asset('public/js/plugins/jquery-jvectormap.js')}}"></script>
  <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
  <script src="{{asset('public/js/plugins/nouislider.min.js')}}"></script>
  <!-- Include a polyfill for ES6 Promises (optional) for IE11, UC Browser and Android browser support SweetAlert -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
  <!-- Library for adding dinamically elements -->
  <script src="{{asset('public/js/plugins/arrive.min.js')}}"></script>
  <!-- Chartist JS -->
  <script src="{{asset('public/js/plugins/chartist.min.js')}}"></script>
  <!--  Notifications Plugin    -->
  <script src="{{asset('public/js/plugins/bootstrap-notify.js')}}"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="{{asset('public/js/material-dashboard.js?v=2.2.2')}}" type="text/javascript"></script>
  <!-- custom script -->
  <script src="{{asset('public/js/custom.js')}}" type="text/javascript"></script>
  <!-- Moment JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/id.min.js" integrity="sha512-he8U4ic6kf3kustvJfiERUpojM8barHoz0WYpAUDWQVn61efpm3aVAD8RWL8OloaDDzMZ1gZiubF9OSdYBqHfQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  
  <script>
    $(document).ready(function() {
      // Javascript method's body can be found in assets/js/demos.js
      md.initDashboardPageCharts();

      md.initVectorMap();

      //input rupiah
      $('.rupiah-input').blur(my.inputToRupiah);
    });
  </script>

  <!-- Script untuk notifikasi -->
  <script>
    @if (session()->exists('alert'))
    $(document).ready(function(){
        notification = @json(session()->pull("alert"));
        md.showNotification(notification.icon, notification.status, notification.message);
        @php
        session()->forget('alert'); 
        @endphp
    });
    @endif
  </script>
  <script>
  const myRequest = {
          get: function(url){
              return $.ajax({
                  url: url,
                  type: 'GET',
              });
          },
          post: function(url, data){
              data["_token"] = "{{ csrf_token() }}"
              return $.ajax({
                  url: url,
                  method: 'POST',
                  data: data,
              });
          },
          delete: function(url){
              const data = {"_token" : "{{ csrf_token() }}"}
              return $.ajax({
                  url: url,
                  method: 'DELETE',
                  data: data,
              });
          },
          put: function(url, data){
              data["_token"] = "{{ csrf_token() }}"
              return $.ajax({
                  url: url,
                  method: 'PUT',
                  data: data,
              });
          },
          upload: function(url, formdata){
              console.log(url)
              // return
              return $.ajax({
                  xhr : function() {
                      var xhr = new window.XMLHttpRequest();
                      xhr.upload.addEventListener('progress', function(e){
                          if(e.lengthComputable){
                              // console.log('Bytes Loaded : ' + e.loaded);
                              // console.log('Total Size : ' + e.total);
                              // console.log('Persen : ' + (e.loaded / e.total));
                              
                              // var percent = Math.round((e.loaded / e.total) * 100);
                              
                              // $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
                          }
                      });
                      return xhr;
                  },
                  url: url,
                  method: 'POST',
                  data: formdata,
                  contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
                  processData: false, // NEEDED, DON'T OMIT THIS
              });
          },
      }
  </script>

  @yield('script')
</body>

</html>