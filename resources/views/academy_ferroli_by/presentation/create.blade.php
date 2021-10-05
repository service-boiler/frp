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
                <a href="{{ route('academy-admin.presentations.index') }}">@lang('site::academy.presentation.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::academy.presentation.add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body" id="summernote">

                <form id="form" method="POST" action="{{ route('academy-admin.presentations.store') }}">
                    @csrf
                   
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.presentation.name')</label>
                                    <input type="text" name="presentation[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('presentation.name') ? ' is-invalid' : '' }}"
                                           value="{{ old('presentation[name]') }}">
                                    <span class="invalid-feedback">{{ $errors->first('presentation.name') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="annotation">@lang('site::academy.presentation.annotation')</label>
                                    <input type="text" name="presentation[annotation]"
                                           id="annotation"
                                           class="form-control{{ $errors->has('presentation.annotation') ? ' is-invalid' : '' }}"
                                           value="{{ old('presentation[annotation]') }}">
                                    <span class="invalid-feedback">{{ $errors->first('presentation.annotation') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="annotation">@lang('site::academy.presentation.text')</label>
                                
                                    <textarea
                                           name="presentation[text]"
                                           id="text"
                                           required
                                           class="summernote form-control{{ $errors->has('presentation.text') ? ' is-invalid' : '' }}"
                                           >{{ old('presentation[text]') }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('presentation.text') }}</span>
                                </div>
                    </div>
                    
                    
                    <div class="form-row ml-2">
                     <h5>@lang('site::academy.presentation.slides')</h5>
                     </div>
                     
                    <fieldset id="presentation_slides-list">
                        @if( is_array(old('presentation_slide')) )
                            @foreach(old('presentation_slide') as $random => $presentation_slide)
                                @include('site::academy-admin.presentation_slide.create', compact('random'))
                            @endforeach
                        @endif
                        
                    </fieldset>

                    <div class="form-row mt-3">
                        <div class="col text-left">

                            <a href="javascript:void(0);" class="btn btn-ms mb-1 presentation_slide-add"
                               data-action="{{route('academy-admin.presentation_slides.create')}}">
                                <i class="fa fa-plus"></i>
                                <span>@lang('site::messages.add') @lang('site::academy.presentation.slide_add')</span>
                            </a>
                        </div>
                    </div>      
                            
                </form>
				
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('academy-admin.presentations.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection