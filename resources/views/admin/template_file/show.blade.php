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
            <li class="breadcrumb-item active">{{ $template_file->name }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $template_file->name }}</h1>
        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.template-files.edit', $template_file) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::admin.template_file.template_file')</span>
            </a>
            @if($template_file->file && $template_file->file->exists())
                <a class="btn btn-success d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                   href="{{route('files.show', $template_file->file)}}">
                    <i class="fa fa-download"></i>
                    @lang('site::messages.download')
                </a>
            @endif
            <a href="{{ route('admin.template-files.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>

        </div>
        <div class="card mb-2">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.template_file.name')</dt>
                    <dd class="col-sm-8">{{$template_file->name}}</dd>

                    @if($template_file->file)
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.type_id')</dt>
                        <dd class="col-sm-8">{{$template_file->file->type->name}}</dd>

                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.name')</dt>
                        <dd class="col-sm-8">{!! $template_file->file->name !!}</dd>

                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.storage')
                            / @lang('site::file.path')</dt>
                        <dd class="col-sm-8">
                            {{Storage::disk($template_file->file->storage)->getAdapter()->getPathPrefix().$template_file->file->path}}
                            @if(!$template_file->file->exists())
                                <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.size')</dt>
                        <dd class="col-sm-8">{{formatFileSize($template_file->file->size)}}
                            @if($template_file->file->exists())
                                <span class="text-muted">(@lang('site::file.real_size'):
                                    {{formatFileSize(filesize(Storage::disk($template_file->file->storage)->getAdapter()->getPathPrefix().$template_file->file->path))}}
                                    )
                            </span>
                            @endif
                        </dd>
                    @else
                        <dd class="col-sm-8">
                            <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                        </dd>
                    @endif
                </dl>

            </div>
        </div>
    </div>
@endsection
