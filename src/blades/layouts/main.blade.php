<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/img/favicon.png') }}" />
    <title>@stack("title")</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}" />
<!--link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" /-->
    @stack("css_plugins")
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('plugins/bower_components/toast-master/css/jquery.toast.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/animate.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/colors/megna-dark.css') }}" id="theme"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}" />
    @stack("css")
    <!--[if lt IE 9]>
    <script src="{{ asset('https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js') }}"></script>
    <script src="{{ asset('https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js') }}"></script>
    <![endif]-->
</head>

<body class="fix-header">
<div class="preloader">
    <svg class="circular" viewBox="25 25 50 50">
        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
    </svg>
</div>
<div id="wrapper">

@include("blocks.nav")
@include("blocks.sidemenu")

<!-- Page Content -->
    <div id="page-wrapper">
        @yield("content")
        @include("blocks.footer")
    </div>
</div>


<script src="{{ mix('/js/app.js') }}"></script>

<script src="{{ asset('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') }}"></script>
<script src="{{ asset('plugins/bower_components/toast-master/js/jquery.toast.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('plugins/bower_components/moment/moment.js') }}"></script>
<script src="{{ asset('js/waves.js') }}"></script>
<script src="{{ asset('js/custom.min.js') }}"></script>
@stack("js")
</body>

</html>