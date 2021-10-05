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
    <link href="{{ asset('css/app.css?v=0411') }}" rel="stylesheet">
	<link href="/css/style.css?v=0410" rel="stylesheet">
    
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

</head>
<body id="page-top" class="{{ $current_body_class }}">
<div class="main-container">

    @yield('header')
    @yield('content')
</div>
@section('footer')
    <footer class="bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 d-none d-lg-block">
                    <img alt="Logo" class="logo" src="{{asset('images/logo_bianco.svg')}}"
                         style="max-width:70%; margin-left:-30px; margin-top:-20px;">
                    <div>
                        ООО «ФерролиРус» <br>
                        141009, Московская обл, г Мытищи, Ярославское шоссе, влд 1 стр 1<br>
                        Тел. (495) 646 06 23<br>
                        Email: info@ferroli.ru<br>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="widget footer-list">
                        <p class="title">ОБОРУДОВАНИЕ</p>
						<hr>
                        <ul class="recent-post">
                             <li><a href="/catalogs/10">Котлы</a></li>
                             <li><a href="/catalogs/128">Водонагреватели</a></li>
                             <li><a href="/catalogs/78">Бойлеры</a></li>
                             <li><a href="/catalogs/143">Радиаторы</a></li>
                             <li><a href="/catalogs/146">Автоматика</a></li>
                             <li><a href="/products/">Запчасти</a></li>
                             <li><a href="/catalogs/70">Промышленные котлы</a></li>
                            
                            

                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="widget footer-list">
						<p class="title">Услуги и программы</p>
                        <hr>
                        <ul class="recent-post">
                            <li><a href="/services">Сервисные центры</a></li>
                            <li><a href="/mounter-requests">Установка и монтаж</a></li>
                            <li><a href="/dealers">Где купить?</a></li>
                            <li><a href="/datasheets">Документация</a></li>
                            <li><a href="{{config('site.catalog_price_pdf')}}">Каталог и прайс (PDF)</a></li>
                            <li><a href="/login">Вход</a></li>
							<li><a href="/register">Регистрация для партнеров</a></li>
							<li><a class="title" href="https://service.ferroli.ru/masterplus"><img src="/images/master-plus-logo.png" style="width: 180px;"></a>
							<a class="title" href="https://service.ferroli.ru/masterplus"><button class="btn btn-ms">СТАТЬ УЧАСТНИКОМ</button></a>
							</li>
                            
                        </ul>
                    </div>
                </div>
                <div class="col-md-4 col-lg-3">
                    <div class="widget">
                        <p class="title">Сертификация</p>
                        <hr>
                        <div>
                            <img src="{{asset('images/stb.png')}}" style="margin-right:22px;"/>
                            <img src="{{asset('images/certificazioni.png')}}" style="margin-right:22px;"/>
                            <img src="{{asset('images/tuv.png')}}" style="margin-right:22px;"/>
                            <img src="{{asset('images/eac.png')}}"/>
                        </div>
                        <div>
                            <br/><br/>

                        </div>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <span class="sub">© Copyright {{ env('APP_NAME') }} {{ date('Y') }}</span>
                </div>
                <div class="col-lg-3"> &nbsp;</div>
                <div class="col-lg-3"> &nbsp;</div>
                <div class="col-lg-3">
                    <a target="_blank" href="https://www.instagram.com/ferroli_rus_bel/"><img
                                style="width: 30px; height: 30px; margin-left:45px; margin-right:22px;"
                                onmouseover="this.src='/images/inst-footer-hover.png';"
                                onmouseout="this.src='images/inst-footer.png';" src="/images/inst-footer.png" />
                    </a>
                    <a target="_blank" href="{{config('site.youtube_channel')}}"><img style="width: 30px; height: 30px; margin-right:22px;"
                                     onmouseover="this.src='/images/youtube-footer-hover.png';"
                                     onmouseout="this.src='images/youtube-footer.png';"
                                     src="/images/youtube-footer.png">
                    </a>
                </div>
            </div>
        </div>
        <a class="btn btn-sm fade-half back-to-top inner-link" href="#page-top">Вверх</a>
    </footer>
@show

@stack('scripts')

@include('site::modal.form')
@include('site::modal.toast')


@if(in_array($current_route, config('site.front_routes', [])) && env('APP_DEBUG')!='false')

<!-- Global site tag (gtag.js) - Google Analytics --> 
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-87914130-2"> 
</script> 
<script> window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);} gtag('js', new Date()); gtag('config', 'UA-87914130-2'); 
</script>


<!-- Yandex.Metrika counter 

!!!!! Вебвизор на страницах с картами не включать! Повисает намертво.

-->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
                try {
                    w.yaCounter51104846 = new Ya.Metrika2({
                        id: 51104846,
                        clickmap: true,
                        trackLinks: true,
                        accurateTrackBounce: true,
                        webvisor: false
                    });
                } catch (e) {
                }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () {
                    n.parentNode.insertBefore(s, n);
                };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else {
                f();
            }
        })(document, window, "yandex_metrika_callbacks2");
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/51104846" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
<!-- /Yandex.Metrika counter -->

    


@endif

</body>
</html>
