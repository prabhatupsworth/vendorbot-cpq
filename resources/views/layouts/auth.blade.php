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
    <title>Login | CRMS - Advanced Bootstrap 5 Admin Template for Customer Management</title>

    <!-- Apple Touch Icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('template/assets/img/apple-touch-icon.png')}}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('template/assets/img/favicon.png')}}" type="image/x-icon">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/bootstrap.min.css')}}">

    <!-- Tabler Icon CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/tabler-icons/tabler-icons.css')}}">

    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/fontawesome.min.css')}}">
    <link rel="stylesheet" href="{{ asset('template/assets/plugins/fontawesome/css/all.min.css')}}">

    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset('template/assets/css/style.css')}}">

</head>

<body class="account-page">

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <div class="account-content">
            @yield('content')
        </div>

    </div>
    <!-- /Main Wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('template/assets/js/jquery-3.7.1.min.js')}}"></script>

    <!-- Bootstrap Core JS -->
    <script src="{{ asset('template/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Feather Icon JS -->
    <script src="{{ asset('template/assets/js/feather.min.js')}}"></script>

    <!-- Slimscroll JS -->
    <script src="{{ asset('template/assets/js/jquery.slimscroll.min.js')}}"></script>

    <!-- Custom JS -->
    <script src="{{ asset('template/assets/js/script.js')}}"></script>

</body>

</html>
