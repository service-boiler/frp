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
        <a class="btn btn-ms" href="{{ route('user_relations.index') }}">
          {{ route('user_relations.index') }}</a>
    </p>
    <p>Вам необходимо перейти в личный кабинет и одобрить или отклонить заявку.
    </p>
@endsection