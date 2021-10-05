@extends('layouts.app')
@section('title')Пользователь {{$user->name}} @endsection
@section('content') 
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.index') }}">Клиенты</a>
            </li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
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
            @if(!$user->verified && $user->email)
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
                        <h5 class="mb-2">{{ $user->name }}
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
                        </div>
                    </div>

                        <div class="list-group list-group-flush">
                            <a href="{{ route('admin.esb-clients.esb-products.index',$user)}}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-android"></i>
                                 <span>Зарегистрированное оборудование</span>
                            </span>
                                <span class="badge text-big @if($user->mountings()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->esbProducts()->count()}}
                            </span>
                            </a>
                            <a href="{{ route('admin.orders.index', ['filter[user]='.$user->id]) }}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::order.icon')"></i>
                                 <span>@lang('site::order.orders')</span>
                            </span>
                                <span class="badge text-big @if($user->orders()->where('status_id','1')->exists()) badge-ferroli @endif">
                                {{$user->orders()->count()}}
                            </span>
                            </a>
                            <a href="{{ route('esb-contracts.index', ['filter[user]='.$user->id]) }}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contract.icon')"></i>
                                 <span>Клиентские договоры</span>
                            </span>
                                <span class="badge text-big badge-light">
                                {{$user->esbClientContracts()->count()}}
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
                            <h5>Адреса клиента</h5>

                            <dl class="row">

                                <dt class="col-sm-2 text-left text-sm-right px-0">Адрес:</dt>
                                <dd class="col-sm-10"><a
                                            href="{{route('admin.addresses.show', $address)}}">{{ $address->full }}</a>
                                </dd>
                                @if($address->esbProducts()->where('enabled',1)->exists())
                                <dt class="col-sm-2 text-left text-sm-right px-0">Оборудование:</dt>

                                    @foreach($address->esbProducts()->where('enabled',1)->get() as $esbProduct)

                                        <dd class="col-sm-10"><a class="mb-3" target="_blank" href="{{route('esb-user-products.show',$esbProduct)}}">
                                                {!! $esbProduct->product ? $esbProduct->product->name  :
                                                  $esbProduct->product_no_cat!!}</a></dd>
                                        <dt class="col-sm-2 text-left text-sm-right px-0"></dt>

                                    @endforeach
                                    @endif

                            </dl>
                        </div>
                    </div>
                @endforeach

                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Последние заявки на сервис и визиты к клиенту</h5>
                        @foreach($user->esbClientVizits()->orderBy('created_at','desc')->take(2)->get() as $visit)
                           <div class="row">
                               <div class="col-5">
                                   <dl class="dl-horizontal mt-2">
                                       <dt class="col-12">Дата: </dt>
                                       <dd class="col-12 font-weight-bold"><b>{{optional($visit->date_planned)->format('d.m.Y')}}</b></dd>
                                       <dt class="col-12">Инженер: </dt>
                                       <dd class="col-12 font-weight-bold"><b>{{optional($visit->engineer)->name}}</b></dd>
                                       <dt class="col-12">Договор: </dt>
                                       <dd class="col-12 "> @if($visit->esbContract)
                                               <a class="font-weight-bold" href="{{route('esb-contracts.show',$visit->esbContract)}}">
                                                   {{$visit->esbContract->number}} {{optional($visit->esbContract->date)->format('d.m.Y')}}
                                               </a>@endif</dd>
                                       <dt class="col-12">Деньги: </dt>
                                       <dd class="col-12 font-weight-bold"> @if($visit->cost_actual)
                                               {!! moneyFormatRub($visit->cost_actual) !!} /
                                               @endif
                                                Планово: {!! moneyFormatRub($visit->cost_planned) !!}</dd>
                                   </dl>

                               </div>
                               <div class="col-7">
                                   <dl class="dl-horizontal mt-2">
                                       <dt class="col-12">Статус:</dt>
                                       <dd class="col-12 font-weight-bold"><b>{{$visit->status->name}}</b></dd>
                                       <dt class="col-12">Тип:</dt>
                                       <dd class="col-12 font-weight-bold"><b>{{optional($visit->type)->name}}</b></dd>
                                       <dt class="col-12">Комментарии:</dt>
                                       <dd class="col-12 font-weight-bold"><b>{{$visit->comments}}</b></dd>

                                   </dl>

                               </div>
                           </div>


                        @endforeach
                        <div class="row">
                            <div class="col-12 text-right">
                                <a class="btn btn-sm btn-ms" href="{{route('esb-visits.create',['client_id'=>$user->id])}}">Запланировать выезд</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-2">
                    <div class="card-body">
                        <h5>Договоры с клиентом</h5>
                        @foreach($user->esbClientContracts()->get() as $contract)
                            <dl class="row">

                                <dt class="col-sm-2 text-left text-sm-right px-0">Договор:</dt>
                                <dd class="col-sm-10"><a class="font-weight-bold" href="{{route('esb-contracts.show', $contract)}}" class="mr-2 ml-0 text-big">
                                        <i class="fa fa-external-link"></i>&nbsp; Договор № {{$contract->number}} </a>
                                    дата: {{optional($contract->date)->format('d.m.Y')?:'---'}},
                                    срок действия с: {{optional($contract->date_from)->format('d.m.Y')?:'---'}},
                                    по: {{optional($contract->date_to)->format('d.m.Y')?:'---'}},

                                </dd>
                                @if($contract->esbUserProducts()->where('enabled',1)->exists())
                                <dt class="col-sm-2 text-left text-sm-right px-0">Оборудование:</dt>

                                    @foreach($contract->esbUserProducts()->where('enabled',1)->get() as $esbProduct)

                                        <dd class="col-sm-10"><a class="mb-3" target="_blank" href="{{route('esb-user-products.show',$esbProduct)}}">
                                                {!! $esbProduct->product ? $esbProduct->product->name  :
                                                  $esbProduct->product_no_cat!!}</a></dd>
                                        <dt class="col-sm-2 text-left text-sm-right px-0"></dt>

                                    @endforeach
                                    @endif

                            </dl>
                            <hr />


                        @endforeach
                        <div class="row">
                            <div class="col text-right">
                                <a class="btn btn-sm btn-ms" href="{{route('admin.esb-clients.esb-contracts.create',$user)}}">Создать новый договор</a>
                            </div>
                        </div>

                    </div>
                </div>
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
