@extends('layouts.app')
@section('title')Пользователь {{$user->name}} @endsection
@section('content') 
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">@lang('site::user.users')</a>
            </li>
            <li class="breadcrumb-item active">{{ $user->name }}</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-@lang('site::user.icon')"></i> {{ $user->name }}</h1>
        @alert()@endalert
        <form id="invite" method="POST" action="{{ route('admin.users.invite', $user) }}">
                            
                            @csrf
        <div class=" border p-3 mb-2">
            <a href="{{ route('admin.users.edit', $user) }}"
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
            @if(!empty($user->guid))
            <span class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-green"><i class="fa fa-check"></i> Синхронизирован с 1С</span>
            @else
            <a href="{{ route('admin.users.schedule', $user) }}"
               class="@cannot('schedule', $user) disabled @endcannot d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn btn-ms">
                <i class="fa fa-@lang('site::schedule.icon')"></i>
                <span>@lang('site::schedule.synchronize')</span>
            </a>
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
                        @if($user->hasRole('ferroli_user'))
                        <div class="mb-2">
                            <span class="text-muted">Руководитель
                                :</span>&nbsp;&nbsp;{{ $user->chief->name }}
                        </div>
                        @endif
                        <!--
                        @if($user->orders()->count() > 0)
                            <div class="mb-2">
                            <span class="text-muted">@lang('site::repair.help.last')
                                :</span>&nbsp;&nbsp;
                                @if($user->repairs()->count() > 0)
                                    <a href="{{route('admin.repairs.show', $user->repairs()->latest()->first())}}">{{ $user->repairs()->latest()->first()->created_at->format('d.m.Y H:i') }}</a>
                                @endif
                            </div>
                        @endif
                        @if($user->orders()->count() > 0)
                            <div class="mb-2">
                            <span class="text-muted">@lang('site::order.help.last')
                                :</span>&nbsp;&nbsp;
                                @if($user->orders()->count() > 0)
                                    <a href="{{route('admin.orders.show', $user->orders()->latest()->first())}}">{{ $user->orders()->latest()->first()->created_at->format('d.m.Y H:i') }}</a>
                                @endif
                            </div>
                        @endif
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.currency_id'):</span>&nbsp;
                            <span class="text-dark">{{ $user->currency->title }}</span>
                        </div>
                        -->
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.region_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($user->region)->name }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.guid'):</span>&nbsp;
                            <span class="text-dark">{{ $user->guid }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::contragent.inn'):</span>&nbsp;
                            @if($user->contragents()->exists())
                                @foreach($user->contragents as $contragent)
                                    <span class="text-dark">{{ $contragent->inn }}<br /></span>
                                @endforeach
                            @else
                                Нет юр.лиц
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.repair_price_ratio')
                                :</span>&nbsp;&nbsp;{{ $user->repair_price_ratio}}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.created_at')
                                :</span>&nbsp;&nbsp;{{ optional($user->created_at)->format('d.m.Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.logged_at')
                                :</span>&nbsp;&nbsp;{{ $user->logged_at ? $user->logged_at->format('d.m.Y H:i') : trans('site::messages.did_not_come') }}
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
                        @if(!empty($user->type))
                        <div class="mb-2">
                        
                            <i class="fa fa-{{ $user->type->icon }}"></i> {{ $user->type->name }}
                        </div>
                        @endif
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
                        <a href="{{ route('admin.authorizations.index', ['filter[user_id]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::authorization.icon')"></i>
                                 <span>@lang('site::authorization.authorizations')</span>
                            </span>
                            <span class="badge text-big @if($user->authorizations()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->authorizations()->count()}}
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
                        <a href="{{ route('admin.storehouses.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::storehouse.icon')"></i>
                                 <span>@lang('site::storehouse.storehouses')</span>
                            </span>
                            <span class="badge text-big @if($user->storehouses()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->storehouses()->count()}}
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
                        <a href="{{ route('admin.contacts.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::contact.icon')"></i>
                                 <span>@lang('site::contact.contacts')</span>
                            </span>
                            <span class="badge text-big @if($user->contacts()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->contacts()->count()}}
                            </span>
                        </a>
                        @if($user->type_id!='3')
                        <a href="{{ route('admin.engineers.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::engineer.icon')"></i>
                                 <span>@lang('site::engineer.engineers')</span>
                            </span>
                            <span class="badge text-big @if($user->engineers()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->engineers()->count()}}
                            </span>
                        </a>
                        @endif
                        <a href="{{ route('admin.trades.index', ['filter[user]='.$user->id]) }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-@lang('site::trade.icon')"></i>
                                 <span>@lang('site::trade.trades')</span>
                            </span>
                            <span class="badge text-big @if($user->trades()->exists()) badge-ferroli @else badge-light @endif">
                                {{$user->trades()->count()}}
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
                <div class="card mb-4">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::schedule.schedules')</span>
                        <div class="card-header-elements ml-auto">
                            <a href="{{ route('admin.users.schedule', $user) }}"
                               class="@cannot('schedule', $user) disabled @endcannot btn btn-sm btn-light">
                                <i class="fa fa-@lang('site::schedule.icon')"></i>
                            </a>
                        </div>
                    </h6>
                    @if(!$user->contragents()->exists())
                                <li class="list-group-item  bg-lighter text-danger">Нет юридических лиц в профиле пользователя</li>
                            @endif
                    @if(!$user->addresses()->where('type_id',2)->exists())
                                <li class="list-group-item  bg-lighter text-danger">Нет фактического адреса профиле пользователя</li>
                            @endif
                    @include('site::schedule.index', ['schedules' => $user->schedules])
                </div>
                <div class="card mb-4">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">Отображается на карте как дилер:</span>
                        <div class="card-header-elements ml-auto">
                            @if($user->show_map_dealer==1)
                                ДА
                            @else
                                НЕТ
                            @endif
                        </div>
                    </h6>
                    @if(is_array($user->show_map_dealer))
                    @foreach($user->show_map_dealer as $key=>$error)
                    <p class="ml-3 mb-1">@lang('site::messages.user_show_map_errors.' .$error)</p>
                    @endforeach
                    @endif
                </div>
                <div class="card mb-4">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">Отображается на карте как сервис:</span>
                        <div class="card-header-elements ml-auto">
                            @if($user->show_map_service==1)
                                ДА
                            @else
                                НЕТ
                            @endif
                        </div>
                    </h6>
                    @if(is_array($user->show_map_service))
                    @foreach($user->show_map_service as $key=>$error)
                    <p class="ml-3 mb-1">@lang('site::messages.user_show_map_errors.' .$error)</p>
                    @endforeach
                    @endif
                </div>
            </div>
            <div class="col">
			@include('site::message.comment', ['commentBox' => $commentBox])
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::user.relations')</h5>   
                        <div class="row">
                        <div class="col-sm-6">
                        <h6>@lang('site::user.parents')</h6>
                        @if(!empty($user->userRelationParents))
                            @foreach($user->userRelationParents as $parent)
                               <div id="relation-{{$parent->id}}">
                                    @bool(['bool' => $parent->accepted == 1])@endbool
                                    <a href="{{route('admin.users.show', $parent->parent)}}" 
                                        style="@if($parent->enabled == '0') text-decoration: line-through;@endif"> 
                                        {{$parent->parent->name}} 
                                    </a> 
                                        <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::user.relation_enabled_help')">
                                        
                                        </span>
                                    
                                         @if(!$parent->accepted) 
                                        
                                            <form class="d-sm-inline-block" action="{{route('user-relations.update',$parent)}}" method="POST"  id="relation-{{$parent->id}}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="userRelation[accepted]" value="1"/>
                                            <input type="hidden" class="form-control" name="redirect" value="child"/>
                                            <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить связь"><i class="fa fa-child"></i></button>
                                        
                                            </form>
                                        @else
                                        <form class="d-sm-inline-block" action="{{route('user-relations.update',$parent)}}" method="POST"  id="relation-{{$parent->id}}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="userRelation[accepted]" value="0"/>
                                            <input type="hidden" class="form-control" name="redirect" value="child"/>
                                            <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Сбросить связь"><i class="fa fa-child" style="text-decoration: line-through;"></i></button>
                                        
                                            </form>
                                        
                                        @endif
                                       
                                        @if($parent->enabled) 
                                               <form class="d-sm-inline-block" action="{{route('user-relations.update',$parent)}}" method="POST"  id="relation-{{$parent->id}}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="userRelation[enabled]" value="0"/>
                                            <input type="hidden" class="form-control" name="redirect" value="child"/>
                                            <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Удалить связь"><i class="fa fa-eye-slash"></i></button>
                                        
                                            </form>
                                           
                                        @else
                                         
                                         <form class="d-sm-inline-block" action="{{route('user-relations.update',$parent)}}" method="POST"  id="relation-{{$parent->id}}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="userRelation[enabled]" value="1"/>
                                            <input type="hidden" class="form-control" name="redirect" value="child"/>
                                            <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Восстановить связь"><i class="fa fa-eye"></i></button>
                                        
                                            </form> 
                                        @endif     
                                        
                                </div>
                            @endforeach
                        @endif
                        </div>
                        <div class="col-sm-6">
                        <h6>@lang('site::user.childs')</h6>
                        @if(!empty($user->userRelationChilds))
                            @foreach($user->userRelationChilds as $child)
                            <div id="relation-{{$child->id}}">
                            @bool(['bool' => $child->accepted == 1])@endbool
                            <a href="{{route('admin.users.show', $child->child)}}"
                            style="@if($child->enabled == '0') text-decoration: line-through;@endif"> 
                            {{$child->child->name}}
                            </a>
                             
                             
                             @if(!$child->accepted) 
                            
                                <form class="d-sm-inline-block" action="{{route('user-relations.update',$child)}}" method="POST"  id="relation-{{$child->id}}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" class="form-control" name="userRelation[accepted]" value="1"/>
                                <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить связь"><i class="fa fa-child"></i></button>
                            
                                </form>
                            @else
                            <form class="d-sm-inline-block" action="{{route('user-relations.update',$child)}}" method="POST"  id="relation-{{$child->id}}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" class="form-control" name="userRelation[accepted]" value="0"/>
                                <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Сбросить связь"><i class="fa fa-child" style="text-decoration: line-through;"></i></button>
                            
                                </form>
                            
                            @endif
                           
                            @if($child->enabled) 
                                   <form class="d-sm-inline-block" action="{{route('user-relations.update',$child)}}" method="POST"  id="relation-{{$child->id}}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" class="form-control" name="userRelation[enabled]" value="0"/>
                                <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Удалить связь"><i class="fa fa-eye-slash"></i></button>
                            
                                </form>
                               
                            @else
                             
                             <form class="d-sm-inline-block" action="{{route('user-relations.update',$child)}}" method="POST"  id="relation-{{$child->id}}">
                                @csrf
                                @method('PUT')
                                <input type="hidden" class="form-control" name="userRelation[enabled]" value="1"/>
                                <button class="btn btn-small" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Восстановить связь"><i class="fa fa-eye"></i></button>
                            
                                </form> 
                            @endif 
                             
                             </div>
                            @endforeach
                        @endif
                        </div>
                        </div>
                        @if($user->type_id == '3')
                     <h5 class="card-title">@lang('site::user.roles_fl')</h5>      
                     <div class="row">
                     @foreach($roles_fl as $role_fl)
               
                        <div class="col-sm-4">
                            <span style=" @if(!$user->hasRole($role_fl->name)) text-decoration: line-through;@endif font-size: 1.2em; margin-bottom: 0.5em" > @lang('site::user.roles.' .$role_fl->name)
                            </span>
                            @if(!$user->hasRole($role_fl->name))
                                
                                    @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()))
                                    <div>Заявка отправлена <br />{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()->created_at}}
                                   
                                    <form id="role-form-accept-{{$role_fl->id}}" method="POST"  class="d-sm-inline-block" 
                                          action="{{ route('admin.users.role', $user) }}">

                                        @csrf
                                        @method('PUT')
                                        <input name="role_id" value="{{$role_fl->id}}" type="hidden">
                                        <input name="role_request_id" value="{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()->id}}" type="hidden">
                                        <input name="role_accept" value="1" type="hidden">
                                                   
                                        <button type="submit" class="btn btn-small">
                                            <i class="fa fa-check"></i>
                                           
                                        </button>
                                    </form>
                                    <form id="role-form-decline-{{$role_fl->id}}" method="POST"  class="d-sm-inline-block" 
                                          action="{{ route('admin.users.role', $user) }}">

                                        @csrf
                                        @method('PUT')
                                        <input name="role_id" value="{{$role_fl->id}}" type="hidden">
                                        <input name="role_request_id" value="{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()->id}}" type="hidden">
                                        <input name="role_decline" value="1" type="hidden">
                                                   
                                        <button type="submit" class="btn btn-small">
                                            <i class="fa fa-close"></i>
                                           
                                        </button>
                                    </form>
                                    </div>
                            @endif
                                @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl)->where('accepted',1)->where('decline','0')->first()))
                                    Заявка одобрена <br />{{$user->UserFlRoleRequests()->where('role_id',$role_fl)->where('accepted',1)->where('decline','0')->first()->created_at}}
                                @endif
                            @else
                                <div class="d-block form-text text-success">Должность подтверждена</div>
                            
                            @endif
                        </div>
                        
                     
                    @endforeach
                    </div>
                     
                        @endif
                    </div>
                    
                </div>
                
                <div class="card mb-2">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::authorization.authorizations')</h5>
                        <table class="table bg-white table-sm table-bordered">
                            <thead class="thead-light">
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
                                                <span class="badge text-normal badge-success"><i
                                                            class="fa fa-check"></i></span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($user->digiftUser()->exists())
                    @include('site::admin.user.digift_bonus.index', ['digiftUser' => $user->digiftUser])
                @else
                    <div class="alert alert-warning" role="alert">
                        @lang('site::digift_user.help.digift_user_not_exists')
                    </div>
                @endif
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
