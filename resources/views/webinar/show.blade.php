@extends('layouts.app')
@section('title')Вебинар {{$webinar->id}} {{$webinar->name}} @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('webinars.index') }}">@lang('site::admin.webinar.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $webinar->name }}</li>
        </ol>
        <div class="row">
        <div class="col-6">
        <a href="{{ route('webinars.index') }}" class="d-block d-sm-inline-block btn btn-secondary mb-4">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
        </a>
        <a href="{{ $webinar->zoom_id ? route('webinars.enter',$webinar) : $webinar->link_service }}" class="d-block d-sm-inline-block btn btn-green mb-4 ml-3 mr-3">
                <i class="fa fa-hand-o-right"></i> 
                <span>@lang('site::admin.webinar.enter')</span>
        </a>
        </div>
        <div class="col-6">
        @if(Auth()->user()->type_id==3)
        <button type="submit" form="webinar-register-form-{{$webinar->id}}" 
                                
                                class="btn btn-ms d-block d-sm-inline  mb-4">
								<i class="fa fa-check-square-o"></i> <span class="d-none d-sm-inline-block">@lang('site::admin.webinar.register')</span>
							</button>
        <button type="submit" form="webinar-unregister-form-{{$webinar->id}}" 
                                
                                class="btn btn-danger d-block d-sm-inline  mb-4">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::admin.webinar.unregister')</span>
							</button>
        @else
            Для учета посещений Вами вебинаров Вы должны войти на сайт как физическое лицо.
        @endif
        </div>
        </div>
        
        @alert()@endalert
        <form id="webinar-register-form-{{$webinar->id}}"
                action="{{route('webinars.register', $webinar)}}"
                method="POST">
             @csrf
             @method('POST')
             
        </form>
            
        <form id="webinar-unregister-form-{{$webinar->id}}"
                action="{{route('webinars.unregister', $webinar)}}"
                method="POST">
             @csrf
             @method('POST')
             
        </form>
            
          
        <div class="card mb-2">
            <div class="card-body">
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.name')</dt>
                    <dd class="col-sm-8">{{ $webinar->name }}</dd>
                                                                       
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.theme')</dt>
                    <dd class="col-sm-8">{{$webinar->theme->name}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Баллы:</dt>
                    <dd class="col-sm-8">@if(!empty($webinar->promocode)) {{ $webinar->promocode->bonuses }} @lang('site::admin.bonus_val')
                                        @elseif(!empty($webinar->theme->promocode))
                                        {{ $webinar->theme->promocode->bonuses }} @lang('site::admin.bonus_val')
                                        @endif
                                         </dd> 
                                        
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.datetime')</dt>
                    <dd class="col-sm-8"> {{ $webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : 'дата не указана'  }}</dd>
                    
                    
                    <dt class="col-sm-4 text-left text-sm-right">
                    @include('site::admin.image.card', ['image' => $webinar->image])
                    </dt>
                    <dd class="col-sm-8"><div class="bg-lightest font-italic pb-2 text-big">{!! $webinar->annotation !!}</div>
                    <br />
                    {!! $webinar->description !!}</dd>

                    
                </dl>
            </div>
        </div>
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="users-tab" data-toggle="tab"
                   href="#users" role="tab" aria-controls="home" aria-selected="true">
                    @lang('site::admin.webinar.members')
                    <span class="badge badge-ferroli text-normal">
                        {{ $webinar->users()->count() }}
                    </span>
                </a>
            </li>
           
        </ul>

        <!-- Tab panes -->
        <div class="tab-content mb-4">
            <div class="tab-pane active" id="users" role="tabpanel" aria-labelledby="users-tab">
                @foreach($webinar->users as $user)
                    <div class="card my-1" id="user-{{$user->id}}">

                        <div class="card-header with-elements px-3">
                            <div class="card-header-elements">
                                    {{$user->name}}
                                
                            </div>
                            
                        </div>
                       
                    </div>
                @endforeach
            </div>
            
        </div>
    </div>
@endsection
