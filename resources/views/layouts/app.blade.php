<!doctype html>
<html lang="en">
  <head>
    <title>Booking Hotel</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=Playfair+Display:400,700,900|Rubik:300,400,700" rel="stylesheet">

    <link rel="stylesheet" href="/css/fe/bootstrap.css">
    <link rel="stylesheet" href="/css/fe/animate.css">
    <link rel="stylesheet" href="/css/fe/owl.carousel.min.css">

    <link rel="stylesheet" href="fonts/ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="fonts/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/fe/magnific-popup.css">

    <!-- Theme Style -->
    <link rel="stylesheet" href="/css/fe/style.css">
    @yield('css')
  </head>
  <body>

    <!-- header -->
    @include('layouts/header')

    <!-- content -->
    @yield('content')
    
    <!-- footer -->
    @include('layouts/footer')
    
    <!-- loader -->
    <div id="loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#f4b214"/></svg></div>

    <script src="/js/fe/jquery-3.2.1.min.js"></script>
    <script src="/js/fe/jquery-migrate-3.0.0.js"></script>
    <script src="/js/fe/popper.min.js"></script>
    <script src="/js/fe/bootstrap.min.js"></script>
    <script src="/js/fe/owl.carousel.min.js"></script>
    <script src="/js/fe/jquery.waypoints.min.js"></script>
    <script src="/js/fe/jquery.stellar.min.js"></script>

    <script src="/js/fe/jquery.magnific-popup.min.js"></script>
    <script src="/js/fe/magnific-popup-options.js"></script>

    <script src="/js/fe/main.js"></script>
    @yield('js')
  </body>
</html>