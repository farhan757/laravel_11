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


    @yield('css')

</head>

<body class="hold-transition skin-purple-light layout-top-nav">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @yield('content')
        </div>

        {{-- <br><br> --}}
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <b>Version</b> {{ env('versi', '1.0.0') }}
            </div>
            <strong>Copyright &copy; 2023 {{ env('APP_NAME') }} BANK GANESHA</strong> All rights
            reserved.
        </footer>
    </div>

    <!-- ./wrapper -->
    <!-- jQuery 3 -->
    <script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
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

    @yield('js')

</body>

</html>
