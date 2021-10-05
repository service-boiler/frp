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
                            <span class="text-muted">@lang('site::user.created_at')
                                :</span>&nbsp;&nbsp;{{ $user->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::region.region'):</span>&nbsp;
                            <span class="text-dark">@if(!empty($user->region)){{ $user->region->name }}@endif</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{ $user->email }}</span>
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
                            <span class="badge text-big @if($user->addresses()->exists()) badge-ferroli @else badge-light @endif">{{$user->addresses()->count()}}</span>
                        </a>
                        @endpermission()
                        @permission('orders')
                        <a href="{{ route('orders.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::order.icon')"></i>
                                @lang('site::order.orders')
                            </span>
                            <span class="badge text-big @if($user->orders()->exists()) badge-ferroli @else badge-light @endif">{{$user->orders()->count()}}</span>
                        </a>
                        @endpermission()
                        @permission('distributors')
                        <a href="{{ route('distributors.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::order.distributor_icon')"></i>
                                @lang('site::order.distributors')
                            </span>
                            <span class="badge text-big @if($user->distributors()->exists()) badge-ferroli @else badge-light @endif">{{$user->distributors()->count()}}</span>
                        </a>
                        @endpermission()
                        @permission('repairs')
                        <a href="{{ route('repairs.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::repair.icon')"></i>
                                @lang('site::repair.repairs')
                            </span>
                            <span class="badge text-big @if($user->repairs()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->repairs()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @if(in_array(env('MIRROR_CONFIG'),['sfru']))
                        @permission('mountings')
                        <a href="{{ route('mountings.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::mounting.icon')"></i>
                                @lang('site::mounting.mountings')
                            </span>
                            <span class="badge text-big @if($user->mountings()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->mountings()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @endif
                        @permission('acts')
                        <a href="{{ route('acts.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::act.icon')"></i>
                                @lang('site::act.acts')
                            </span>
                            <span class="badge text-big @if($user->acts()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->acts()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('authorizations')
                        <a href="{{ route('authorizations.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::authorization.icon')"></i>
                                @lang('site::authorization.authorizations')
                            </span>
                            <span class="badge text-big @if($user->authorizations()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->authorizations()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('mountings')
                        <a href="{{ route('mounters.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::mounter.icon')"></i>
                                @lang('site::mounter.mounters')
                            </span>
                            <span class="badge text-big @if($user->mounters()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->mounters()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('messages')
                        <a href="{{ route('messages.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::message.icon')"></i>
                                @lang('site::message.messages')
                            </span>
                            <span class="badge text-big @if($user->inbox()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->inbox()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('contracts')
                        <a href="{{ route('contracts.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contract.icon')"></i>
                                @lang('site::contract.contracts')
                            </span>
                            <span class="badge text-big @if($user->contracts()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contracts()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('storehouses')
                        <a href="{{ route('storehouses.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::storehouse.icon')"></i>
                                @lang('site::storehouse.storehouses')
                            </span>
                            <span class="badge text-big @if($user->storehouses()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->storehouses()->count()}}
                            </span>
                        </a>
                        @endpermission()
                        @permission('engineers')
                        <a href="{{ route('engineers.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::engineer.icon')"></i>
                                @lang('site::engineer.engineers')
                            </span>
                            <span class="badge text-big @if($user->engineers()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->engineers()->count()}}
                            </span>
                        </a>
                        @endpermission
                        @permission('trades')
                        <a href="{{ route('trades.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::trade.icon')"></i>
                                @lang('site::trade.trades')
                            </span>
                            <span class="badge text-big @if($user->trades()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->trades()->count()}}
                            </span>
                        </a>
                        @endpermission
                        @permission('contragents')
                        <a href="{{ route('contragents.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contragent.icon')"></i>
                                @lang('site::contragent.contragents')
                            </span>
                            <span class="badge text-big @if($user->contragents()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contragents()->count()}}
                            </span>
                        </a>
                        @endpermission
                        @permission('contacts')
                        <a href="{{ route('contacts.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contact.icon')"></i>
                                @lang('site::contact.contacts')
                            </span>
                            <span class="badge text-big @if($user->contacts()->exists()) badge-ferroli @else badge-light @endif">{{$user->contacts()->count()}}</span>
                        </a>
                        @endpermission()
                        @permission('addresses')
                        <a href="{{ route('addresses.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::address.icon')"></i>
                                @lang('site::address.addresses')
                            </span>
                            <span class="badge text-big @if($user->addresses()->exists()) badge-ferroli @else badge-light @endif">{{$user->addresses()->count()}}</span>
                        </a>
                        @endpermission()
                    </div>
                </div>
            </div>
            <div class="col">
			
				@if($user->contragents()->exists())
					
					@foreach($user->contragents()->get() as $contragent)
						@foreach ($contragent->addresses()->where('type_id', 3)->with('type')->get() as $address)
                        @if(empty($address->postal))
						<div class="card my-4" id="address-{{$address->id}}">
							<div class="card-header with-elements">
								<div class="card-header-elements">
								<dl class="dl-horizontal my-sm-2 my-0">
									<dt class="col-12">{{ $address->type->name }}</dt>
									<dd class="col-12">
									<a href="{{route('addresses.edit', $address)}}" class="mr-3"> {{ $address->postal }} {{ $address->full }}</a> {{$contragent->name}} </dd>
								
								<dt class="col-12 error"><span class="bg-danger text-white px-2">Заполните почтовый индекс!</span></dt>
								</dl>
							</div>
							</div>
						</div>
                        @endif        
						@endforeach
						
					@endforeach	
				@endif
			
                @permission('authorizations')
				@if($current_user->type_id != 3 || $user->engineers()->first()->certificates()->where('type_id', '2')->exists())
				<div class="card mb-4">
                    <div class="card-body">
						@if($current_user->type_id == 3)
						<h5 class="card-title">@lang('site::authorization.request.title')</h5>
                        <p class="card-text">@lang('site::authorization.request.text_fl')</p>
						<a href="{{route('authorizations.create', 11)}}"
                               class="btn btn-ms">Монтажник</a>
						@else
					   <h5 class="card-title">@lang('site::authorization.request.title')</h5>
                        <p class="card-text">@lang('site::authorization.request.text')</p>
                        @foreach($authorization_roles as $authorization_role)
                            <a href="{{route('authorizations.create', $authorization_role->role)}}"
                               class="btn btn-ms">{{$authorization_role->name}}</a>
                        @endforeach
						@endif
                    </div>
                </div>
				@endif
                @endpermission()
				
                @permission('manuals_for_sc')
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="{{route('home_academy')}}" class="btn btn-primary">
                            <i class="fa fa-upload"></i>
                            Обучающие и справочные материалы
                        </a>
                    </div>
                </div>
                @endpermission()
								
				<div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::authorization.learn.title') OnLine</h5>
                        <p class="card-text">@lang('site::authorization.learn.text')</p>
                        <p><a href="@lang('site::authorization.learn.montage')?entry.161167930={{$user->email}}&entry.1429077286={{ $user->name }}&entry.1388971351=&entry.37765298=@if(!empty($user->addresses()->where('type_id', '2')->first()->locality)){{$user->addresses()->where('type_id', '2')->first()->locality}}@endif" target="_blank"
							class="btn btn-ms blink-t">Монтаж оборудования Ferroli</a></p>
						
                        <!--
                        @if($user->mountingCertificates()->exists())
                            <p class="card-text">
								<a class="btn btn-success" href="{{route('certificates.show', $user->mountingCertificates()->first())}}" target="_blank">
                                <i class="fa fa-download"></i>@lang('site::certificate.button.download_pdf')
								</a> 
								<a class="btn btn-edit" href="{{route('contacts.edit', $user->contacts->first()->id)}}" target="_blank">
                                <i class="fa fa-edit"></i>@lang('site::certificate.button.edit_position')
								</a>  
								<a class="btn btn-edit" href="{{route('addresses.edit', $user->addresses->first()->id)}}" target="_blank">
                                <i class="fa fa-edit"></i>@lang('site::certificate.button.edit_locality')
								</a> 
							
							</p>
                        @else
							@if(!empty($user->engineers()->first()))
								<form action="{{route('certificates.store')}}" method="POST">
									@csrf
									<div class="form-group">
										<input type="hidden" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{$user->email}}"/>
										<span class="invalid-feedback">{!! $errors->first('email') !!}</span>
									</div>
									<button class="btn btn-primary" type="submit">
										<i class="fa fa-download"></i>
										@lang('site::certificate.button.get')
									</button>
								</form>
							@else
								@lang('site::mounting.help.engineer_required')
							@endif
                        @endif
                        -->
					
					</div>
				</div>
				<div class="card mb-4">
					
					<div class="card-body">
						<p class="card-text">@lang('site::event_type.request.region'), @lang('site::event_type.request.city')<br />
						<a href="/up/ferroli-event-request.pdf" target="_blank"><b>Краткая инструкция.</b></a></p>
						<a href="{{ route('events.index') }}" target="_blank" class="btn btn-ms blink-t">Обучения и мероприятия</a>
						
					</div>
					
					
                </div>
				

				
                @permission('storehouses.excel')
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::storehouse.header.quantity')</h5>

                        <a href="{{route('storehouses.excel')}}" class="btn btn-primary">
                            <i class="fa fa-upload"></i>
                            @lang('site::storehouse.header.download')
                        </a>
                    </div>
                </div>
                @endpermission()
                
                @can('product_list', Auth::user())
                <div class="card mb-1 mt-3">
                <div class="card-body">
                <a href="{{route('products.list')}}" class="btn btn-primary">
                    <i class="fa fa-list-alt"></i> Прайс-лист на запасные части
                </a>
            </div>
            </div>
                
                @endcan
					 <!--
                     @permission('mountings')
					 <div class="card mb-4 card-body bg-danger text-white px-2">
						Внимание! Оборудование, не участвующее в программе мотивации (п 4.1 Правил):
				 
                  <p><ul>
						<li>- смонтированное ранее 01.01.2020г.</li>
						<li>- с датой производства ранее 36 недели 2019 г (например, <span style="color:black; font-weight: 700;">1935</span>L08362 или <span style="color:black; font-weight: 700;">1920</span>0001)</li>
						<li>- объектные поставки</li>
						<li>- реализованное или смонтированное на территории Калининградской области или вне территории РФ.</li>
						</ul></p>
             
					</div>
					 @endpermission()
					 -->
                
                @permission('ferroli_contacts')
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::home.ferroli_contacts_h') </h5>
                        @lang('site::home.ferroli_contacts_w')
                        @lang('site::home.ferroli_contacts_text')
                    </div>
                </div>
                @endpermission()
                
                
            </div>
        </div>
    </div>
@endsection
