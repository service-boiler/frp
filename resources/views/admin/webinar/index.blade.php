@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.webinar.index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.webinar.icon')"></i> @lang('site::admin.webinar.index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('ferroli-user.webinars.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.webinar.webinar_add')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
            
								@foreach($webinars as $webinar)
                                <div class="border p-3 mb-2">
                                    <div class="row">
                                    <div class="col-sm-10">
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                        <a href="{{route('admin.webinars.show', $webinar)}}">{{ $webinar->name }}</a>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-12">
                                        Тема: {{ $webinar->theme->name }}</a>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-sm-2">
                                            {{ $webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : '-'  }}
                                        </div>
                                        <div class="col-sm-1">
                                            {{ $webinar->type->name  }}
                                        </div>
                                        <div class="col-sm-9">
                                            {{ $webinar->link_service  }}
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                    <div class="col-sm-7">
                                        @if(!empty($webinar->promocode))
                                        Баллы отдельно от темы: {{ $webinar->promocode->bonuses }} @lang('site::admin.bonus_val') ({{ $webinar->promocode->name }})
                                        @elseif(!empty($webinar->theme->promocode))
                                        Баллы по теме: {{ $webinar->theme->promocode->bonuses }} @lang('site::admin.bonus_val') ({{ $webinar->theme->promocode->name }})
                                        @endif
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
