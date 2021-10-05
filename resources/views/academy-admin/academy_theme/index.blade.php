@extends('layouts.app')
@section('title')@lang('site::academy.theme.index')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::academy.theme.index')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::academy.theme.index')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.themes.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::academy.theme.add')</span>
            </a>
        </div>
        <div class="border p-3 mb-2">
            
        @foreach($academyThemes as $academyTheme)

            <div class="row mb-2">
                <div class="col-sm-4">
                <a href="{{route('academy-admin.themes.edit', $academyTheme)}}">{{ $academyTheme->name }}</a>
                </div>
               
                             
            </div>
        @endforeach
            
			
		</div>
    </div>
@endsection
