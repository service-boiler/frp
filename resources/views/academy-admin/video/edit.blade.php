@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::academy.video.index')</li>
        </ol>
        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('academy-admin.videos.update',$video) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.video.name')</label>
                                    <input type="text" name="video[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('video.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')"
                                           value="{{ old('video[name]', $video->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('video.name') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="link">@lang('site::academy.video.link')</label>
                                    <input type="text" name="video[link]"
                                           id="link"
                                           class="form-control{{ $errors->has('video.link') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::academy.placeholder.youtube_link')"
                                           value="{{ old('video[link]', $video->link) }}">
                                    <span class="invalid-feedback">{{ $errors->first('video.link') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="annotation">@lang('site::academy.video.annotation')</label>
                                    <input type="text" name="video[annotation]"
                                           id="annotation"
                                           class="form-control{{ $errors->has('video.annotation') ? ' is-invalid' : '' }}"
                                           value="{{ old('video[annotation]', $video->annotation) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('video.annotation') }}</span>
                                </div>
                    </div>
                <form>
                 <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('academy-admin.videos.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
		</div>
    </div>
@endsection
