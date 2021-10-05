<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

    <title>@auth()@lang('site::page.mirror') @endauth @if($page_title) {{$page_title}} - @else @yield('title') @endif @lang('site::messages.title')</title>

    <meta name="description" content="@if($page_description) {{$page_description}} @else @yield('description') @endif">
    <meta name="keyword" content="@lang('site::messages.keyword')">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="QuadStudio"/>
    <meta name="viewport"
          content="width=device-width, shrink-to-fit=no, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{--<link rel="shortcut icon" href="{{asset('favicon.ico')}}">--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600|Roboto:300,400&amp;subset=cyrillic"--}}
    {{--rel="stylesheet">--}}
    <link href="{{ asset('css/app.css?v=0410') }}" rel="stylesheet">
	<link href="/css/style.css?v=0410" rel="stylesheet">
    <link href="/css/owl.carousel.min.css" rel="stylesheet">
    @stack('styles')
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset('/favicon/apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset('/favicon/apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset('/favicon/apple-icon-72x72')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset('/favicon/apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset('/favicon/apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset('/favicon/apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset('/favicon/apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset('/favicon/apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('/favicon/apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset('/favicon/android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset('/favicon/favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset('/favicon/favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset('/favicon/favicon-16x16.png')}}">
    <link rel="manifest" href="{{asset('favicon/manifest.json')}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <script src="{{ asset('js/app.js?v=0410') }}" defer></script>
	
    <script src="{{ asset('js/aditon.js?v=0410') }}" defer></script>

</head>
<body id="page-top" class="{{ $current_body_class }}">
<div class="main-container">

    @yield('content')
</div>
@section('footer')
   
   @show

@stack('scripts')

@include('site::modal.form')
@include('site::modal.toast')

@if($current_route == 'index' OR substr($current_route,0,7) == 'product' OR substr($current_route,0,8) == 'catalogs' OR substr($current_route,0,10) == 'datasheets')
    


@endif

</body>
</html>
