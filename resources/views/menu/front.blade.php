<img src="///zip.kotelkotel.ru{{env('TEST_SITE') ? '.test' : ''}}/setcookie?id={{ Session::getId() }}" style="display:none;" />

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark fixed-top">

    <div class="container">
        
        <a href="#" class="navbar-toggler navbar-toggler-right bg-dark"
           data-toggle="collapse"
           data-target="#navbarResponsive"
           aria-controls="navbarResponsive"
           aria-expanded="false"
           aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </a>
        <div class="collapse navbar-collapse" style="z-index: 25000 !important;" id="navbarResponsive">
            <ul class="navbar-nav mr-auto ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="///kotelkotel.ru{{env('TEST_SITE') ? '.test' : ''}}">kotelkotel.ru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="///zip.kotelkotel.ru{{env('TEST_SITE') ? '.test' : ''}}">Запчасти</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="///market.kotelkotel.ru">Котлы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="///docs.kotelkotel.ru">Документация</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="///service.kotelkotel.ru">Сервис</a>
                    </li>



                
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-toggle="dropdown"
                       href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        {{ str_limit(Auth::user()->name, 25) }} <i class="mdi mdi-chevron-down"></i>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                        @admin()
                        <a class="dropdown-item" href="{{ route('admin') }}">
                            <i class="fa fa-sliders"></i> @lang('site::messages.admin')
                        </a>

                        <div class="dropdown-divider"></div>

                        
                        @elseadmin()

                        <a class="dropdown-item" href="{{ route('home') }}">
                            <i class="fa fa-desktop"></i> @lang('site::messages.home')
                        </a>
                        
                        @endadmin()
                        <div class="dropdown-divider"></div>
                        @if(isset($current_admin) && is_object($current_admin))
                            <a href="{{ route('users.admin',$current_admin) }}"
                               class="dropdown-item">
                                <i class="fa fa-sign-in"></i>
                                @lang('site::user.force_admin')
                            </a>
                        @endif
                        <a href="javascript:void(0);" class="dropdown-item notify-item"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-sign-out"></i> {{ __('site::user.sign_out') }}
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </div>
                </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
