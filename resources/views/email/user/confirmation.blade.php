@extends('layouts.email')

@section('title')
    Подтвердите свой E-mail адрес
@endsection

@section('h1')
    Спасибо за регистрацию
@endsection

@section('body')
    <p>Вы зарегистрировались от имени {{$user->name }} на сайте
        <a target="_blank" href="{{route('index')}}">{{env('APP_URL')}}</a>
    </p>
    <p>Для завершения регистрации необходимо подтвердить свой E-mail: {{ $user->email }}</p>
    <p>Для этого вам необходимо пройти по ссылке:</p>
    <p>
        <a class="btn btn-ms btn-lg" target="_blank" href="{{ url("register/confirm/{$user->verify_token}") }}">
            ✔ Подтвердить E-mail</a>
    </p>
    <p>Если вы не регистрировались, то, скорее всего, это письмо пришло вам по ошибке, просто
        проигнорируйте его.</p>
@endsection