@extends('layouts.app')

@section('title'){{$event->title}}@lang('site::messages.title_separator')@endsection

@section('content')

    <div class="container">
        @alert()@endalert()
        <div class="row my-5">
        
            <div class="col-md-12 news-content">
                 <h1>{{$event->title}}</h1>

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::event.region_id')</dt>
                    <dd class="col-sm-8">{{$event->region->name}}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::event.city')</dt>
                    <dd class="col-sm-8">{{$event->city}}</dd>
                    @if($event->hasAddress())
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::event.address')</dt>
                        <dd class="col-sm-8">{{$event->address}}</dd>
                    @endif
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::event.date')</dt>
                    <dd class="col-sm-8">
                        @if($event->date_from == $event->date_to)
                            {{ $event->date_from->format('d.m.Y') }}
                        @else
                            @lang('site::event.date_from') {{ $event->date_from->format('d.m.Y') }}
                            @lang('site::event.date_to') {{ $event->date_to->format('d.m.Y') }}
                        @endif
                    </dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">
                    @if($event->image->fileExists)
                    <img class="card-img-top" style="width: 100%;" src="{{Storage::disk($event->image->storage)->url($event->image->path)}}" alt="">
                    @endif
                    </dt>
                    
                    <dd class="col-sm-8">
                    <div class="bg-lightest font-italic p-4 text-big">{{$event->annotation }}</div>
                    @if($event->hasDescription()){!! $event->description !!}@endif
                    </dd>
                </dl>
                
                @if($event->members()->where('status_id', 2)->exists())
                    <h3 class="mb-2">@lang('site::member.members')</h3>
                    @foreach($event->members()->where('status_id', 2)->get() as $member)
                        @include('site::member.event.row')
                    @endforeach
                @endif
                <div class="text-center my-4">
                @if($event->type->is_webinar==1 && $event->hasWebinarlink())
            <a class="btn btn-ms" href="{{$event->webinar_link}}" target="_blank">@lang('site::event.webinar_link')</a>
			<!-- <a class="btn btn-ms" href="{{$event->webinar_link}}" target="_blank">@lang('site::event.webinar_enter')</a> -->
			@else
                    <a class="btn btn-ms" href="{{route('members.register', $event)}}">
                        @lang('site::event.register')
                    </a>
                    @endif
                    <a class="btn btn-outline-ferroli ml-0 ml-sm-3 d-block d-sm-inline-block"
                       href="{{route('academy')}}#filters">@lang('site::event.help.other')</a>
                    @admin()
                    <a class="btn btn-warning ml-0 ml-sm-3 d-block d-sm-inline-block"
                       href="{{route('ferroli-user.events.show', $event)}}">
                        <i class="fa fa-folder-open"></i>
                        @lang('site::messages.open_admin')
                    </a>
                    @endadmin()
                </div>
            </div>
        </div>
    </div>
@endsection
