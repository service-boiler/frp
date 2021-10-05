@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.faq.index') }}">@lang('site::admin.faq_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::admin.faq_add')</h1>

        @alert()@endalert

        <div class="card mb-2">
            <div class="card-body text-muted" id="summernote">
                <form id="form-content"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.faq.store') }}">

                    @csrf
                   
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="body">@lang('site::admin.faq_body')</label>
                            <textarea id="body"
                                      class="summernote form-control{{ $errors->has('body') ? ' is-invalid' : '' }}"
                                      placeholder="@lang('site::mailing.placeholder.body')"
                                      name="faq[body]">{{ old('body') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('body') }}</span>
                        </div>
                    </div>
                   
                   
                   
                </form>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form-content" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.faq.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection