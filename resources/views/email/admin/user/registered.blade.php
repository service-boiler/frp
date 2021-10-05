@extends('layouts.email')

@section('title')
    Регистрация нового пользователя @if($user->type_id=='3') ФЛ @endif
@endsection

@section('h1')
    Регистрация нового пользователя @if($user->type_id=='3') ФЛ @else ЮЛ @endif
@endsection

@section('body')
    <p>@if($user->type_id!='3')<b>Компания</b>@endif: {{$user->name }}</p>
	@if(!empty($user->contragents()->first()))
	
    <p><b>Юридический адрес</b>: {{ $user->contragents()->first()->addresses()->where('type_id', 1)->first()->full }}
    </p> @endif
    <p>
        <a class="rng" href="{{ route('admin.users.show', $user) }}">
          Открыть карточку пользователя {{ route('admin.users.show', $user) }}</a>
    </p>
@endsection