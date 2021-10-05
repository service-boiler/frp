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
                <a href="{{ route('admin.black-list.index') }}">@lang('site::admin.black_list.black_list')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::admin.black_list.address')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('admin.black-list.store') }}">
                    @csrf
                    
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="route">@lang('site::admin.black-list.web')</label>
                            <input type="text" name="web"
                                   id="web"
                                   required
                                   class="form-control{{ $errors->has('web') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::page.placeholder.web')"
                                   value="{{ old('web') }}">
                            <span class="invalid-feedback">{{ $errors->first('web') }}</span>
                        </div>
                    </div>
                    
                    <div class="form-row ">
                        <div class="col mb-3">
                            <label class="control-label" for="route">@lang('site::admin.black-list.name')</label>
                            <input type="text" name="name"
                                   id="name"
                                   
                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::page.placeholder.name')"
                                   value="{{ old('name') }}">
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    
                    <div class="form-row ">
                        <div class="col mb-3">
                            <label class="control-label" for="route">@lang('site::admin.black-list.full')</label>
                            <input type="text" name="full"
                                   id="full"
                                   
                                   class="form-control{{ $errors->has('full') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::page.placeholder.full')"
                                   value="{{ old('full') }}">
                            <span class="invalid-feedback">{{ $errors->first('full') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="route">@lang('site::admin.black-list.comment')</label>
                            <input type="text" name="comment"
                                   id="comment"
                                   class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::page.placeholder.comment')"
                                   value="{{ old('comment') }}">
                            <span class="invalid-feedback">{{ $errors->first('comment') }}</span>
                        </div>
                    </div>
                    <input type="hidden" name="active" value="1">
                    
                </form>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form-content" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.black-list.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection