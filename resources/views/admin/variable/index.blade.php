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
            <li class="breadcrumb-item active">@lang('site::admin.variables')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.icon')"></i> @lang('site::admin.variables')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $variables])@endpagination
        {{$variables->render()}}
        <div class="row items-row-view">
            @each('site::admin.variable.index.row', $variables, 'variable')
        </div>
        {{$variables->render()}}
    </div>
@endsection
