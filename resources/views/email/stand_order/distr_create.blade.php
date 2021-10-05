@extends('layouts.email')

@section('title')
   @lang('site::stand_order.email_create_title')
@endsection

@section('h1')
    @lang('site::stand_order.email_create_h1')
@endsection

@section('body')
    <p><b>@lang('site::stand_order.id')</b>: {{$standOrder->id }}</p>
    <p><b>@lang('site::stand_order.user_id')</b>: {{$standOrder->user->name }}</p>
    <p><b>{{ $standOrder->address->type->name }}</b>: {{ $standOrder->address->name }} </p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('stand-distr.index') }}">
            @lang('site::stand_order.orders_distr') {{ route('stand-distr.index') }}</a>
    </p>
    <p> 
        Условия предоставления товара на витрину согласованы менеджером Ferroli {{$standOrder->user->region->manager->name}}. 
        <br />Проверьте заказ и свяжитесь с дилером. После отгрузки товара отметьте, пожалуйста, что товар отгружен в карточке заказа витринных образцов.
    </p>
@endsection