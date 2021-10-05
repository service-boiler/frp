@extends('layouts.email')

@section('title')
    @lang('site::user.esb_user.service_change_subject')
@endsection

@section('h1')
    @lang('site::user.esb_user.service_change_subject')
@endsection

@section('body')
    <p>
        {{$esbUser->name_filtred}}
    </p>

    <p>
        <a class="btn btn-ms btn-lg" href="///service.ferroli.ru/esb-users">
           Открыть список пользователей /esb-users</a>

    </p>


@endsection