@extends('layouts.email')

@section('title')
    @lang('site::user.role_request_email.title')
@endsection

@section('h1')
    @lang('site::user.role_request_email.h1')
@endsection

@section('body')
    <p> 
            {{ $userFlRoleRequest->user->name }}<br />
        <a class="btn btn-ms" href="{{ route('admin.users.show', $userFlRoleRequest->user) }}">
          {{ route('admin.users.show', $userFlRoleRequest->user) }} </a>
    </p>
@endsection