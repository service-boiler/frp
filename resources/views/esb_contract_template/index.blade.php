@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_contract_template.index')</li>
        </ol>
        <h1 class="header-title mb-4"><i class="fa fa-@lang('site::user.esb_contract_template.icon')"></i> @lang('site::user.esb_contract_template.index')
        </h1>
        @alert()@endalert

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-page d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-contract-templates.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::user.esb_contract_template.add')</span>
            </a>
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.home')</span>
            </a>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('site::user.esb_contract_template.name')</th>
                        <th class="text-center">@lang('site::user.esb_contract_template.file_id')</th>
                        <th class="text-center">@lang('site::user.esb_contract_template.shared')</th>
                        <th class="text-center">@lang('site::messages.created_at')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($esbContractTemplates as $esbContractTemplate)
                        <tr>
                            <td class="align-middle">
                                @bool(['bool' => $esbContractTemplate->enabled])@endbool
                                <a href="{{route('esb-contract-templates.show', $esbContractTemplate)}}">{{$esbContractTemplate->name }}</a>
                            </td>
                            <td class="text-center">
                                @if($esbContractTemplate->file)
                                    @if(optional($esbContractTemplate->file)->exists())
                                        <a class="btn btn-success d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                                           href="{{route('files.show', $esbContractTemplate->file)}}">
                                            <i class="fa fa-download"></i>
                                            @lang('site::messages.download')
                                        </a>
                                    @else
                                        {{$esbContractTemplate->file->name }}
                                        <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                                    @endif
                                @else
                                    <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @bool(['bool' => $esbContractTemplate->shared])@endbool
                            </td>
                            <td class="text-center">
                                {{$esbContractTemplate->created_at->format('d.m.Y')}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
