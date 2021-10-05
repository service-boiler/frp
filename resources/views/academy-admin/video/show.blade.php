@extends('layouts.app')
@section('title')@lang('site::academy.video.index')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.videos.index') }}">@lang('site::academy.video.index')</a>
            </li>
            <li class="breadcrumb-item active">{{$video->name}}</li>
        </ol>
        @alert()@endalert

         <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.videos.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.videos.edit', $video) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.video.add')</span>
            </a>
            
            <button type="submit" form="video-delete-form-{{$video->id}}" 
                                @cannot('delete', $video) disabled  @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $video) disabled @endcannot">
								<i class="fa fa-close"></i> 
                                <span class="d-none d-sm-inline-block" 
                                @cannot('delete', $video) data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.video.delete_cannot')" @endcannot
                                >@lang('site::messages.delete')</span>
            </button>
            <form id="video-delete-form-{{$video->id}}" action="{{route('academy-admin.videos.destroy', $video)}}" method="POST">
								 @csrf
								 @method('DELETE')
							</form>

        </div>
        
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0">{{$video->name}}</h4>
                    <div class="form-row">
                                <div class="col mb-3">
                                   {{$video->annotation}}
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                   <iframe width="1024" height="576" src="{{$video->link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                    </div>
                    
                
            </div>
		</div>
    </div>
@endsection
