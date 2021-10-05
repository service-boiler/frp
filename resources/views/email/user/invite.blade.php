@extends('layouts.email')

@section('title')
    Представительство Ferroli. Подтвердите свою регистрацию
@endsection

@section('h1')
    Регистрация на сайте {{env('APP_URL')}}
@endsection

@section('body')
    <p>Вы зарегистрированы от имени {{$user->name }} на сайте
        <a target="_blank" href="{{route('index')}}">{{env('APP_URL')}}</a>
    </p>
    <p>Для завершения регистрации Вам необходимо подтвердить свой E-mail: {{ $user->email }}</p>
    <p>Для этого Вам необходимо пройти по ссылке:</p>
    <p>
        <a class="btn btn-ms btn-lg" target="_blank" href="{{ url("register/confirm/{$user->verify_token}") }}">
            {{ url("register/confirm/{$user->verify_token}") }}</a>
    </p>
    
@endsection