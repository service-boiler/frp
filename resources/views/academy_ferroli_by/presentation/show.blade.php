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
                <a href="{{ route('academy-admin.presentations.index') }}">@lang('site::academy.presentation.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $presentation->name }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $presentation->name }}</h1>

         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.presentations.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.presentations.edit', $presentation) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.presentation.add')</span>
            </a>
            
            <button type="submit" form="presentation-delete-form-{{$presentation->id}}" 
                                @cannot('delete', $presentation) disabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.presentation.delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $presentation) ssdisabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="presentation-delete-form-{{$presentation->id}}"
									action="{{route('academy-admin.presentations.destroy', $presentation)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    
        <div class="card mt-4">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-2 text-left text-sm-right">@lang('site::academy.presentation.annotation'):</dt>
                    <dd class="col-sm-10">{!!$presentation->annotation!!}</dd>
                    <dt class="col-sm-2 text-left text-sm-right">@lang('site::academy.presentation.text'):</dt>
                    <dd class="col-sm-10">{!!$presentation->text!!}</dd>
    
                </dl>
                
                @foreach($presentation->slides as $slide)
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="col">
                            <h5>{{$slide->name}}</h5>
                            <image src="{{$slide->image->src()}}">
                                <p class="mt-2 text-center">{!!$slide->text!!}</p>
                            
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
