    <div class="academy-index-card">
        <div class="academy-index-card-header" style="line-height: 1.3em;">
			{{$webinar->name}}
		</div>
		<div class="academy-index-card-address">
			@if($webinar->annotation)
            {{$webinar->annotation}}
			@endif
		</div>
		<div class="academy-index-card-footer">
			<div class="academy-index-card-date">{{$webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : " "}}</div>
			<div class="academy-index-card-btn">
			<a class="btn btn-ms" href="{{route('events.webinars.show', $webinar)}}">@lang('site::event.more')</a>
            
			</div>
		</div>
		
	</div>
