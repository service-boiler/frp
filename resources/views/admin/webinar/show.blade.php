@extends('layouts.app')
@section('title')Вебинар {{$webinar->id}} {{$webinar->name}} @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.webinars.index') }}">@lang('site::admin.webinar.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $webinar->title }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $webinar->name }}</h1>
        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('ferroli-user.webinars.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.webinars.edit', $webinar) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::admin.webinar.webinar')</span>
            </a>
            @if(empty($webinar->zoom_id))
            <a class="btn btn-primary d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.webinars.create_zoom_webinar', $webinar) }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::admin.webinar.create_zoom_webinar')</span>
            </a>
            @elseif($webinar->service_name=='zoom')
            
            <a class="btn btn-primary d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.webinars.zoom_webinar_stat', $webinar) }}"
               role="button">
                <i class="fa fa-clock-o"></i>
                <span>@lang('site::admin.webinar.get_zoom_webinar_stat')</span>
            </a>
                <a target="_blank" href="https://zoom.us/webinar/{{$webinar->zoom_id}}" 
                    class="btn btn-green d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">@lang('site::admin.webinar.zoom_webinar_exists')&nbsp;<i class="fa fa-external-link"></i></a>
            @else
            @endif
            
            <button type="submit" form="webinar-delete-form-{{$webinar->id}}" 
                                @cannot('delete', $webinar) disabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::admin.webinar.delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $webinar) disabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="webinar-delete-form-{{$webinar->id}}"
									action="{{route('ferroli-user.webinars.destroy', $webinar)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
        <div class="card mb-2">
            <div class="card-body">
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.theme')</dt>
                    <dd class="col-sm-8">{{$webinar->theme->name}} &nbsp;&nbsp;  &nbsp;&nbsp; @lang('site::admin.promocodes.promocode') : @if(!empty($webinar->theme->promocode))
                                                                       {{ $webinar->theme->promocode->name }} ({{ $webinar->theme->promocode->bonuses }} @lang('site::admin.bonus_val'),  
                                                                        @lang('site::admin.promocodes.expiry_at') {{ $webinar->theme->promocode->expiry ? $webinar->theme->promocode->expiry->format('d.m.Y') : 'без срока'}} )
                                                                       @endif</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Баллы отдельно от темы:</dt>
                    <dd class="col-sm-8">@if(!empty($webinar->promocode))
                                         {{ $webinar->promocode->bonuses }} @lang('site::admin.bonus_val') ({{ $webinar->promocode->name }})
                                         @else 
                                         нет
                                         @endif
                                         </dd> 
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.datetime')</dt>
                    <dd class="col-sm-8"> {{ $webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : 'дата не указана'  }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.type_id')</dt>
                    <dd class="col-sm-8">{{$webinar->type->name}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Платформа</dt>
                    <dd class="col-sm-8">{{$webinar->service_name}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.enabled')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $webinar->enabled])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.link_service')</dt>
                    <dd class="col-sm-8">{{$webinar->link_service}}</dd>
                    @if($webinar->zoom_id)
					<dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.zoom_id')</dt>
                    <dd class="col-sm-8">{{$webinar->zoom_id}}</dd>
                    @else
					<dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.id_service')</dt>
                    <dd class="col-sm-8">{{$webinar->id_service}}</dd>
                    @endif
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.annotation')</dt>
                    <dd class="col-sm-8">{!! $webinar->annotation !!}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.description')</dt>
                    <dd class="col-sm-8">{!! $webinar->description !!}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.image_id')</dt>
                    <dd class="col-sm-8">
                        @include('site::admin.image.card', ['image' => $webinar->image])
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.duration_fact')</dt>
                    <dd class="col-sm-8">
                        {{$webinar->participants->max('pivot.duration')}} минут
                    </dd>
                </dl>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="users-tab" data-toggle="tab"
                   href="#users" role="tab" aria-controls="home" aria-selected="true">
                    @lang('site::admin.webinar.members')
                    <span class="badge badge-ferroli text-normal">
                        {{ $webinar->participants()->count() }}
                    </span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="unauth-tab" data-toggle="tab"
                   href="#unauth" role="tab" aria-controls="home" aria-selected="true">
                    @lang('site::admin.webinar.unauth_participants')
                    <span class="badge badge-ferroli text-normal">
                        {{ $webinar->unauthParticipants()->count() }}
                    </span>
                </a>
            </li>
           
        </ul>

        <!-- Tab panes -->
        <div class="tab-content mb-4">
            <div class="tab-pane active" id="users" role="tabpanel" aria-labelledby="users-tab">
                <div class="card">
                    <div class="card-body">               
                       @foreach($webinar->participants->sortBy('name') as $user)
                            
                                        <div class="row mb-3" id="user-{{$user->id}}">
                                            <div class="col-sm-1">
                                                <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::admin.webinar.user_visited')">@bool(['bool' => $user->webinarVisit($webinar->id)->get()->isNotEmpty()])@endbool</span>
                                                @if($webinar->participants->max('pivot.duration')>0) 
                                                {{round($user->pivot->duration/($webinar->participants->max('pivot.duration'))*100,0)}} %
                                                @endif
                                            </div>
                                            <div class="col-sm-11 ">
                                                <a href="{{route('admin.users.show', $user)}}" class=" text-big mr-2 ml-0">
                                                    {{$user->name}} 
                                                </a>
                                        
                                            </div>
                                        </div>
                                    
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="tab-pane" id="unauth" role="tabpanel" aria-labelledby="unauth-tab">
                <div class="card">
                    <div class="card-body">               
                       @foreach($webinar->unauthParticipants->sortBy('name') as $unauthParticipant)
                            
                                        <div class="row mb-3" id="puser-{{$unauthParticipant->id}}">
                                            <div class="col-sm-1">
                                                   @if($webinar->participants->max('pivot.duration')>0) 
                                                    {{round($unauthParticipant->duration/($webinar->participants->max('pivot.duration'))*100,0)}} %
                                                    @endif
                                            
                                            </div>
                                            <div class="col-sm-3 ">
                                                <span class=" text-big mr-2 ml-0">
                                                    {{$unauthParticipant->name}}
                                                </span>
                                        
                                            </div>
                                            <div class="col-sm-3 ">
                                                <span class=" text-big mr-2 ml-0">
                                                    {{$unauthParticipant->email}}
                                                </span>
                                        
                                            </div>
                                            <div class="col-sm-5 ">
                                                <span class=" text-big mr-2 ml-0">
                                                    {{$unauthParticipant->city}}, {{$unauthParticipant->region}}, {{$unauthParticipant->phone}}, {{$unauthParticipant->company}}, 
                                                </span>
                                        
                                            </div>
                                        </div>
                                    
                        @endforeach
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
