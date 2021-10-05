<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <title></title>

    <meta name="description" content="@if($page_description) {{$page_description}} @else @yield('description') @endif">
    <meta name="keyword" content="@lang('site::messages.keyword')">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="QuadStudio"/>
    <meta name="viewport"
          content="width=device-width, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ asset('css/app.css?v=') .env('APP_VER')}}" rel="stylesheet">
	<link href="/css/owl.carousel.min.css" rel="stylesheet">
    @stack('styles')
    <link rel="manifest" href="{{asset('favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('js/app.js?v=') .env('APP_VER')}}" defer></script>

</head>
<body id="page-top" class="{{ $current_body_class }}">
<div class="main-container">
@section('navbar') @include('site::menu.'.$current_menu) @show
    @yield('header')
    @yield('content')
</div>
@section('footer')
    <footer class="bg-dark">
        <div class="container">
            
            <div class="row">
                <div class="col-lg-3">
                    <span class="sub">© Copyright {{ env('APP_NAME') }} {{ date('Y') }}</span>
                </div>
                
            </div>
        </div>
        <a class="btn btn-sm fade-half back-to-top inner-link" href="#page-top">Вверх</a>
    </footer>
@show

@stack('scripts')

@include('site::modal.form')
@include('site::modal.toast')
@include('site::cart.modal.add')



</body>
</html>
