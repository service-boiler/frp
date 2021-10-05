@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::academy.test.index')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::academy.test.index')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.tests.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::academy.test.add')</span>
            </a>
            
            <a class="d-block d-sm-inline btn btn-secondary" 
                href="{{ route('academy-admin') }}">
                <i class="fa fa-reply"></i> @lang('site::academy.admin_index')
            </a>
        </div>
        <div class="border p-3 mb-2">
            
        @foreach($academyTests as $academyTest)

            <div class="row mb-2">
                <div class="col-sm-4">
                <a href="{{route('academy-admin.tests.edit', $academyTest)}}">{{ $academyTest->name }}</a>
                </div>
               <div class="col-sm-4">
               {{ $academyTest->theme->name }}</a>
                </div>
               <div class="col-sm-4">
               Вопросов: {{ $academyTest->count_questions }} / {{ $academyTest->count_correct }}
               , Привязано - {{ $academyTest->questions->count() }}</a>
                </div>
                             
            </div>
        @endforeach
            
			
		</div>
    </div>
@endsection
