@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.event_types.index') }}">@lang('site::event_type.event_types')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::event_type.event_type')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.event_types.store') }}">
                    @csrf

                    <div class="form-row">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('event_type.is_webinar')) checked @endif
                                       class="custom-control-input{{  $errors->has('event_type.is_webinar') ? ' is-invalid' : '' }}"
                                       id="is_webinar"
                                       name="event_type[is_webinar]">
                                <label class="custom-control-label"
                                       for="is_webinar">@lang('site::event.is_webinar')</label>
                                <span class="invalid-feedback">{{ $errors->first('event_type.is_webinar') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('event_type.show_ferroli')) checked @endif
                                       class="custom-control-input{{  $errors->has('event_type.show_ferroli') ? ' is-invalid' : '' }}"
                                       id="show_ferroli"
                                       name="event_type[show_ferroli]">
                                <label class="custom-control-label"
                                       for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                <span class="invalid-feedback">{{ $errors->first('event_type.show_ferroli') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('event_type.show_lamborghini')) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('event_type.show_lamborghini') ? ' is-invalid' : '' }}"
                                       id="show_lamborghini"
                                       name="event_type[show_lamborghini]">
                                <label class="custom-control-label"
                                       for="show_lamborghini">@lang('site::messages.show_lamborghini')</label>
                                <span class="invalid-feedback">{{ $errors->first('event_type.show_lamborghini') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-row required mt-3">
                        <div class="col">
                            <label class="control-label" for="name">@lang('site::event_type.name')</label>
                            <input required
                                   type="text"
                                   name="event_type[name]"
                                   id="name"
                                   class="form-control{{ $errors->has('event_type.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::event_type.placeholder.name')"
                                   value="{{ old('event_type.name') }}">
                            <span class="invalid-feedback">{{ $errors->first('event_type.name') }}</span>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label"
                                   for="annotation">@lang('site::event_type.annotation')</label>
                            <textarea required
                                      name="event_type[annotation]"
                                      class="form-control{{ $errors->has('event_type.annotation') ? ' is-invalid' : '' }}"
                                      placeholder="@lang('site::event_type.placeholder.annotation')"
                                      id="annotation">{{ old('event_type.annotation') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('event_type.annotation') }}</span>
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col">
                            <label class="control-label" for="title">@lang('site::event_type.title')</label>
                            <input type="text"
                                   name="event_type[title]"
                                   id="title"
                                   class="form-control{{ $errors->has('event_type.title') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::event_type.placeholder.title')"
                                   value="{{ old('event_type.title') }}">
                            <span class="invalid-feedback">{{ $errors->first('event_type.title') }}</span>
                        </div>
                    </div>

                    <div class="form-row mt-3">
                        <div class="col">
                            <label class="control-label" for="meta_description">@lang('site::event_type.meta_description')</label>
                            <input type="text"
                                   name="event_type[meta_description]"
                                   id="meta_description"
                                   class="form-control{{ $errors->has('event_type.meta_description') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::event_type.placeholder.meta_description')"
                                   value="{{ old('event_type.meta_description') }}">
                            <span class="invalid-feedback">{{ $errors->first('event_type.meta_description') }}</span>
                        </div>
                    </div>

                </form>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="image_id">@lang('site::event_type.image_id')</label>

                                <form method="POST"
                                      enctype="multipart/form-data"
                                      action="{{route('admin.images.store')}}">
                                    @csrf
                                    <input type="hidden"
                                           name="storage"
                                           value="event_types"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.event_types.accept')}}"
                                           name="path"/>

                                    <input type="button" class="btn btn-ms image-upload-button"
                                           value="@lang('site::messages.load')"/>
                                    <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="images" class="row bg-white">
                            @include('site::admin.image.edit')
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button form="form" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.event_types.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection