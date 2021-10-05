@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.stages.index') }}">@lang('site::academy.stage.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $stage->name }}</li>
        </ol>
      
         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.stages.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.stages.edit', $stage) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.stage.add')</span>
            </a>
            
            <button type="submit" form="stage-delete-form-{{$stage->id}}" 
                                @cannot('delete', $stage) disabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.stage_delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $stage) ssdisabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="stage-delete-form-{{$stage->id}}"
									action="{{route('academy-admin.stages.destroy', $stage)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    
        <div class="card mb-5">
            <div class="card-body">
                    <dl class="row">
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.name_show'):</dt>
                    <dd class="col-sm-9">{!!$stage->name!!}</dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.theme'):</dt>
                    <dd class="col-sm-9">{!!$stage->theme->name!!}</dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.annotation'):</dt>
                    <dd class="col-sm-9">{!!$stage->annotation!!}</dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.presentations'):</dt>
                    <dd class="col-sm-9">@foreach($stage->presentations as $presentation)
                                          <p class="mb-2">{{$presentation->name}} <a target="_blank" href="{{ route('academy-admin.presentations.show',$presentation) }}"><i class="fa fa-external-link"></i> </a></p>
                                          @endforeach</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.videos'):</dt>
                    <dd class="col-sm-9">@foreach($stage->videos as $video)
                                          <p class="mb-2">{{$video->name}} <a target="_blank" href="{{ route('academy-admin.videos.show',$video) }}"><i class="fa fa-external-link"></i> </a></p>
                                          @endforeach</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.tests'):</dt>
                    <dd class="col-sm-9">@foreach($stage->tests as $test)
                                          <p class="mb-2">{{$test->name}} <a target="_blank" href="{{ route('academy-admin.tests.edit',$test) }}"><i class="fa fa-external-link"></i> </a></p>
                                          @endforeach</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.stage.is_required'):</dt>
                    <dd class="col-sm-9">@bool(['bool' => $stage->is_required])@endbool</dd>
                       
                </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
