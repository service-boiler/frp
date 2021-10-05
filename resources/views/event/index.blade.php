@extends('layouts.app')

@section('title')@lang('site::event.events')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::event.events'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::event.events')]
        ]
    ])
@endsection
@section('content')
 <div class="container">
 @alert()@endalert()
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
					<h3>Список ближайших обучений</h3>
					@filter2(['repository' => $repository])@endfilter2     
					<div class="row news-list">
						@each('site::event.index.row', $events, 'event')
					</div>
					@pagination(['pagination' => $events])@endpagination
					{{$events->render()}}
				</div>  
			</div>  
</div> 
@endsection
