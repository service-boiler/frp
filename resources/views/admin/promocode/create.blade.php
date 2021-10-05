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
                <a href="{{ route('admin.promocodes.index') }}">@lang('site::admin.promocodes.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::admin.promocodes.promocode')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.promocodes.store') }}">
                    @csrf
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.promocodes.name')</label>
                                    <input type="text" name="promocode[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('promocode.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')"
                                           value="{{ old('promocode.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.name') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocodes.bonuses')</label>
                                    <input type="number" name="promocode[bonuses]"
                                           id="bonuses"
                                           required
                                           class="form-control{{ $errors->has('promocode.bonuses') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.bonuses') }}">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.bonuses') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label"
                                       for="expiry">@lang('site::admin.promocodes.expiry_at')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_expiry"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="promocode[expiry]"
                                           id="expiry"
                                           maxlength="10"
                                           placeholder="@lang('site::admin.promocodes.expiry_placeholder')"
                                           data-target="#datetimepicker_expiry"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('promocode.expiry') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.expiry') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_expiry"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('promocode.expiry') }}</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocodes.short_token')</label>
                                    <input type="text" name="promocode[short_token]"
                                           id="short_token"
                                           class="form-control{{ $errors->has('promocode.short_token') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.short_token') }}">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.short_token') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::messages.comment')</label>
                                    <input type="text" name="promocode[comment]"
                                           id="comment"
                                           class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.comment') }}">
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
                        <a href="{{ route('admin.promocodes.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection