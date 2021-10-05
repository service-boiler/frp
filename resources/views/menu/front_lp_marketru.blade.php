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
            <li class="nav-item"><a class="nav-link" href="{{ route('index') }}">@lang('site::messages.index')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('catalogs.index') }}">@lang('site::catalog.catalogs')</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ route('datasheets.index') }}">@lang('site::datasheet.datasheets')</a>
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
<nav class="navbar fixed-top navbar-expand-lg fixed-top d-none d-lg-flex" style="background-color:  rgba(255, 255, 255, 0.95); border-bottom: solid 1px #fe790252;">

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
                <li class="nav-item"><a class="nav-link" href="{{ route('catalogs.index') }}">@lang('site::catalog.catalogs')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('datasheets.index') }}">@lang('site::datasheet.datasheets')</a>
                <li class="nav-item"><a class="nav-link" href="{{ route('service-centers') }}">@lang('site::map.service_center.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('where-to-buy') }}">@lang('site::map.where_to_buy.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('mounter-requests') }}">@lang('site::map.mounter_request.menu')</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('feedback') }}">@lang('site::messages.feedback')</a></li>

            </ul>
        </div>
        <div class="module right" .style="padding-right: 0;">


            @include('site::cart.nav_market')
        </div>
    </div>

    <div style="position: fixed;right: 10px;top: 10px;">

        <button class="btn btn-outline-ferroli p-1 pl-2 pr-2 mt-2" type="button"  onclick="window.location.href = '{{route('home')}}';">
            <p class="mt-0"><i class="ti-user"></i> Личный кабинет</p>
        </button>
        <a class="d-block p-1 pl-2 pr-2 mt-2" href="/whatsapp">
            <p class="mt-0">Whatsapp</p>
        </a>


    </div>

</nav>


