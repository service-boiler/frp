@extends('layouts.email')

@section('title')
    @lang('site::user.esb_request.email_create_subject')
@endsection

@section('h1')
    Новые данные о пуско-наладочных работах. (Ввод в эксплуатацию)
@endsection

@section('body')
    <p>
        {{$esbProductLaunch->esbUser->name_filtred}}
    </p><p>
        {{$esbProductLaunch->esbProduct->address_filtred}}
    </p>
    <p>
        {{$esbProductLaunch->esbProduct->product->name}} {{$esbProductLaunch->esbProduct->serial}}
    </p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{env('MARKET_URL').'/esb-product-launches/'.$esbProductLaunch->id }}">
           Открыть данные о ПНР {{env('MARKET_URL').'/esb-product-launches/'.$esbProductLaunch->id }}</a>
    </p>


@endsection