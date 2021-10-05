@extends('layouts.email')

@section('title')
    Регистрация ФЛ
@endsection

@section('h1')
    Регистрация ФЛ
@endsection

@section('body')
    <p>{{$user->name }}</p>
    <p>{{$user->region->name }}</p>
	<p> Открыть:
        <a class="btn btn-ms btn-lg" href="{{ route('admin.users.show', $user) }}">
            &#128194; {{ route('admin.users.show', $user) }} </a>
    </p>
@endsection
