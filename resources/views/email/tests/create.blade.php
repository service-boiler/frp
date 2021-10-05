@extends('layouts.email')

@section('title')
    @lang('site::engineer.email_link_title')
@endsection

@section('h1')
    @lang('site::engineer.email_link_title')
@endsection

@section('body')
    <p>Из личного кабинета Ferroli Вам отправлена ссылка на прохождение обучения и тестирования для получения сертификата</p>
    <p><a href="{{$linktest}}">Пройти тестирование</a>
    
    
@endsection