@extends('layouts.app')
@section('title')@lang('site::academy.admin_index')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::academy.admin_index')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::academy.index')</h1>

        <div class="row mb-4">

            <div class="col-md-4">

                <!-- Tasks -->
                <div class="card mb-4">
                    <div class="list-group">

                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.tests.index') }}">
                            <i class="fa fa-check-square-o"></i> @lang('site::academy.test.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.questions.index') }}">
                            <i class="fa fa-question-circle-o"></i> @lang('site::academy.question.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.themes.index') }}">
                            <i class="fa fa-sitemap"></i> @lang('site::academy.theme.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.presentations.index') }}">
                            <i class="fa fa-image"></i> @lang('site::academy.presentation.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.videos.index') }}">
                            <i class="fa fa-youtube"></i> @lang('site::academy.video.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.stages.index') }}">
                            <i class="fa fa-briefcase"></i> @lang('site::academy.stage.index')
                        </a>
                        <a class="list-group-item list-group-item-action py-1" href="{{ route('academy-admin.programs.index') }}">
                            <i class="fa fa-graduation-cap"></i> @lang('site::academy.program.index')
                        </a>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="list-group">
                       
                       <a class="list-group-item list-group-item-action py-1" href="{{ route('academy_ferroli.programs.index') }}">
                            <i class="fa fa-graduation-cap"></i> @lang('site::academy.program.index') (Просмотр)
                        </a>
                          
                    </div>
                </div>
    </div>
    </div>
    </div>
@endsection
