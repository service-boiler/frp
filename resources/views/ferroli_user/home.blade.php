@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.home')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-desktop"></i> @lang('site::messages.home')</h1>
        @alert()@endalert
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <img id="user-logo" src="{{$user->logo}}" style="width:100px!important;height: 100px"
                                 class="rounded-circle">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ $user->name }}</h5>
                                <div class="mt-3">
                                    <form action="{{route('home.logo')}}" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="btn btn-ms btn-sm control-label" for="change-user-logo">
                                                @lang('site::messages.change') @lang('site::user.help.logo')
                                            </label>
                                            <input accept="image/jpeg" name="path" type="file"
                                                   class="d-none form-control-file" id="change-user-logo">
                                            <input type="hidden" name="storage" value="logo">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-light m-0">
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{ $user->email }}</span>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        
                        
                        @if($user->hasPermission('admin_dashboards'))
                        <a href="{{ route('admin.dashboards.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-dashboard"></i> @lang('site::messages.dashboards')
                            </span>
                        </a>
                        @endif
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.tickets.index') }}">
                            <i class="fa fa-life-ring"></i> @lang('site::ticket.tickets')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.missions.index') }}">
                                <i class="fa fa-plane"></i> @lang('site::admin.mission.index')
                            </a>
                        <a href="{{ route('admin.users.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-user-o"></i> @lang('site::user.users')
                            </span>
                        </a>
                        <a href="{{ route('ferroli-user.user_prereg.mounters') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-rocket"></i> Предрегистрация физ.лиц
                            </span>
                        </a>
                        <a href="{{ route('ferroli-user.events.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-calendar"></i> @lang('site::event.academy_events')
                            </span>
                        </a>
                        
                        <a href="{{ route('ferroli-user.members.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::member.icon')"></i> @lang('site::member.members')
                            </span>
                        </a>
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.authorizations.index') }}"><i
                                    class="fa fa-@lang('site::authorization.icon')"></i> @lang('site::authorization.authorizations')
                        </a>
                        
                        <hr/>
                        @permission('admin_acts')
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.acts.index') }}"><i
                                    class="fa fa-@lang('site::act.icon')"></i> @lang('site::act.acts')</a>
                        @endpermission 
                        
                        @permission('repairs_admin_view')       
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.repairs.index') }}"><i
                                    class="fa fa-@lang('site::repair.icon')"></i> @lang('site::repair.repairs')</a>
                        @endpermission                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.orders.index') }}"><i
                                    class="fa fa-@lang('site::order.icon')"></i> @lang('site::order.orders')</a>
                                    
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.tenders.index') }}">
                            <i class="fa fa-legal"></i> Тендеры
                        </a>
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.retail-orders.index') }}">
                            <i class="fa fa-@lang('site::order.icon')"></i> @lang('site::admin.retail_order.retail_orders')</a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.stand-orders.index') }}">
                            <i class="fa fa-cart-plus"></i> @lang('site::stand_order.orders')</a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.mountings.index') }}"><i
                                    class="fa fa-@lang('site::mounting.icon')"></i> @lang('site::mounting.mountings')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.retail-sale-reports.index') }}"><i
                                    class="fa fa-money"></i> @lang('site::retail_sale_report.reports')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.mounters.index') }}">
                            <i class="fa fa-@lang('site::mounter.icon')"></i> @lang('site::mounter.mounters')</a>
                       <hr/> 
                        
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.distributor-sales.index') }}">
                            <i class="fa fa-@lang('site::digift_bonus.icon')"></i> @lang('site::distributor_sales.distributor_sales')
                        </a>
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.storehouses.index') }}">
                            <i class="fa fa-@lang('site::storehouse.icon')"></i> @lang('site::storehouse.storehouses')
                        </a>
                        
                        <hr/>
                        
                        @permission('academy-admin')
                        <a href="{{ route('academy-admin') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-graduation-cap"></i> @lang('site::academy.index')
                            </span>
                        </a>
                        <a href="{{ route('ferroli-user.webinars.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-video-camera"></i> @lang('site::admin.webinar.index')
                            </span>
                        </a>
                        
                        @endpermission()
                        <hr/>
                        
                        @permission('admin_product_edit')
                        
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.catalogs.index') }}">
                            <i class="fa fa-@lang('site::catalog.icon')"></i> @lang('site::catalog.catalogs')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.equipments.index') }}">
                            <i class="fa fa-@lang('site::equipment.icon')"></i> @lang('site::equipment.equipments')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.equipments.market-sort') }}">
                            <i class="fa fa-@lang('site::equipment.icon')"></i> @lang('site::equipment.equipments_sort_menu')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.products.index') }}">
                            <i class="fa fa-@lang('site::product.icon')"></i> @lang('site::product.cards')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.datasheets.index') }}">
                            <i class="fa fa-@lang('site::datasheet.icon')"></i> @lang('site::datasheet.datasheets')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.revision_parts.index') }}">
                            <i class="fa fa-info"></i> @lang('site::admin.revision_part.index')
                        </a>
                        
                        @endpermission()
                        
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="mb-2">
                            <h5>Инструкции и FAQ</h5>
                        </div>
            
                    <div class="list-group list-group-flush">
                        
                        
                        <a href="{{ route('admin.faq.show','1') }}" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-legal"></i> Тендеры. Видео для менеджеров
                            </span>
                        </a>
                        <a href="{{ route('admin.faq.show','2') }}" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-user-o"></i> ЛК менеджера. Краткий обзор.
                            </span>
                        </a>
                        
                        <a href="/up/man/storehouses-and-orders.pdf" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-inbox"></i> Склады дистрибьютора и заказы. Для пользователей.
                            </span>
                        </a>
                        
                        <a href="/up/man/preregs-events-mailing.pdf" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                Предрегистрация. Мероприятия. Рассылки.
                            </span>
                        </a>
                        <a href="/up/man/Тендеры в 1С.pdf" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                Тендеры в 1С.pdf
                            </span>
                        </a>
                        <a href="/up/man/Клиенты в 1С.pdf" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                Клиенты в 1С
                            </span>
                        </a>
                        <a href="/up/man/distributor-sales.pdf" target="_blank"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                Дистрибьюторы. Загрузка продаж.
                            </span>
                        </a>
                   
                </div>
            </div>
            </div>
            
        </div>
    </div>
@endsection
