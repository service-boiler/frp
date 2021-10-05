@extends('layouts.email')

@section('title')
    @lang('site::admin.sms_limit_subject')
@endsection

@section('h1')
    @lang('site::admin.sms_limit_subject')
@endsection

@section('body')
    <p>Слишком много отправлено СМС за 1 час! <br /> Отправка заблокирована.
    
    </p>
    
@endsection