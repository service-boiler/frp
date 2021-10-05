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
<section class="masterplus">
     <div class="img-block pos-img masterplus__arrow-down">
       <img src="images/mp-down.png">
     </div>
  
     <div class="title-block title-block--gray"  style="padding: 30px;">
        <div class="container">
          <h1>4 УРОВНЯ <span class="rng">ОБНОВЛЕННОЙ</span> БОНУСНОЙ ПРОГРАММЫ</h1>
        </div>
     </div>
     
      <div style="margin-top: 20px;">
       <div class="card-container">
         <div class="row justify-content-center">
           <div class="card-col">
           <div class="card advant-card-header-white advant-card-col-left">
             Start Ferroli
             </div>
             <div class="card advant-card-left-gray">
               <p>
                 Участник у которого есть:
                 <li>личный кабинет на сайте service.ferroli.ru</li>
               </p>
             </div>
           </div>
  
           <div class="card-col">
           
             <div class="advant-card-col-left card advant-card-header-white">
             Master Ferroli
             </div>
             <div class="card advant-card-left-gray">
               <p>
                 Участник у которого есть:
                 <li>личный кабинет на сайте service.ferroli.ru</li>
                 <li>сертификат о прохождении обучения от Ferroli (живое обучение или видеокурс и тестирование в личном кабинете)</li>
               </p>
             </div>
           </div>
  
           <div class="card-col">
           <div class="card advant-card-header-white advant-card-col-left">
             Profi Ferroli
             </div>
             <div class="card advant-card-left-gray">
               <p>
                 Участник у которого есть:
                 <li>личный кабинет на сайте service.ferroli.ru</li>
                 <li>сертификат о прохождении обучения от Ferroli (живое обучение или видеокурс и тестирование в личном кабинете)</li>
                 <li>более 3-х отчетов по монтажам котлов Ferroli</li>
               </p>
             </div>
           </div>
  
           <div class="card-col">
           <div class="card advant-card-header-white">
             SuperProfi Ferroli
             </div>
             <div class="card advant-card-left-gray">
               <p>
               Участник у которого есть:
                 <li>личный кабинет на сайте service.ferroli.ru</li>
                 <li>сертификат о прохождении обучения от Ferroli (живое обучение или видеокурс и тестирование в личном кабинете)</li>
                 <li>более 10-ти отчетов по монтажам котлов Ferroli</li>
               </p>
             </div>
           </div>
  
         </div>
       </div>
      <div class="btn-block mb-4 mt-4"><a href="{{route('home')}}" class="btn header-btn">Правила и таблицу вознаграждений по каждому уровню можно скачать в личном кабинете</a></div>
  
     <div class="title-block title-block--gray"  style="padding: 30px;">
        <div class="container">
          <h1>Как стать участником бонусной программы?</h1>
        </div>
     </div>
  
     <div class="container">
       <!-- Step list -->
       <ul class="list part-step-list">
         <li>
           <span class="part-step-list__step">Регистрация</span>
           <p class="part-step-list__desc">Зарегистрируйтесь на сайте <a href="{{route('register_fl')}}">service.ferroli.ru</a>, 
           пройдите обучение онлайн и получите сертификат (обучающее видео будет находиться в Вашем личном кабинете)</p>
         </li>
  
         <li>
           <span class="part-step-list__step">Монтаж</span>
           <p class="part-step-list__desc">Устанавливайте котлы Ferroli и сообщайте нам <br />(отчет по монтажу формируется в Вашем личном кабинете)</p>
         </li>
  
         <li>
           <span class="part-step-list__step">Бонусы</span>
           <p class="part-step-list__desc">Получайте бонусы на банковскую карту/телефон или совершайте покупки в магазинах-партнерах за полученные баллы</p>
         </li>
       </ul>
  
       <div class="btn-block"><a href="{{route('register_fl')}}" class="btn block-btn">Стать участником</a></div>
  
       <div class="img-block pos-img masterplus__bg-img"><img src="images/master-plus-bg.png" alt="master-plus"></div>
     </div>
  
   </section>
   
   <!-- Advantages -->
   <section class="advantages">
     <div class="title-block title-block--gray">
        <div class="container">
          <h2>В чем преимущество?</h2>
          <div class="img-block" ><img src="images/masterplus-logo.png" style="max-width: 250px;" alt="master-plus"></div>
        </div>
     </div>
  
     <div class="orange-line" style="margin-top: 20px;">
       <div class="card-container">
         <div class="row justify-content-center">
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-1.svg" alt="">
               <p>
                 Увеличение дохода за установку оборудования Ferroli.
                 <br />Участники программы сами влияют на уровень вознаграждения
               </p>
             </div>
           </div>
  
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-2.svg" alt="">
               <p>
                 Бесплатное онлайн обучение и выдача сертификата, подтвержающего Вашу компетентность
               </p>
             </div>
           </div>
  
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-3.svg" alt="">
               <p>
                 Поддержка нашими специалистами. Ответы на вопросы по оборудованию Ferroli
               </p>
             </div>
           </div>
  
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-4.svg" alt="">
               <p>
                 Получение заказов с сайта <br />(в случае дополнительной авторизации юридического лица)
               </p>
             </div>
           </div>
  
         </div>
       </div>
   </section>
   
   @include('site::announcement.section')
    
   <div class="title-block title-block--gray title-social-block">
    <div class="container">
      <div class="row justify-content-center">
        <div class="title-social-block-col mt-3">
          <h4 class="block-title">Присоединяйтесь к команде</h4>
        </div>

        <div class="title-social-block-col">
          <div class="social-block">
            <div class="row">
               <div class="col"><a href="https://www.instagram.com/ferroli_rus_bel/" class="social-link"><img src="images/inst-icon.svg" alt="Instagram"></a></div>
               <div class="col"><a href="{{config('site.youtube_channel')}}" class="social-link" target="_blank"><img src="images/yt-icon.svg" alt="Youtube"></a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  
   </div>
  
    <!-- Video carousel -->
    <div class="video-carousel">
      
      <div class="container">

      <div class="owl-carousel js-videoBlock">
         <div class="iframe-wrapper">
           <iframe src="https://www.youtube.com/embed/e9NFHwgSWEU" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
         </div>

         <div class="iframe-wrapper">
           <iframe src="https://www.youtube.com/embed/LWyg9cUZJdE" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
         </div>

         <div class="iframe-wrapper">
           <iframe src="https://www.youtube.com/embed/ryVi-lyE2-s" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
         </div>
      </div>

      </div>
    </div>
  
  <div class="site-form js-siteForm">
    <i class="icon site-form-arrow js-siteFormOpen"></i>

    <div class="site-form-wrapper">
      <div class="row">
        <div class="img-block js-siteFormOpen"><img src="images/chain.png" alt="Ferroli"></div>
        <div class="desc"><a href="{{route('register_fl')}}">Присоединяйся и зарабатывай с Ferroli</a></div>
        <div class="form-close">
          <span class="site-form-close icon js-siteFormClose"></span>
        </div>
      </div>
    </div>
  </div>
@endsection
