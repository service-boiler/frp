@extends('layouts.email')

@section('title')
    @lang('site::admin.sms_error_subject')
@endsection

@section('h1')
    @lang('site::admin.sms_error_subject')
@endsection

@section('body')
    <p>{{$data['name']}} <br /> {{$data['id']}}</p>
    <p>{{$data['email']}} <br /> {{$data['phone']}}</p>
    
    
    
@endsection