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
                <a href="{{ route('admin.variables.index') }}">@lang('site::admin.variables')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') @lang('site::admin.variable_value')</li>
        </ol>
        <h1 class="header-title mb-4"> {{$variable->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="variable-edit-form"
                              action="{{ route('admin.variables.update', $variable) }}">

                            @csrf
                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="value">@lang('site::admin.variable_value')</label>
                                    <input type="text"
                                           name="value"
                                           id="value"
                                           required
                                           class="form-control{{$errors->has('value') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::variable.placeholder.value')"
                                           value="{{ old('value', $variable->value) }}">
                                    <span class="invalid-feedback">{{ $errors->first('value') }}</span>
                                </div>

                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="comment">@lang('site::admin.variable_comment')</label>
                                    <input type="text"
                                           name="comment"
                                           id="comment"
                                          
                                           class="form-control{{$errors->has('value') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::admin.placeholder.variable_comment')"
                                           value="{{ old('value', $variable->comment) }}">
                                    <span class="invalid-feedback">{{ $errors->first('value') }}</span>
                                </div>

                            </div>
                            <hr />
                            <div class=" text-right">
                                <button form="variable-edit-form"type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.variables.index') }}" class="d-block d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
