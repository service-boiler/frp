@extends('layouts.app')
@section('title')Заявки на авторизацию @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::authorization.authorizations')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::authorization.authorizations')</h1>
        @alert()@endalert
        <div class=" border p-3 mb-2">
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $authorizations])@endpagination
        {{$authorizations->render()}}
        @foreach($authorizations as $authorization)
            <div class="card my-4" id="authorization-{{$authorization->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements col-8 ml-0 pl-0">
                        <a href="{{route('admin.authorizations.show', $authorization)}}" class="mr-3 text-big ml-0">
                                    @lang('site::authorization.header.authorization') № {{$authorization->id}}
                                </a>
                                
                              
                    </div>
                    <div class="card-header-elements ml-md-auto">
                                
                        <span class="badge text-normal badge-pill badge-{{ $authorization->status->color }} mr-3 ml-0">
                                    <i class="fa fa-{{ $authorization->status->icon }}"></i> {{ $authorization->status->name }}
                                </span>
                                <span class="badge text-normal badge-pill badge-@lang('site::authorization.pre_accepted.color_' .$authorization->pre_accepted) mr-3 ml-0">
                                    <i class="fa fa-@lang('site::authorization.pre_accepted.icon_' .$authorization->pre_accepted)"></i> 
                                    @lang('site::authorization.pre_accepted.' .$authorization->pre_accepted)
                                </span>
                    
                        @if( $authorization->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $authorization->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::authorization.created_at')</dt>
                            <dd class="col-12">{{$authorization->created_at->format('d.m.Y H:i')}}</dd>
                             <dd class="col-12"><a href="{{route('admin.users.show', $authorization->user)}}" class="mr-3 ml-0">{{$authorization->user->name}}</a></dd>
                             <dd class="col-12"><strong>@if(!empty($authorization->user->region)){{$authorization->user->region->name}}@endif</strong></dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::authorization.role_id')</dt>
                            <dd class="col-12">{{$authorization->role->authorization_role->name}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-4">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">{{$authorization->role->authorization_role->title}}</dt>
                            <dd class="col-12">
                                <ul class="list-group">
                                    @foreach($authorization->types as $authorization_type)
                                        <li class="list-group-item px-2 py-1">
                                            {{$authorization_type->name}}
                                            {{$authorization_type->brand->name}}
                                        </li>
                                    @endforeach
                                </ul>
                            </dd>

                        </dl>
                    </div>
                </div>
                <div class="card-footer py-1">
                    
                </div>
            </div>
        @endforeach
        {{$authorizations->render()}}
    </div>
@endsection