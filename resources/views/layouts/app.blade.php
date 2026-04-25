<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Streamline your business with our advanced CRM template. Easily integrate and customize to manage sales, support, and customer interactions efficiently. Perfect for any business size">
    <meta name="keywords"
        content="Advanced CRM template, customer relationship management, business CRM, sales optimization, customer support software, CRM integration, customizable CRM, business tools, enterprise CRM solutions">
    <meta name="author" content="Dreams Technologies">
    <meta name="robots" content="index, follow">

    <!-- Title -->
    <title>@yield('title', 'CRMS - Advanced Bootstrap 5 Admin Template for Customer Management')</title>
    <!-- Themescript JS -->
    <script src="{{ asset('template/assets/js/theme-script.js') }}"></script>

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('template/assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('template/assets/img/favicon.png') }}" type="image/x-icon">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css') }}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/tabler-icons/tabler-icons.css') }}">

    <!-- Datatable CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/dataTables.bootstrap5.min.css') }}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/all.min.css') }}">

    <!-- Daterangepicker CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/daterangepicker/daterangepicker.css') }}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css') }}">

</head>

<body>
    <div class="main-wrapper">
        <div class="preloader">
            <span class="loader"></span>
        </div>

        @include('layouts.partials.header')

        @include('layouts.partials.sidebar')

        <div class="page-wrapper">
            @yield('content')
        </div>

    </div>
    <!-- jQuery -->
    <script src="{{ asset('template/assets/js/jquery-3.7.1.min.js') }}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('template/assets/js/feather.min.js') }}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('template/assets/js/jquery.slimscroll.js') }}"></script>
    <script src="assets/js/jquery.slimscroll.min.js"></script>

    <!-- Daterangepicker JS -->
    <script src="{{ asset('template/assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('template/assets/plugins/daterangepicker/daterangepicker.js') }}"></script>

    <!-- Apexchart JS -->
    <script src="{{ asset('template/assets/plugins/apexchart/apexcharts.min.js') }}"></script>
    <script src="{{ asset('template/assets/plugins/apexchart/chart-data.js') }}"></script>

    <!-- Datatable JS -->
    <script src="{{ asset('template/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/assets/js/dataTables.bootstrap5.min.js') }}"></script>


    <!-- Custom Json Js -->
    <script src="{{ asset('template/assets/js/jsonscript.js') }}"></script>


    <!--- Custom Js -->
    <script src="{{ asset('template/assets/js/script.js') }}"></script>
</body>

</html>
