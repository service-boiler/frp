@extends('layouts.app')

@section('title')@lang('site::event.academy')@lang('site::messages.title_separator')@endsection


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
</header>
@endsection


@section('content')
<div class="container">
	<div class="academy-index-header"><img src="{{asset('images/academy_logo_index.png')}}"></div>
		@foreach($types as $type)
        @endforeach
			<div class="row justify-content-sm-center">
				<div class="col-12 col-xl-5 col-lg-4 col-md-6 col-sm-10 academy-index">
				<h4>Академия Ferroli - мы всегда рядом с профессионалом.</h4>
					<p>Обучения технических специалистов по всей стране, вебинары и online курсы.</p>
 
					<p>Мы предлагаем всем профессионалам в области ремонта и технического обслуживания котельного оборудования посетить наши семинары, с расписанием которых вы можете ознакомиться на этой странице.

<p>Пройдите обучение в очной или заочной форме. </p>
<p>Получите знания об особенностях и методах ремонта оборудования Ferroli.</p>
<p>Сдайте экзамен и получите сертификат на право монтажа, пуска, сервисного обслуживания и ремонта котельного оборудования FERROLI.</p>

<p>Оставьте заявку на проведение обучения в Вашем регионе.</p>

<p>Либо зарегистрируйтесь на запланированное мероприятие в Вашем городе.</p>
				<a href="{{route('members.create')}}" class="btn btn-ms">@lang('site::messages.leave') @lang('site::member.member')</a>
				</div>
				 <div class="col-12 col-xl-7 col-lg-8 col-md-6 col-sm-10 academy-index">
				 <a name="filters"></a>
					<h3>Список ближайших семинаров</h3>
					
					<div class="row news-list">
						@each('site::event.index.row', $events, 'event')
					</div>
					<a href="{{route('events.index')}}" class="btn btn-ms">Полный список семинаров и других мероприятий</a>
                    
                    
                    <br />
                    <br />
                    <br />
					<h3>Список ближайших вебинаров</h3>
					 
					<div class="row news-list">
						@each('site::event.index.webinar', $webinars, 'webinar')
					</div>
					<a href="
                    @auth() {{route('webinars.index')}} 
                    @else
                    {{route('events.webinars.index')}}
                    @endauth()
                    " class="btn btn-ms">Все вебинары</a>
				</div>  
			</div>  
</div>  

<hr style="border-top: 1px solid #fe7902; margin-top: 60px;">			
<div class="container">
<div class="academy-motivation">
	<div class="row justify-content-sm-center">
			<div class="col-12 col-xl-5 col-lg-4 col-md-6 col-sm-10 academy-index ac-mt-l">
			<h4>ИНВЕСТИРУЙ В СЕБЯ</h4>
			<p>Потратьте часть своего времени на обучения, чтобы повысить квалификацию и продолжить успешно работать в области ремонта и обслуживания котельного оборудования.
			Будьте в курсе новых технологий, информацию о которых дает вам АКАДЕМИЯ. Применяйте свои знания в быстро развивающемся рынке, будьте успешны с FERROLI. </p>
			</div>

			<div class="col-12 col-xl-7 col-lg-7 col-md-6 col-sm-10">
			<div class="col-12 ac-quadres">
					<div class="col-sm-6 ac-quadro"><div class="ac-quadro-content"><b>{{$vars['academy_learn_heads_last']}}</b><br />человек<br />обучено<br />в {{$vars['academy_learn_year_last']}} году </div></div>
					<div class="col-sm-6 ac-quadro"><div class="ac-quadro-content"><b>{{$vars['academy_learn_count_last']}}</b><br />проведено<br />обучений<br />в {{$vars['academy_learn_year_last']}} году</div></div>
			</div>
			<div class="col-12 ac-quadres">
					<div class="col-sm-6 ac-quadro"><div class="ac-quadro-content"><b>{{$vars['academy_learn_heads_current']}}</b><br />человек<br />обучено<br />в {{$vars['academy_learn_year_current']}} году </div></div>
					<div class="col-sm-6 ac-quadro"><div class="ac-quadro-content"><b>{{$vars['academy_learn_count_current']}}</b><br />проведено<br />обучений<br />в {{$vars['academy_learn_year_current']}} году</div></div>
			</div>
			</div>

			</div>			
	</div>
