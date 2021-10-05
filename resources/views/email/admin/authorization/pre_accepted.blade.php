@extends('layouts.email')

@section('title')
    @lang('site::authorization.email.pre_accepted.title')
@endsection

@section('h1')
    @lang('site::authorization.email.pre_accepted.h1')
@endsection

@section('body')
    
    <p><b>@lang('site::authorization.email.pre_accepted.title')</b></p>
    <p><b>@lang('site::authorization.email.pre_accepted.manager')</b>: {{$authorization->user->region->manager->name }}</p>
    <p><b>@lang('site::authorization.email.pre_accepted.action')</b></p>
    <a class="btn btn-ms btn-lg" href="{{ route('admin.authorizations.show', $authorization) }}">
            &#128194; @lang('site::messages.open') @lang('site::authorization.authorization')</a>
    <p><b>@lang('site::authorization.id')</b>: {{$authorization->id }}</p>
    <p><b>@lang('site::authorization.status_id')</b>: {{ $authorization->status->name }}</p>
	<p>@lang('site::authorization.role_id'):{{ $authorization->role->authorization_role->name }}</p>
	<p>{{ $authorization->role->authorization_role->title }}
                            @foreach($authorization->types as $authorization_type)
                                <li>{{ $authorization_type->name }} {{ $authorization_type->brand->name }}</li>
                            @endforeach
    
@endsection