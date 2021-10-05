@extends('layouts.app')
@section('title')Пользователь {{$user->name}} @endsection
@section('content') 
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.engineers.index') }}"> @lang('site::engineer.engineers')</a>
            </li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-@lang('site::user.icon')"></i> {{ $user->name }}</h1>
        @alert()@endalert
        <form id="invite" method="POST" action="{{ route('admin.users.invite', $user) }}">
                            
                            @csrf
        <div class=" border p-3 mb-2">
            <a href="{{ route('admin.engineers.edit', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-ms">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::user.user')</span>
            </a>
            <a href="{{ route('admin.users.password.create', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-ms">
                <i class="fa fa-user-secret"></i>
                <span>@lang('site::messages.change') @lang('site::user.password')</span>
            </a>
            @if(!$user->verified)
            <button type="submit" form="invite" name="invite_user" value="1"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-ms">
                <i class="fa fa-envelope"></i>
                <span>Отправить приглашение пользователю</span>
            </button>
            @endif
            <a href="{{ route('admin.users.prices.index', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn btn-ms">
                <i class="fa fa-@lang('site::user_price.icon')"></i>
                <span>@lang('site::user_price.user_price')</span>
            </a>
            <a href="{{ route('admin.users.messages', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn btn-ms">
                <i class="fa fa-send"></i>
                <span>Сообщения</span>
            </a>
            @can('force_login', Auth::user(), $user)
            <a href="{{ route('admin.users.force', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-warning">
                <i class="fa fa-sign-in"></i>
                <span>@lang('site::user.force_login')</span>
            </a>
            @endcan
            <a href="{{ route('admin.users.index') }}"
               class="d-block d-sm-inline-block btn mb-1 btn-secondary">
                <i class="fa fa-reply"></i>
               
            </a>
        </div>

        </form>
        <div class="row">
            <div class="col-xl-4">
                <!-- Side info -->
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="media">
                            <img id="user-logo" src="{{$user->logo}}" style="width:100px!important;height: 100px"
                                 class="rounded-circle">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ $user->name }} 
                                
                                @if(!empty($user->type)) <i class="fa fa-{{ $user->type->icon }}"></i> @endif
                                @include('site::admin.user.component.online')</h5>
                            </div>
                        </div>
                    </div>
                    <hr class="border-light m-0">
                    <div class="card-body">

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email')
                                :</span>&nbsp;&nbsp;{{ $user->email }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Имя для сайта
                                :</span>&nbsp;&nbsp;{{ $user->name_for_site }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Основной телефон
                                :</span>&nbsp;&nbsp;<a target="_blank" href="https://wa.me/7{{$user->phone}}">{{ $user->phone_formated }} <i class="fa fa-whatsapp"></i></a>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.region_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($user->region)->name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">Тип пользователя:</span>&nbsp;
                            <span class="text-dark">{{ optional($user->type)->name }}</span>
                        </div>


                        <div class="my-2">
                            <span class="d-block text-normal @if($user->active) text-success @else text-danger @endif">
                                @lang('site::user.active_'.($user->active))
                            </span>
                            <span class="d-block text-normal @if($user->verified) text-success @else text-danger @endif">
                                @lang('site::user.verified_'.($user->verified))
                            </span>
                            <span class="d-block text-normal @if($user->display) text-success @else text-danger @endif">
                                @lang('site::user.display_'.($user->display))
                            </span>
                            <span class="d-block text-normal @if($user->only_ferroli) text-success @else text-danger @endif">
                                @lang('site::user.only_ferroli_'.($user->only_ferroli))
                            </span>
                        </div>

                    
                    
                    Сертификаты:
                    @foreach($user->certificates as $certificate)
                    <br />{{$certificate->id}} {{$certificate->type->name}} ({{$certificate->created_at->format('d.m.Y H:i')}})
                    @endforeach
                    
                    </div>
                    <hr class="border-light m-0">
                    <div class="card-body">
                        <h4>@lang('rbac::role.roles')</h4>

                        @foreach($roles as $role)
                            <span class="d-block text-normal @if($user->hasRole($role->name)) text-success @else text-danger @endif">
                                @if($user->hasRole($role->name)) ✔ @else ✖ @endif {{$role->title}}
                            </span>
                        @endforeach
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.repairs.index', ['filter[user_id]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::repair.icon')"></i>
                                 <span>@lang('site::repair.repairs')</span>
                            </span>
                            <span class="badge text-big @if($user->repairs()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->repairs()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.mountings.index', ['filter[user_id]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::mounting.icon')"></i>
                                 <span>@lang('site::mounting.mountings')</span>
                            </span>
                            <span class="badge text-big @if($user->mountings()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->mountings()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.orders.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::order.icon')"></i>
                                 <span>@lang('site::order.orders')</span>
                            </span>
                            <span class="badge text-big @if($user->orders()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->orders()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.contracts.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contract.icon')"></i>
                                 <span>@lang('site::contract.contracts')</span>
                            </span>
                            <span class="badge text-big @if($user->contracts()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contracts()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.users.contragents.index', $user) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contragent.icon')"></i>
                                 <span>@lang('site::contragent.contragents')</span>
                            </span>
                            <span class="badge text-big @if($user->contragents()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contragents()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.addresses.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::address.icon')"></i>
                                 <span>@lang('site::address.addresses')</span>
                            </span>
                            <span class="badge text-big @if($user->addresses()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->addresses()->count()}}
                            </span>
                        </a>
                        <a href="{{ route('admin.contragents.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contragent.icon')"></i>
                                 <span>@lang('site::contragent.contragents')</span>
                            </span>
                            <span class="badge text-big @if($user->contragents()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contragents()->count()}}
                            </span>
                        </a>
                    </div>

                </div>

                <div class="mb-2">
                            <span class="text-muted">@lang('site::user.created_at')
                                :</span>&nbsp;&nbsp;{{ optional($user->created_at)->format('d.m.Y H:i') }}
                </div>
                <div class="mb-2">
                            <span class="text-muted">@lang('site::user.logged_at')
                                :</span>&nbsp;&nbsp;{{ $user->logged_at ? $user->logged_at->format('d.m.Y H:i') : trans('site::messages.did_not_come') }}
                </div>
            </div>
            <div class="col">
			@include('site::message.comment', ['commentBox' => $commentBox])

                @foreach($contacts as $contact)
                    <div class="card mb-2">
                        <div class="card-body">
                            <dl class="row">

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.header.contact')</dt>
                                <dd class="col-sm-8">{{ $contact->name }}</dd>

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::contact.position')</dt>
                                <dd class="col-sm-8">{{ $contact->position }}</dd>

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::contact.type_id')</dt>
                                <dd class="col-sm-8">{{ $contact->type->name }}</dd>

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::phone.phones')</dt>
                                <dd class="col-sm-8">
                                    @if(($phones = $contact->phones)->isNotEmpty())
                                        <ul>
                                            @foreach($phones as $phone)
                                                <li>{{$phone->country->phone}} {{$phone->number}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </dd>

                            </dl>

                        </div>
                    </div>
                @endforeach
                @foreach($addresses as $address)
                    <div class="card mb-2">
                        <div class="card-body">

                            <dl class="row">

                                <dt class="col-sm-4 text-left text-sm-right">{{ $address->type->name }}</dt>
                                <dd class="col-sm-8"><a
                                            href="{{route('admin.addresses.show', $address)}}">{{ $address->full }}</a>
                                </dd>

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.name')</dt>
                                <dd class="col-sm-8">{{ $address->name }}</dd>

                                <dt class="col-sm-4 text-left text-sm-right">@lang('site::phone.phones')</dt>
                                <dd class="col-sm-8">

                                    @if(($phones = $address->phones)->isNotEmpty())
                                        <ul>
                                            @foreach($phones as $phone)
                                                <li>{{$phone->country->phone}} {{$phone->number}}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </dd>
                                <dt class="col-sm-4 text-left text-sm-right"></dt>
                                <dd class="col-sm-8">
                                    @if(( $address->is_service ))<span
                                            class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Сервис</span> @endif
                                    @if(( $address->is_shop ))<span
                                            class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Торговая точка</span> @endif
                                    @if(( $address->is_eshop ))<span
                                            class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Интернет-магазин</span> @endif
                                    @if(( $address->is_mounter ))<span
                                            class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Монтажник</span> @endif
                                </dd>
                            </dl>
                        </div>
                    </div>
                @endforeach
				@if($user->contragents()->exists())
					
					@foreach($user->contragents()->get() as $contragent)
						@foreach ($contragent->addresses()->where('type_id', 3)->with('type')->get() as $address)
                        <div class="card mb-2">
							<div class="card-body">
								<dl class="row">
									<dt class="col-sm-4 text-left text-sm-right">{{ $address->type->name }}</dt>
									<dd class="col-sm-8"><a
                                            href="{{route('admin.addresses.show', $address)}}">{{ $address->postal }}, {{ $address->full }}</a>
									</dd>
									<dt class="col-sm-4 text-left text-sm-right"></dt>
									<dd class="col-sm-8">{{$contragent->name}}</dd>
								</dl>
							</div>
						</div>
                                
						@endforeach
						
					@endforeach	
                @endif
            </div>
        </div>


    </div>
@endsection