</div>
</div>
<div class="ac-of-h">
Академии Ferroli
</div>

<div class="main-container">
 		<div class="col-12 ac-ofs">
			<div class="col-sm-6 ac-of"><div class="ac-of-content-img" style="background-image: url('/images/ac-fanipol.jpg');"></div></div>
			<div class="col-sm-6 ac-of"><div class="ac-of-content">
				<h4>АКАДЕМИЯ В ФАНИПОЛЕ</h4>
					<p>
					- площадь <b>50 м2</b><br />
					- <b>7</b> функциональных продуктов<br />
					- <b>1</b> техническое помещение<br />
					&nbsp; &nbsp; вместимостью <b>20</b> человек<br />
					</p><p>
					<span class="rng">адрес:</span><br />
					Республика Беларусь, Минская область<br />
					г. Фаниполь, ул.Заводская,45 (СЭЗ)<br />

					<a href="https://yandex.ru/maps/-/C0TlrTij" target="_blank">посмотреть на карте</a></p>
				</div></div>
			
		</div>
		<div class="col-12 ac-ofs" style="margin-top: -3px;">
			<div class="col-sm-6 ac-of  mobile"><div class="ac-of-content-img" style="background-image: url('/images/ac-verona.jpg');"></div></div>
			<div class="col-sm-6 ac-of">
				<div class="ac-of-content ">
					<h4>АКАДЕМИЯ В ВЕРОНЕ</h4>
					<p>
					- площадь <b>600 м2</b> <br />
					- <b>50</b> функциональных продуктов<br />
					- <b>3</b> технических помещения<br />
					&nbsp; &nbsp; вместимостью <b>80</b> человек<br />
					</p><p>
					<span class="rng">адрес:</span><br />
					via Marco Polo 15, 37047 - San Bonifacio (VR)<br />

					<a href="https://goo.gl/maps/SsW9FDT1EjCiPkhNA" target="_blank">посмотреть на карте</a></p>
				</div>
			</div>
			<div class="col-sm-6 ac-of desktop"><div class="ac-of-content-img" style="background-image: url('/images/ac-verona.jpg');"></div></div>
			
		</div>
 		<div class="col-12 ac-ofs" style="margin-top: -3px; border-bottom: 1px solid #fe7902;">
			<div class="col-sm-6 ac-of"><div class="ac-of-content-img" style="background-image: url('/images/ac-bolonia.jpg');"></div></div>
			<div class="col-sm-6 ac-of"><div class="ac-of-content">
			<h4>АКАДЕМИЯ В БОЛОНЬЕ</h4>
				<p>
				- площадь <b>250 м2</b><br />
				- <b>35</b> функциональной продукции<br />
				- <b>1</b> техническое помещение <br />
				&nbsp; &nbsp; (вместимость <b>25</b> человек)<br />
				- <b>1</b> конференц-зал <br />
				&nbsp; &nbsp; (вместимость <b>10</b> человек)<br />
				</p><p>
				<span class="rng">адрес</span><br />
				via del Legatore 3, 40138 - Bologna<br />

				<a href="https://goo.gl/maps/EHxzsiVKdHusfJVFA" target="_blank">посмотреть на карте</a></p>
			
			</div></div>
							
		</div>
		<div class="col-12 ac-ofs" style="margin-top: -3px;">
			<div class="col-sm-6 ac-of"><div class="ac-of-content">
			<h4 style="padding-top: 30px;">ЗНАНИЯ - ЭТО СИЛА</h4>
				<p>Помимо выездных обучающих семинаров АКАДЕМИЯ Ferroli работает и в online режиме.
					Мы приветствуем всех желающих получить знания на наших вебинарах, пройти курс обучения на нашем <a href="https://www.youtube.com/channel/UC9q1SNmiWzsFUysN-LPXHnw/videos">Youtube канале</a>.
					Дистанционное обучение, подтвержденное тестированием так же дает вам возможность получения сертификата.
		</div></div>
			<div class="col-sm-6 ac-of"><div class="ac-of-content">
			<div>
			<div><img src="/images/ac-lpow.jpg" style="max-width:100%;height:auto;"></div>
			</div>
			
			</div></div>
			
		</div>	
			
 		
</div>
			

@endsection