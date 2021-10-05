@extends('layouts.app')
@section('title')Заявка на авторизацию {{$authorization->id}} {{$authorization->user->name}} @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.authorizations.index') }}">@lang('site::authorization.authorizations')</a>
            </li>
            <li class="breadcrumb-item active">№ {{$authorization->id}}</li>

        </ol>
        <h1 class="header-title mb-4">@lang('site::authorization.header.authorization') № {{$authorization->id}}</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
                    @if($authorization->pre_accepted !='1' && (Auth()->user()->hasRole('админ') || Auth()->user()->hasRole('supervisor') || 
                    (Auth()->user()->hasRole('ferroli_user') && Auth()->user()->notifiRegions()->exists() )))
                    <button type="submit"
                            form="authorization-edit-form"
                            name="authorization[pre_accepted]"
                            value="1"
                            class="btn btn-green d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-check"></i>
                        <span>Согласовать</span>
                    </button>
                    @endif
                    @if($authorization->status_id !='2' && 
                        (Auth()->user()->hasRole('админ') 
                            || Auth()->user()->hasRole('supervisor') 
                            || ( Auth()->user()->hasRole('service_super') && $authorization->role->authorization_role->id =='1')
                            || $authorization->role->authorization_role->id !='1'))
                    <button type="submit"
                            form="authorization-edit-form"
                            name="authorization[status_id]"
                            value="2"
                            class="btn btn-success d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-check"></i>
                        <span>Одобрить</span>
                    </button>
                    @endif
                    @if($authorization->status_id !='3' && (Auth()->user()->hasRole('админ') || Auth()->user()->hasRole('supervisor') || Auth()->user()->hasRole('service_super') || Auth()->user()->hasRole('ferroli_user')))
                    <button type="submit"
                            form="authorization-edit-form"
                            name="authorization[status_id]"
                            value="3"
                            class="btn btn-danger d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-close"></i>
                        <span>Отклонить</span>
                    </button>
                    @endif
                    @if($authorization->status_id !='1' && 
                            (Auth()->user()->hasRole('админ') 
                                || Auth()->user()->hasRole('supervisor') 
                                || Auth()->user()->hasRole('service_super') 
                                || Auth()->user()->hasRole('ferroli_user')))
                    <button type="submit"
                            form="authorization-edit-form"
                            name="authorization[status_id]"
                            value="1"
                            class="btn btn-secondary d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-circle-o"></i>
                        <span>Сбросить в новый</span>
                    </button>
                    @endif
            <a href="{{ route('admin.authorizations.index') }}"
               class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
        </div>


        <form id="authorization-edit-form"
              action="{{route('admin.authorizations.update', $authorization)}}"
              method="POST">
            @csrf
            @method('PUT')
        </form>


        @include('site::message.create', ['messagable' => $authorization])
		
		

        <div class="card mb-4">
            <div class="card-body">
                <dl class="row">

                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::authorization.id')</dt>
                    <dd class="col-sm-9">{{ $authorization->id }}</dd>

                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::authorization.status_id')</dt>
                    <dd class="col-sm-9"><span
                                class="badge text-normal badge-{{ $authorization->status->color }}">{{ $authorization->status->name }}</span>
                                <span class="badge text-normal badge-pill badge-@lang('site::authorization.pre_accepted.color_' .$authorization->pre_accepted) mr-3 ml-0">
                                    <i class="fa fa-@lang('site::authorization.pre_accepted.icon_' .$authorization->pre_accepted)"></i> 
                                    @lang('site::authorization.pre_accepted.' .$authorization->pre_accepted)
                                </span>
                    </dd>

                    <dt class="col-sm-3 text-left text-sm-right mb-3">@lang('site::region.manager')</dt>
                    <dd class="col-sm-9">{{$authorization->user->region->manager->name }}
                    </dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::authorization.user_id')</dt>
                    <dd class="col-sm-9"><a
                                href="{{route('admin.users.show', $authorization->user)}}">{{ $authorization->user->name }}</a>
                    </dd>
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::authorization.role_id')</dt>
                    <dd class="col-sm-9">{{ $authorization->role->authorization_role->name }}</dd>

                    <dt class="col-sm-3 text-left text-sm-right">{{ $authorization->role->authorization_role->title }}</dt>
                    <dd class="col-sm-9">
                        <ul class="list-group">
                            @foreach($authorization->types as $authorization_type)
                                <li class="list-group-item p-1">{{ $authorization_type->name }} {{ $authorization_type->brand->name }}</li>
                            @endforeach
                        </ul>
                    </dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::address.addresses')</dt><dd class="col-sm-9"></dd>
                    @foreach($authorization->user->addresses->where('type_id','2') as $address)
                    <dt class="col-sm-3 text-left text-sm-right mb-0"></dt><dd class="col-sm-9 mb-0"><a target="_blank" href="{{route('admin.addresses.show', $address)}}">{{$address->full}} </a>
                    
                    
                    </dd>
                    <dt class="col-sm-3 text-left text-sm-right mt-0 mb-3"></dt>
                    <dd class="col-sm-9 mt-0 mb-3">
                        @if(( $address->is_service ))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Сервис</span> @endif
                        @if(( $address->is_shop ))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Дилер</span> @endif
                        @if(( $address->is_mounter ))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Монтажник</span> @endif
                        @if(( $address->is_eshop ))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Интернет-магазин</span> @endif
                        @bool(['bool' => $address->approved])@endbool  Одобрен
                        @bool(['bool' => $address->show_ferroli])@endbool F-RU
                        @bool(['bool' => $address->show_market_ru])@endbool Market
                        @bool(['bool' => $address->show_lamborghini])@endbool Lambo 
                    </dd>
                    @endforeach
                    
                    <dt class="col-sm-3 text-left text-sm-right"></dt>
                    <dd class="col-sm-9">Перед одобрением проверь фактические адреса! <br />Должны быть включены нужные галочки, чтобы адрес отображался на соответствующей карте.</dd>
                    
                </dl>
            </div>
        </div>
        
        
        <table class="table bg-white table-sm table-bordered">
            <thead class="thead-light">
            <h4>Текущие авторизации</h4>
            <tr>
                <th scope="col"></th>
                @foreach($authorization_roles as $authorization_role)
                    <th class="text-center" scope="col">{{$authorization_role->name}}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($authorization_types as $authorization_type)
                <tr>
                    <td class="text-right">{{$authorization_type->name}} {{$authorization_type->brand->name}}</td>
                    @foreach($authorization_roles as $authorization_role)
                        <td class="text-center">
                            @if($authorization_accepts->contains(function ($accept) use ($authorization_role, $authorization_type) {
                                return $accept->type_id == $authorization_type->id && $accept->role_id == $authorization_role->role_id;
                            }))
                                <span class="badge text-normal badge-success"><i class="fa fa-check"></i></span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
        @include('site::message.comment', ['commentBox' => $commentBox])
    </div>
@endsection
