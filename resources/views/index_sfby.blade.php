@extends('layouts.app')

@section('header')
<header>
<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
	<ol class="carousel-indicators">
        @foreach($headBannerBlocks as $headBannerBlock)
           <li data-target="#carouselExampleIndicators" data-slide-to="{{$loop->index}}" @if($loop->index==0) class="active" @endif></li>
        @endforeach
    </ol>
        <div class="carousel-inner">
            @foreach($headBannerBlocks as $headBannerBlock)
                <div class="carousel-item @if($loop->index==0) active @endif"><a href="{{$headBannerBlock->url}}" target="_blank"><img src="{{$headBannerBlock->image->src()}}" alt=""></a></div>
            @endforeach
        </div>	


	<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.prev')</span></a>
	<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="sr-only">@lang('site::messages.next')</span></a>
</div>
</header>
@endsection

@section('content')
    <div class="container">
	@include('site::static.home_text_'.env('MIRROR_CONFIG'))
    </div>
	
	@include('site::blocks.index_cards')
	
	<!-- Video block -->
	<div class="title-block-index">
	<h2>ВИДЕООБЗОРЫ FERROLI</h2>
    </div>

  
  @include('site::blocks.video_service')

  @include('site::announcement.section')

    
@endsection
