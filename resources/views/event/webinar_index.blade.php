@extends('layouts.app')

@section('title')@lang('site::webinar.index')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::webinar.index'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['url' => route('academy'), 'name' => __('site::event.academy')],
            ['name' => __('site::webinar.index')]
        ]
    ])
@endsection
@section('content')
 <div class="container">
 @alert()@endalert()
	<div class="academy-index-header"><img src="{{asset('images/academy_logo_index.png')}}"></div>
		
			<div class="row justify-content-sm-center">
				<div class="col-12 col-xl-5 col-lg-4 col-md-6 col-sm-10 academy-index">
				<h4>Академия Ferroli - мы всегда рядом с профессионалом.</h4>
					<p>Обучения технических специалистов по всей стране, вебинары и online курсы.</p>
 
					<p>Мы предлагаем всем профессионалам в области ремонта и технического обслуживания котельного оборудования посетить наши семинары, с расписанием которых вы можете ознакомиться на этой странице.

<p>Пройдите обучение в очной или заочной форме. </p>
<p>Получите знания об особенностях и методах ремонта оборудования Ferroli.</p>
<p>Сдайте экзамен и получите сертификат на право монтажа, пуска, сервисного обслуживания и ремонта котельного оборудования FERROLI.</p>
				</div>
				 <div class="col-12 col-xl-7 col-lg-8 col-md-6 col-sm-10 academy-index">
				 <a name="filters"></a>
					<h3>Список запланированных вебинаров</h3>
					@filter2(['repository' => $repository])@endfilter2     
					<div class="row news-list">
						@each('site::event.index.webinar', $webinars, 'webinar')
					</div>
					@pagination(['pagination' => $webinars])@endpagination
					{{$webinars->render()}}
				</div>  
			</div>  
</div> 
@endsection
