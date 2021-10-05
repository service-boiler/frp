@extends('layouts.app')
@section('title')@lang('site::messages.home')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.home')</li>
        </ol>
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
                            <span class="text-muted">@lang('site::user.created_at')
                                :</span>&nbsp;&nbsp;{{ $user->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::region.region'):</span>&nbsp;
                            <span class="text-dark">{{ !empty($user->region) ? $user->region->name : 'Регион не задан!!'}}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{ $user->email }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.phone_main'):</span>&nbsp;
                            <span class="text-dark">{{ $user->phone }}</span>
                        </div>
                        <div class="mb-0">
                           <a class="btn btn-ms btn-sm mb-0 control-label" href="{{route('edit_profile')}}">Редактировать профиль</a>
                        </div>
                    </div>
                    <div class="list-group list-group-flush">
                        
                        @permission('addresses')
                        <a href="{{ route('addresses.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::address.icon')"></i>
                                <b>@lang('site::address.addresses_map')</b>
                            </span>
                            <span class="badge text-big @if($user->addresses()->exists())  @else badge-light @endif">{{$user->addresses()->count()}}</span>
                        </a>
                        @endpermission()
                       
                       
                        <a href="{{ route('esb-requests.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-life-ring"></i>
                                @lang('site::user.esb_request.esb_requests_srv')
                            </span>
                        </a>
                        <a href="{{ route('esb-visits.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-clock-o"></i>
                                @lang('site::user.esb_user_visit.index_srv')
                            </span>
                        </a>
                       

                        <a href="{{ route('esb-user-products.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-barcode"></i>
                                @lang('site::user.esb_user_product.index')
                            </span>
                            
                        </a>

                        <a href="{{ route('esb-users.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-user"></i>
                                @lang('site::user.esb_user.index')
                            </span>
                        </a>
                        
                       <a href="{{ route('esb-repairs.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-wrench"></i>
                                @lang('site::user.esb_repair.index_sm')
                            </span>
                        </a>
                       <a href="{{ route('esb-product-maintenances.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-file"></i>
                                @lang('site::user.esb_product_maintenance.index_sm')
                            </span>
                        </a>

                       
                       <a href="{{ route('esb-product-launches.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-file"></i>
                                @lang('site::user.esb_product_launch.index_sm')
                            </span>
                        </a>
                        <hr>

                       <a href="{{ route('esb-catalog-prices.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-rub"></i>
                                @lang('site::admin.esb_catalog_service.price_index_sm')
                            </span>
                        </a>
                       <a href="{{ route('esb-catalog-services.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-tasks"></i>
                                @lang('site::admin.esb_catalog_service.index_sm')
                            </span>
                        </a>
                        
                        @permission('contragents')
                        <a href="{{ route('contragents.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contragent.icon')"></i>
                                @lang('site::contragent.contragents')
                            </span>
                            <span class="badge text-big @if($user->contragents()->exists()) @else badge-light @endif">
                                {{$user->contragents()->count()}}
                            </span>
                        </a>
                        @endpermission
                       
                    </div>
                </div>
            </div>
            
			
        </div>
    </div>
@endsection
