<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=0">
    <meta name="robots" content="noindex">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | {{ app_name() }}</title>
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="apple-mobile-web-app-title" content="{{ app_name() }}">
    <meta name="application-name" content="{{ app_name()  }}">
    <meta name="theme-color" content="#ffffff">
    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/shopper.css', 'shopper') }}">
    @routes
</head>
<body class="login-page">

    <section class="wrapper">
        <div class="container-fluid">
            <div class="row h-100">
                <div class="col-md-7 illustration" style="background-image: url('{{ asset('shopper/images/illustration.jpg') }}')"></div>
                <div class="col-md-5">
                    @yield('content')
                </div>
            </div>
        </div>
    </section>

    <script src="{{ asset('shopper/js/vendor.js') }}"></script>
    <script src="{{ mix('/js/login.js', 'shopper') }}"></script>

</body>
</html>
