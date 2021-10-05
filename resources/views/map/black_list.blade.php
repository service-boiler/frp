@extends('layouts.app')

@section('title')@lang('site::map.black_list.title')@lang('site::messages.title_separator')@endsection

@section('header')
     <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            @foreach($headBannerBlocks as $headBannerBlock)
               <li data-target="#carouselExampleIndicators" data-slide-to="{{$loop->index}}" @if($loop->index==0) class="active" @endif></li>
            @endforeach
        </ol>
            <div class="carousel-inner">
                @foreach($headBannerBlocks as $headBannerBlock)
                    <div class="carousel-item @if($loop->index==0) active @endif">
                    @if(!empty($headBannerBlock->url) & $headBannerBlock->url!='#' )
                    <a href="{{$headBannerBlock->url}}" target="_blank"><img src="{{$headBannerBlock->image->src()}}" alt=""></a>
                    @else
                    <img src="{{$headBannerBlock->image->src()}}" alt="">
                    @endif
                    </div>
                @endforeach
            </div>	


        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.prev')</span></a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.next')</span></a>
    </div>
    @include('site::header.front',[
        'h1' => ' '
        .__('site::map.black_list.title'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::map.black_list.title')]
        ]
    ])
@endsection

@section('content')
    <div class="container">
     <p style="text-align: center;	font-size: 1.2em;	line-height: 1.3em;	padding: 20px;}">
     Внимание! <br />Ознакомьтесь со списком интернет-магазинов, которые компания ИЗАО "ФерролиБел" не может рекомендовать для приобретения продукции ТМ Ferroli вследствие неизвестного происхождения товара и, соответственно, отсутствия заводской гарантии.
     </p>
        <div class="row items-row-view">
                @filter(['repository' => $repository])@endfilter
                @foreach($addresses as $address)
                
                    <div class="items-col col-12">
                        <div class="card item-hover mb-1">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-2">{{$address->web}}</div>
                                    <div class="col-sm-4">{{$address->name}}</div>
                                </div>                            
                            </div>                            
                        </div>                            
                    </div>                            
                   
                @endforeach
        </div>
     </div>
@endsection