<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $title ?? env('APP_NAME', 'Title') }}</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="turbolinks-cache-control" content="no-cache">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css') }}"
        data-navigate-once>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/font-awesome/css/font-awesome.min.css') }}"
        data-navigate-once>
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/Ionicons/css/ionicons.min.css') }}"
        data-navigate-once>
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/select2/dist/css/select2.min.css') }}"
        data-navigate-once>
    <!-- bootstrap slider -->
    <link rel="stylesheet" href="{{ asset('template/plugins/seiyria-bootstrap-slider/css/bootstrap-slider.min.css') }}"
        data-navigate-once>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/AdminLTE.min.css') }}" data-navigate-once>
    <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/skins/_all-skins.min.css') }}" data-navigate-once>
    <!-- Date Picker -->
    <link rel="stylesheet"
        href="{{ asset('template/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}"
        data-navigate-once>

    <link rel="stylesheet" href="{{ asset('template/vendors/sweetalert2/dist/sweetalert2.min.css') }}"
        data-navigate-once>
    <link rel="stylesheet" href="{{ asset('template/dist/css/spinner.css') }}" data-navigate-once>

    <!-- jQuery 3 -->
    <script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @yield('css')

</head>

<body class="sidebar-mini skin-purple-light fixed">
    <div class="wrapper">


        <header class="main-header" style="box-shadow: 0px 0px 25px rgb(235, 199, 122);">
            <!-- Logo -->
            <a href="#" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                {{-- <span class="logo-mini"><b>A</b>T</span> --}}
                <!-- logo for regular state and mobile devices -->
                <span class="logo-lg"><b style="font-size: 12pt;">{{ str_replace('_', ' ', env('APP_NAME')) }}</b></span>
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>

                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">

                        {{-- <li class="dropdown notifications-menu">
                            <a href="{{ route('home') }}" >
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">10</span>
                            </a>
                        </li> --}}

                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="{{ asset('users.jpg') }}" class="user-image fa fa-user" alt="User Image">
                                <span class="hidden-xs"> {{ usersCustom()->nama_lengkap }}</span>
                            </a>
                            <ul class="dropdown-menu">

                                <li class="user-header">
                                    {{-- <img src="{{ asset('template/dist/img/user2-160x160.jpg') }}" class="img-circle" alt="User Image"> --}}
                                    <p>
                                        {{ usersCustom()->nama_lengkap }}
                                        <small>{{ usersCustom()->nama_jabat }} -
                                            {{ usersCustom()->nama_dept }}</small>
                                    </p>
                                </li>

                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="{{ route('form.changepass') }}"
                                            class="btn btn-default btn-flat">Change Password</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            class="btn btn-default btn-flat">Sign
                                            out</a>
                                        <form id="logout-form" action="{{ route('proseslogout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul>
                            {{-- <ul class="dropdown-menu">

                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-right">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                            class="btn btn-danger btn-flat"><span class="fa fa-close"></span> Sign
                                            out</a>
                                        <form id="logout-form" action="{{ route('proseslogout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            </ul> --}}
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- Left side column. contains the logo and sidebar -->


        <aside class="main-sidebar">
            @include('layouts.menu')
        </aside>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        {{-- <br><br> --}}
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> {{ env('versi', '1.0.0') }}
            </div>
            <strong>Copyright &copy; 2023 - {{ now()->format('Y') }} {{ env('APP_NAME') }} BANK GANESHA</strong> All
            rights
            reserved.
        </footer>
    </div>

    <!-- ./wrapper -->

    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('template/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- datepicker -->
    <script src="{{ asset('template/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}">
    </script>

    <!-- Slimscroll -->
    <script src="{{ asset('template/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <!-- FastClick -->
    <script src="{{ asset('template/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('template/dist/js/demo.js') }}"></script>

    <script src="{{ asset('template/vendors/sweetalert2/dist/sweetalert2.min.js') }}"></script>

    <script>
        function msgBoxSweetBasic(title, text, icon, url) {
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    if (url != null) {
                        location.href = url;
                    }
                }
            });
        }
    </script>

    @yield('js')

</body>

</html>
