@extends('layouts.app')
@section('title')@lang('site::shop.service_center.title')@lang('site::messages.title_separator')@endsection
@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endpush


@section('content')
    <div class="container">
        <div class="row">
            <div class="offset-sm-0 col-sm-12 offset-1 col-10">
                <div class="row">
                    <div class="col-12">
                        <h4 id="addresses-list">Список оборудования участвующего в программе дополнительной гарантии.</h4>
                    </div>

                </div>
                <div class="card mb-2">

                    <div class="card-body card-columns">
                        @foreach($products as $product)

                            {{$product->name}}
                            @if(!$loop->last)<br />@endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="offset-sm-0 col-sm-12 offset-1 col-10">
                <div class="row">
                    <div class="col-12">
                        <h4 id="addresses-list">Список сервисных центров, участвующих в программе дополнительной гарантии.
                        </h4>
                        </div>
                        <div class="col-12">
                       <a href="{{route('api.service-centers')}}"><i class="fa fa-external-link"></i>Показать все сервисные центры на карте</a>
                        </div>
                </div>

                @foreach($services as $service)
                <div class="card mb-2">
                    
                        <div class="card-body">
                            <div class="h5 card-title mb-1 gray">{{$service->public_name}} <a class="text-success" target="_blank" href="{{route('public-user-card',$service)}}"><i class="fa fa-external-link"></i></a></div>
                            @foreach($addresses->where('addressable_id',$service->id)->values()->all() as $num=>$address)
                            @if($num == 2)
                            <div class="d-none">
                            @endif
                                <div class="ml-1">
                                
                                    {{$address->name}}, {{$address->full}}
                                    @foreach($address->phones as $phone)
                                                    , {{$phone->country->phone}} {{$phone->number}}&nbsp;
                                                @endforeach
                                            @if(!is_null($address->web))

                                                    @if(stristr($address->web,'http'))
                                                    , <a target="_blank" href="{{$address->web}}" class="card-link">{{$address->web}}</a>
                                                    @else
                                                    , <a target="_blank" href="http://{{$address->web}}" class="card-link">{{$address->web}}</a>
                                                    @endif
                                            @endif

                                </div> 
                                                            
                            @endforeach
                                
                                @if($addresses->where('addressable_id',$service->id)->count() > 2)
                            </div>
                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).addClass('d-none')" 
                            class="align-text-bottom text-left">
                            <b>Еще адреса</b></a>
                                    @endif
                                
                
                
                
                
                        </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection
