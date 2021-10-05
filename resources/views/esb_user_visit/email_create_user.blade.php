@extends('layouts.email')

@section('title')
   Запланирован выезд специалиста сервисной службы Ferroli
@endsection

@section('h1')
    Запланирован выезд специалиста сервисной службы Ferroli
@endsection

@section('body')
    <p>
        <a class="btn btn-ms btn-lg" href="{{env('MARKET_URL')}}/esb-visits">
            Открыть список {{env('MARKET_URL')}}/esb-visits</a>
    </p>
    <p>
        Сервисным центром {{$esbVisit->service->public_name}} запланировано техническое обслуживание котла. <br />
        {{trans('site::date.week.' .$esbVisit->date_planned->dayOfWeek)}}  {{$esbVisit->date_planned->format('d.m H:i')}}.
        <br />Вы можете перейти в свой личный кабинет на сайте {{env('MARKET_URL')}} и согласовать дату и время визита.
    </p>
@endsection