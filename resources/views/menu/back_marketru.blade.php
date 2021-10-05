<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/" style="position:relative;">
            <img alt="Logo" class="logo" src="{{asset('images/logo_bianco_sm.png')}}" style="height:30px;">
        </a>
        <a href="#" class="navbar-toggler navbar-toggler-right bg-dark"
           data-toggle="collapse"
           data-target="#navbarResponsive"
           aria-controls="navbarResponsive"
           aria-expanded="false"
           aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </a>
        <div class="collapse navbar-collapse" style="z-index: 25000 !important;" id="navbarResponsive">
            <ul class="navbar-nav ml-auto mr-auto">
                
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalogs.index') }}">@lang('site::catalog.catalogs_long')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('service-centers') }}">@lang('site::service.services')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('where-to-buy') }}">@lang('site::dealer.dealers')</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('datasheets.index') }}">@lang('site::datasheet.datasheets')</a>
                </li>
                @auth

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">
                        <i class="fa fa-desktop"></i> @lang('site::messages.home')
                    </a>
                </li>
                <li class="nav-item">
                        <a href="javascript:void(0);" class="nav-link"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> {{ __('site::user.sign_out') }}
                        </a>
                   
                </li>
                

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                @endauth
                @include('site::cart.nav_back')
            </ul>
        </div>
    </div>
</nav>