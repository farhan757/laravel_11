<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME') }} | Log in</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('template/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/dist/css/AdminLTE.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('template/plugins/iCheck/square/blue.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
    <style>
        /* CSS untuk menetapkan posisi tombol */
        .cornercek {
            position: fixed;
            top: 10px;
            right: 10px;
        }
    </style>

</head>

<body class="hold-transition login-page" style="background-image: url('bg_ganesha.png'); background-size: 100% 100%;">

    <div class="login-box bg-purple" style="box-shadow: 0px 0px 15px rgb(224, 179, 82);">
        <div class="login-logo">
            <b style="font-size: 20pt;">{{ str_replace("_"," ",env('APP_NAME')) }}</b>
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Sign in to start your session</p>
            @if (Session::get('warning'))
                <div class="alert alert-danger">
                    {{ Session::get('warning') }}
                </div>
            @endif
            <form action="{{ route('post.login') }}" method="post" id="login">
                @csrf
                <input type="hidden" name="return" id="return" value="{{ request('return') }}">
                <div class="form-group has-feedback">
                    <input type="text" style="text-transform:uppercase" name="unm_nip" id="unm_nip"
                        class="form-control" autofocus placeholder="NIP" required>
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" name="password" id="password" class="form-control" required placeholder="PASSWORD">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>

                <div class="row">

                    <!-- /.col -->
                    <div class="col-md-12">
                        <center><button type="submit" id="submit" class="btn bg-purple btn-block"><span
                                    class="fa fa-key"></span>
                                Sign In</button></center>
                    </div>
                    <!-- /.col -->
                </div>
            </form>

        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->

    <div id="cekping" class="cornercek">

    </div>

    <footer style="position: fixed; left: 0;
    bottom: 0;
    width: 100%;">
        <strong>Copyright &copy; 2023 {{ env('APP_NAME') }} BANK GANESHA</strong> All rights
        reserved. <b>Version</b> {{ env('versi', '1.0.0') }}
    </footer>

    <!-- jQuery 3 -->
    <script src="{{ asset('template/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('template/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <!-- iCheck -->
    <script src="{{ asset('template/plugins/iCheck/icheck.min.js') }}"></script>
    <script>
        // pingg();
        // setInterval(() => {
        //     pingg();
        // }, 10000);


        // function pingg() {
        //     $.ajax({
        //         url: "{{ route('pingsso') }}",
        //         type: "POST",
        //         data: {
        //             _token: "{{ csrf_token() }}"
        //         },
        //         success: function(rs) {
        //             console.log(rs);
        //             html = '';
        //             if (rs.responcode != "00") {
        //                 html =
        //                     '<div class="alert alert-danger">Offline</div>';
        //                 $("#cekping").html(html);
        //                 trueorfalse(true);
        //             } else {
        //                 html =
        //                     '<div class="alert alert-success">Online</div>';
        //                 $("#cekping").html(html);
        //                 trueorfalse(false);
        //             }

        //         },
        //         error: function(rs) {
        //             console.log(rs + 'err');
        //             html =
        //                 '<div class="alert alert-danger">Offline</div>';
        //             $("#cekping").html(html);
        //             trueorfalse(true);
        //         }
        //     });
        // }

        // function trueorfalse(bool) {
        //     $("#unm_nip").prop("disabled", bool);
        //     $("#password").prop("disabled", bool);
        //     $("#submit").prop("disabled", bool);
        // }
    </script>
</body>

</html>
