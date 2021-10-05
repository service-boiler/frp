@extends('layouts.app')
@section('header')
    @include('site::header.front',[
        'h1' => __('site::cart.order'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::cart.order')]
        ]
    ])
@endsection
@section('content')
    <div class="container">
<div class="row my-5">
<div class="col text-center cart-suess">
	<h1>Спасибо за Ваш заказ! </h1>
	
	<p>Наши партнеры свяжутся с Вами в самое ближайшее время. </p>
	<p>В случае возникновения вопросов по Вашему заказу, пожалуйста, связывайтесь с нами по телефону (495)646-06-23 или пишите на info@ferroli.ru</p>
	</div>
	</div>
            <div class="row my-5">
                <div class="col text-center">
                    <div class="mb-3" style="transform: rotate(15deg);font-size: 2rem;">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    @auth()
                        <a href="{{ route('home') }}" role="button" class="btn btn-green mb-3">@lang('site::messages.home')</a>
                        <a href="{{ route('retail-orders-esb.index') }}" role="button" class="btn btn-primary mb-3">@lang('site::user.esb_order.your_orders')</a>
                   @endauth
                    <a href="{{ route('catalogs.index') }}" role="button" class="btn btn-ms mb-3">@lang('site::cart.to_catalog')</a>
                       
                </div>
            </div>

    </div>
@endsection


