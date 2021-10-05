@extends('layouts.email')

@section('title')
    'Заявка на авторизацию №' .$authorization->id .'. не обрабоатна более недели'
@endsection
@section('h1')
    <a class="btn btn-ms" href="{{ route('admin.authorizations.show', $authorization) }}">
            <b>{{ route('admin.authorizations.show', $authorization) }}</b></a>
@endsection

@section('body')
    <p><b>@lang('site::authorization.email.expired.title')</b></p>
    <p><b>@lang('site::authorization.email.expired.manager')</b>: {{$authorization->user->region->manager->name }}</p>
    <p><b>@lang('site::authorization.email.expired.action')</b></p>
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