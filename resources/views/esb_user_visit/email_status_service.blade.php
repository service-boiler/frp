@extends('layouts.email')

@section('title')
   Изменен статус запланированого выезда специалиста сервисной службы Ferroli
@endsection

@section('h1')
    Изменен статус запланированого выезда специалиста сервисной службы Ferroli
@endsection

@section('body')
    <p>{{$esbVisit->status->name}}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{env('SERVICE_URL')}}/esb-visits">
            Открыть список {{env('SERVICE_URL')}}/esb-visits</a>
    </p>
    <p>
        Сервисным центром {{$esbVisit->service->public_name}} было запланировано техническое обслуживание котла. <br />
        {{trans('site::date.week.' .$esbVisit->date_planned->dayOfWeek)}}  {{$esbVisit->date_planned->format('d.m H:i')}}.
        <br />Статус изменен на {{$esbVisit->status->name}}
    </p>
@endsection