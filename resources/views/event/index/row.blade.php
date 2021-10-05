    <div class="academy-index-card">
        <div class="academy-index-card-header" style="line-height: 1.3em;">
			{{$event->title}}
		</div>
		<div class="academy-index-card-address">
			@if($event->type->is_webinar==1)
			<!--<a href="{{$event->webinar_link}}" target="_blank">@lang('site::event.webinar_link')</a> -->
			@else
			{{$event->address}}
			@endif
		</div>
		<div class="academy-index-card-footer">
			<div class="academy-index-card-date">{{$event->date_from->format('d.m.Y')}} @if($event->date_from->format('d.m.Y') != $event->date_to->format('d.m.Y')) - {{$event->date_to->format('d.m.Y')}} @endif </div>
			<div class="academy-index-card-btn">
			@if($event->type->is_webinar==1)
            <a class="btn btn-ms" href="{{route('events.webinars.show', $event)}}">@lang('site::event.more')</a>
			<!-- <a class="btn btn-ms" href="{{$event->webinar_link}}" target="_blank">@lang('site::event.webinar_enter')</a> -->
			@else
                <a class="btn btn-ms" href="{{route('events.show', $event)}}">@lang('site::event.register')</a>
			@endif
            
			</div>
		</div>
		
	</div>
