@extends('layouts.app')
@section('title'){{$user->public_name}}@lang('site::messages.title_separator')@endsection

@section('header')
    @include('site::header.front',[
        'h1' => $user->public_name,
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => $user->public_name]
        ]
    ])
@endsection

@section('content')
    <div class="container">
        <div class="card mb-3">
        <div class="card-body">
            
            <div class="mb-2"> 
            @auth()
            <a class="badge text-normal mb-0 mb-sm-1 badge-primary" href="{{route('user_service_change',$user)}}"><i class="fa fa-check"></i> Выбрать этот сервисный центр Вашим основным</a>
            @endauth                   
            @if($user->hasRole('dealer'))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Официальный дилер</span>@endif
            @if($user->hasRole('asc'))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Авторизованный сервисный центр</span>@endif
            </div>
                            @foreach($user->addressesPublic as $num=>$address)
                            
                                <div class="ml-1">
                                
                                    <h5>{{$address->name}}</h5>
                                    <dl class="row ml-1">
                                            <dd class="col-12">{{$address->full}}
                                            
                                             @if(isset($address->is_shop) && $address->is_shop === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Продажи</span>@endif
                                            @if(isset($address->is_service) && $address->is_service === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Сервис</span>@endif
                                            
                                            </dd>
                                            <dt class="col-sm-2">@lang('site::phone.phones')</dt>
                                            <dd class="col-sm-10">@foreach($address->phones as $phone)
                                                    {{$phone->country->phone}} {{$phone->number}} &nbsp;
                                                @endforeach</dd>
                                            @if(!is_null($address->web))
                                            <dt class="col-sm-2">@lang('site::contact.web')</dt>
                                                    @if(stristr($address->web,'http'))
                                                    <dd class="col-sm-10"><a target="_blank" href="{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                                                    @else
                                                    <dd class="col-sm-10"><a target="_blank" href="http://{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                                                    @endif
                                            @endif
                                            
                                            @if(!is_null($address->email))
                                            <dt class="col-sm-2">@lang('site::user.email')</dt><dd class="col-sm-10">{{$address->email}}</dd>
                                            @endif
                                            
                                    </dl>
                                </div> 
                                                            
                            @endforeach
                                
                                
                
        </div>
        </div>
    </div>
@endsection
