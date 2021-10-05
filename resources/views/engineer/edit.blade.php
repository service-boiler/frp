@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('engineers.index') }}">@lang('site::engineer.engineers')</a>
            </li>
            <li class="breadcrumb-item">{{ $engineer->name }}</li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::engineer.engineer')</h1>

        @alert()@endalert

        <div class="card mt-2 mb-2">
            <div class="card-body">

                @include('site::engineer.form.edit')

                <div class="form-row">
                    <div class="col text-right">
                        <button form="form-content" type="submit" class="btn btn-ms  mr-0 mr-sm-1 mb-1 mb-sm-0 d-block d-sm-inline">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('engineers.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection