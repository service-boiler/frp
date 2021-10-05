@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.template_file.template_files')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-@lang('site::admin.template_file.icon')"></i> @lang('site::admin.template_file.template_files')
        </h1>
        @alert()@endalert

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-page d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.template-files.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.template_file.template_file')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('site::admin.template_file.name')</th>
                        <th class="text-center">@lang('site::admin.template_file.file_id')</th>
                        <th class="text-center">@lang('site::messages.created_at')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($template_files as $template_file)
                        <tr>
                            <td class="align-middle">
                                <a href="{{route('admin.template-files.show', $template_file)}}">{{$template_file->name }}</a>
                            </td>
                            <td class="text-center">
                                @if($template_file->file) {{$template_file->id}} / {{$template_file->file->id}}
                                    @if(optional($template_file->file)->exists())
                                        <a class="btn btn-success d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                                           href="{{route('files.show', $template_file->file)}}">
                                            <i class="fa fa-download"></i>
                                            @lang('site::messages.download')
                                        </a>
                                    @else
                                        {{$template_file->file->name }}
                                        <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{$template_file->created_at->format('d.m.Y')}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
