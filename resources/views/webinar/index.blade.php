@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.webinar.index')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::admin.webinar.index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.home')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
            
								@foreach($webinars as $webinar)
                                <div class="border p-3 mb-2">
                                    <div class="row">
                                    <div class="col-sm-10">
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                        <a href="{{route('webinars.show', $webinar)}}">{{ $webinar->name }}</a>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                            {{ $webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : '-'  }}
                                        </div>
                                        <div class="col-sm-5">
                                        Тема: {{ $webinar->theme->name }}</a>
                                        </div>
                                   </div>
                                   <div class="row mb-2">
                                        <div class="col-sm-12">
                                        Аннотация: {{ $webinar->annotation }}</a>
                                        </div>
                                   </div>
                                    
                                    </div>
                                    <div class="col-sm-2">
                                    <img style="width:100%;" src="{{$webinar->image()->exists() ? $webinar->image->src() : Storage::disk($webinar->image->storage)->url($webinar->image->path)}}" alt="">
                                    </div>
                                    </div>
                                       
										
                                        
                                </div>
                                @endforeach
            
			
		
    </div>
@endsection
