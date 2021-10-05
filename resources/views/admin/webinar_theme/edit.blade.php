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
                <a href="{{ route('admin.webinar-themes.index') }}">@lang('site::admin.webinar_theme.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::admin.webinar_theme.webinar_theme_add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.webinar-themes.update',$webinarTheme->id) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.webinar_theme.name')</label>
                                    <input type="text" name="webinarTheme[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')"
                                           value="{{ old('webinarTheme[name]', $webinarTheme->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group" id="form-group-promocode_id">
                                <label class="control-label" for="promocode_id">@lang('site::admin.webinar_theme.promocode')</label>
                                <div class="input-group">
                                    <select data-form-action="{{ route('admin.promocodes.create') }}"
                                            data-btn-ok="@lang('site::messages.save')"
                                            data-btn-cancel="@lang('site::messages.cancel')"
                                            data-options="#promocode_id_options"
                                            data-label="@lang('site::messages.add') @lang('site::admin.promocodes.promocode')"
                                            class="dynamic-modal-form form-control{{  $errors->has('webinarTheme.promocode_id') ? ' is-invalid' : '' }}"
                                            name="webinarTheme[promocode_id]"
                                            id="promocode_id">
                                        
                                        
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($promocodes as $promocode)
                                            <option @if(old('webinarTheme[promocode_id]', isset($webinarTheme) ? $webinarTheme->promocode_id : null) == $promocode->id) selected @endif
                                                    value="{{ $promocode->id }}">
                                                {{ $promocode->name }} {{ $promocode->bonuses }} @lang('site::admin.bonus_val')
                                            </option>
                                        @endforeach
                                        <optgroup>
                                            <option value="load">✚ @lang('site::messages.add')</option>
                                        </optgroup>
                                        
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-@lang('site::trade.icon')"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('webinarTheme.promocode_id') }}</span>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::messages.comment')</label>
                                    <input type="text" name="webinarTheme[comment]"
                                           id="comment"
                                           class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                           value="{{ old('webinarTheme[comment]', $webinarTheme->comment) }}">
                                    <span class="invalid-feedback">{{ $errors->first('comment') }}</span>
                                </div>
                            </div>

                            
                </form>
					 
                <hr/>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <button name="_newwebinar" form="form" value="1" type="submit" class="btn btn-green mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::admin.webinar_theme.save_and_new_webinar')</span>
                        </button>
                        <a href="{{ route('admin.webinar-themes.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
