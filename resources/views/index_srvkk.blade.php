@extends('layouts.app')

@section('header')
<header>

</header>
@endsection

@section('content')
    <div class="container">
    <div class="row m-5">
    <div class="col text-center">
    <h1 class="mt-5 mb-3">Котельный сервис. Система упраления клиентами</h1>
        <a class="btn btn-ms m-3" href="{{route('force_temp','1')}}">Вход как админ (ЛК Центральный)</a>
        <a class="btn btn-green m-3" href="{{route('force_temp','3')}}">Вход как сервис</a>
    </div>
    
    
    </div>

    
@endsection
