@extends('layouts.email')

@section('title')
    @lang('site::distributor_sales.email.title')
@endsection

@section('h1')
    @lang('site::distributor_sales.email.h1')
@endsection

@section('body')
    <p><strong> @lang('site::distributor_sales.email.error_text')</strong></p>
    <ul>
        @foreach($distributorSaleLog->message as $message)
            <li>&nbsp;{{$message}}</li>
        @endforeach
    </ul>
    
    
@endsection