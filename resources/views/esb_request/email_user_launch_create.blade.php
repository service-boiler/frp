@extends('layouts.email')

@section('title')
    @lang('site::user.esb_request.email_user_launch_create_subject')
@endsection

@section('h1')
    @lang('site::user.esb_request.email_user_launch_create_subject')
@endsection

@section('body')
    <p>
        <a class="btn btn-ms btn-lg" href="https://service.ferroli.ru/esb-product-launches/{{$esbProductLaunch->id }}">
            @lang('site::messages.open') https://service.ferroli.ru/esb-product-launches/{{$esbProductLaunch->id }}</a>
    </p>
    <p>Владелец оборудования добавил на сайт данные о вводе в эксплуатацию. Вам необходимо проверить, что пуско-наладочные работы выполнены корректно, 
    заполнить данные об отопительной системе клиента и подтвердить ввод в эксплуатацию.
    </p>
@endsection