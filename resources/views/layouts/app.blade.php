<!DOCTYPE html>

<html>
  <head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('assets/css/sb-admin-2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="author" content="Abdul">
    <meta name="description" content="Meet new gamer friends, voice chat and play games together! Mobile Legends, PUBG, Free Fire, League of Legends, Call of Duty, Among Us and much more!">
    <meta name="theme-color" content="#FFF">
    <meta name="title" content="@yield('title')">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:description" content="Meet new gamer friends, voice chat and play games together! Mobile Legends, PUBG, Free Fire, League of Legends, Call of Duty, Among Us and much more!">
    <meta property="og:image" content="{{ asset('favicon.ico') }}">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="game">
    <meta property="og:url" content="http://localhost:8000">
    @yield('head')
  </head>
  <body id="page-top">
    <div id="wrapper">
        <x-sweetalert/>
        <x-sidebar/>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <x-navbar/>

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            <x-footer />
        </div>
    </div>
    
    @yield('scripts')
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>
  </body>
</html>