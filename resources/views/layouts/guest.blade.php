<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8"/>
    <title>Login | Minible - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description"/>
    <meta content="EMEHMON" name="author"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css"/>
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css"/>
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css"/>

    @yield('styles')

</head>

<body class="authentication-bg">
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center">
                    {{--
                                        <a href="/" class="mb-5 d-block auth-logo">
                                            <img src="/assets/images/logo.svg" alt="" height="22" class="logo logo-dark">
                                            <img src="/assets/images/logo.svg" alt="" height="22" class="logo logo-light">
                                        </a>
                    --}}
                </div>
            </div>
        </div>
        <div class="row align-items-center justify-content-center">
            @yield('content')
        </div>
        <!-- end row -->
    </div>
    <!-- end container -->
</div>

<!-- JAVASCRIPT -->
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
<script src="/assets/libs/jquery.counterup/jquery.counterup.min.js"></script>

@yield('scripts')

<!-- App js -->
<!-- <script src="/assets/js/app.js"></script> -->

</body>
</html>
