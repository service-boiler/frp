<div class="d-flex d-lg-none navbar fixed-top " style="align-items: normal;background-color: white;
border-bottom: 1px #fe7907 solid;">
    <div class="left">
        <a class="navbar-toggle collapsed" href="#" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            {{--  <img class="" src="images/lp/logo-bc.png" alt="" style="max-height: 75px;">--}}
            <img class="" src="{{asset('/images/logo-dark-sm-transp.png')}}" alt="" style="max-height: 55px; margin-top: 10px;">
        </a>
    </div>
    <div class="collapse" id="navbar">
        <ul class="navbar-nav mr-auto bg-dark p-3">
            <li class="nav-item"><a class="nav-link" href="https://service.ferroli.ru">Профессионалам</a></li>
            <li class="nav-item"><a class="nav-link" href="{{config('site.catalog_price_pdf')}}">Каталог и прайс (PDF)</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('datasheets.index') }}">@lang('site::datasheet.datasheets')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('service-centers') }}">@lang('site::map.service_center.menu')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('where-to-buy') }}">@lang('site::map.where_to_buy.menu')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('mounter-requests') }}">@lang('site::map.mounter_request.menu')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('feedback') }}">@lang('site::messages.feedback')</a></li>

        </ul>
    </div>
    <div class="right">
        @include('site::cart.nav_market')
    </div>

</div>
<nav class="navbar fixed-top navbar-expand-lg fixed-top d-none d-lg-flex" style="background-color:  rgba(255, 255, 255, 0.95); border-bottom: solid 1px #fe790252; height: 70px;">

    <div class="container pl-0">
        <a class="navbar-brand" href="/" style="position:relative;">
            <img alt="Logo" class="logo" src="{{asset('images/logo_bianco.svg')}}"
                 style="height:74px;position:absolute;top:-30px;left:-18px;">
        </a>
        <a href="#" class="navbar-toggler navbar-toggler-right bg-dark"
           data-toggle="collapse"
           data-target="#navbarResponsive"
           aria-controls="navbarResponsive"
           aria-expanded="false"
           aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </a>
        <div class="module left pl-0">
            <a href="/">
                <img class="logo logo-dark" style="max-height:50px;" alt="Ferroli" src="{{asset('/images/logo-dark-sm-transp.png')}}">
            </a>
        </div>
        <div class="collapse navbar-collapse" style="z-index: 25000 !important;" id="navbarResponsive">
            <ul class="navbar-nav ml-auto mr-auto topmenu">
                <li class="nav-item"><a class="nav-link" href="https://service.ferroli.ru">Профессионалам</a></li>
                <li class="nav-item"><a class="nav-link" href="{{config('site.catalog_price_pdf')}}">Каталог и прайс (PDF)</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('service-centers') }}">@lang('site::map.service_center.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('where-to-buy') }}">@lang('site::map.where_to_buy.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('mounter-requests') }}">@lang('site::map.mounter_request.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('feedback') }}">@lang('site::messages.feedback')</a></li>

            </ul>
        </div>
        <div class="module right">
            @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby']) || (auth()->user()))
                @include('site::cart.nav_market')
            @endif
        </div>

    </div>

    <div style="position: fixed;right: 10px;top: 10px;">

        <button class="btn btn-outline-ferroli p-1 pl-2 pr-2 mt-2" type="button"  onclick="window.location.href = '{{route('home')}}';">
            <p class="mt-0"><i class="ti-user"></i> Личный кабинет</p>
        </button>


    </div>
</nav>




<div class="d-none d-lg-flex" style="position: absolute;z-index: 25000;left: 0;right: 0;">
    <ul class="menu ml-auto mr-auto ">
        <li class="neromenu has-dropdown {{--@if(in_array(Request::route()->getName(), ['products.index', 'products.list'])) active @endif--}}">
            <a href="{{ route('products.index') }}" class="menuprinc">@lang('site::product.products')</a>
        </li>
        <li class="neromenu has-dropdown {{--@if(in_array(Request::route()->getName(), ['catalogs.index', 'catalogs.show', 'catalogs.list', 'equipments.show', 'products.show']) ) active @endif--}}">
            <a href="{{ route('catalogs.index') }}" class="menuprinc">@lang('site::catalog.catalogs')</a>
        </li>
        <li class="neromenu has-dropdown {{--@if(in_array(Request::route()->getName(), ['datasheets.index','datasheets.show'] )) active @endif--}}">
            <a href="{{ route('datasheets.index') }}" class="menuprinc">@lang('site::datasheet.datasheets')</a>
        </li>
    </ul>


</div>