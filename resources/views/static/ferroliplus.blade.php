@extends('layouts.app')
@section('title')@lang('site::mounting.masterplus')@lang('site::messages.title_separator')@endsection
@push('styles')

@endpush
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
   <!-- Advantages -->
   <section class="advantages">
     <div class="orange-line" style="margin-top: 10px;">
       <div class="card-container">
         <div class="row justify-content-center">
           <div class="card-col-2">
             <div class="card advant-card-2">
               <img src="images/managerplus-card.png" alt="">
               <div class="card-logo-left"><a href="{{ route('managerplus') }}"><img src="images/manager-plus-logo-shrink.png" alt=""></a></div>
               <p>
                 Программа лояльности для продавцов. Продавай оборудование Ferroli и получай бонусы от производителя! Пройди обучение и зарабатывай ЕЩЁ больше.
                 <br /><a href="{{ route('managerplus') }}">Подробнее</a>

               </p>
             </div>
           </div>
  
           <div class="card-col-2">
             <div class="card advant-card-2">
               <img src="images/masterplus-card.png" alt="">
               <div class="card-logo-left"><a href="{{ route('masterplus') }}"><img src="images/masterplus-logo.png" alt=""></a></div>
               <p>
                Программа лояльности для специалистов монтажа. Монтируй оборудование Ferroli и получай бонусы от производителя! 
                Чем больше монтажей, тем больше бонусов. <br /><a href="{{ route('masterplus') }}">Подробнее</a>
               </p>
             </div>
           </div>
  
           
  
         </div>
       </div>
   </section>
   
 
@endsection
