@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.template-files.index') }}">@lang('site::admin.template_file.template_files')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::admin.template_file.template_file')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">
                <form id="form" method="POST" action="{{ route('admin.template-files.store') }}">
                    @csrf

                    <div class="form-row required mt-3">
                        <div class="col">
                            <label class="control-label" for="name">@lang('site::admin.template_file.name')</label>
                            <input type="text"
                                   name="template_files[name]"
                                   id="name"
                                   required
                                   class="form-control{{ $errors->has('template_file.name') ? ' is-invalid' : '' }}"
                                   value="{{ old('template_file.name') }}">
                            <span class="invalid-feedback">{{ $errors->first('template_file.name') }}</span>
                        </div>
                    </div>

                </form>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="file_id">@lang('site::admin.template_file.file_id')</label>

                                <form method="POST" enctype="multipart/form-data"
                                      action="{{route('admin.files.store')}}">
                                    @csrf
                                    <input type="hidden"
                                           name="type_id"
                                           value="40"/>
                                    <input type="hidden"
                                           name="storage"
                                           value="template_files"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('file_id') ? ' is-invalid' : '' }}"
                                           type="file"

                                           name="path"/>

                                    <input type="button" class="btn btn-ms file-upload-button"
                                           value="@lang('site::messages.load')"/>
                                    <span id="downloadbar" class="d-none"><img style="height:40px" src="/images/l3.gif" /></span>
                                    <span class="invalid-feedback">{{ $errors->first('file_id') }}</span>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="files" class="row bg-white">
                            @include('site::admin.file.edit')
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
                        <a href="{{ route('admin.template-files.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection