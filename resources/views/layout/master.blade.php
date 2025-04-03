<!DOCTYPE html>
<!--
Template Name: NobleUI - Laravel Admin Dashboard Template
Author: NobleUI
Website: https://www.nobleui.com
Portfolio: https://themeforest.net/user/nobleui/portfolio
Contact: nobleui123@gmail.com
Purchase: https://1.envato.market/nobleui_laravel
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html>
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="description" content="Responsive Laravel Admin Dashboard Template based on Bootstrap 5">
	<meta name="author" content="NobleUI">
	<meta name="keywords" content="nobleui, bootstrap, bootstrap 5, bootstrap5, admin, dashboard, template, responsive, css, sass, html, laravel, theme, front-end, ui kit, web">

  <title>Gestion Comercial</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- End fonts -->

  <!-- CSRF Token -->
  <meta name="_token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">

  <!-- plugin css -->
  <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/@mdi/css/materialdesignicons.min.css') }}" rel="stylesheet" />
  <link href="{{ asset('assets/plugins/datatables-net-bs5/dataTables.bootstrap5.css') }}" rel="stylesheet" />

    {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">--}}
    <link href="{{asset('css/fontgoogleapis.css')}}" rel="stylesheet">
    {{--<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.3.1/css/all.min.css" rel="stylesheet">--}}
    <link href="{{asset('css/cloudflare.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/fontawesome.css')}}" rel="stylesheet">
    <link href="{{asset('css/svg-with-js.css')}}" rel="stylesheet">
    <link href="{{asset('css/solid.css')}}" rel="stylesheet">
    <link href="{{asset('css/datatable-style.css')}}" rel="stylesheet">
    <link href="{{asset('css/regular.css')}}" rel="stylesheet">
    <link href="{{asset('css/v5-font-face.css')}}" rel="stylesheet">
    <link href="{{asset('css/brands.css')}}" rel="stylesheet">
    <link href="{{asset('css/fontawesome.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/brands.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/svg-with-js.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/v4-font-face.css')}}" rel="stylesheet">
    <link href="{{asset('css/v4-font-face.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/v4-shims.css')}}" rel="stylesheet">
    <link href="{{asset('css/v4-shims.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/v5-font-face.min.css')}}" rel="stylesheet">
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/dashboard-custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-solid-900.woff2') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-brands-400.woff2') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-regular-400.woff2') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-v4compatibility.woff2') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-solid-400.ttf') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-brands-400.ttf') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-regular-400.ttf') }}" rel="stylesheet" />
    <link href="{{ asset('webfonts/fa-v4compatibility.ttf') }}" rel="stylesheet" />
    {{--<link href="{{ asset('webfonts/fa-') }}" rel="stylesheet" />--}}
  <!-- end plugin css -->



  @stack('plugin-styles')

  <!-- common css -->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet" />
  <!-- Plugin css import here -->
    <link href="{{ asset('assets/plugins/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/jquery-tags-input/jquery.tagsinput.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropzone/dropzone.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/dropify/css/dropify.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/pickr/themes/classic.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/flatpickr/flatpickr.min.css') }}" rel="stylesheet" />
  <!-- end common css -->

  @stack('style')
</head>
<body data-base-url="{{url('/')}}" class="sidebar-dark">

  <script src="{{ asset('assets/js/spinner.js') }}"></script>

  <div class="main-wrapper" id="app">
    @include('layout.sidebar')
    <div class="page-wrapper">
      @include('layout.header')
      <div class="page-content">
        @yield('content')
      </div>
      @include('layout.footer')
    </div>
  </div>

    <!-- base js -->

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

    <!-- end base js -->

    <!-- plugin js -->
    @stack('plugin-scripts')
    <!-- end plugin js -->

    <!-- common js -->
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.9/dist/l10n/es.js"></script>

    <!-- end common js -->

    @stack('custom-scripts')
</body>
</html>
