@extends('layouts.email')

@section('title')
    @lang('site::user.esb_request.email_create_subject')
@endsection

@section('h1')
    Новые данные о ПРН от пользователя.
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
        <a class="btn btn-ms btn-lg" href="{{ route('esb-product-launches.show',$esbProductLaunch) }}">
           Открыть данные о ПНР {{ route('esb-product-launches.show',$esbProductLaunch) }}</a>
    </p>


@endsection