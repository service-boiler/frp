@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.questions.index') }}">@lang('site::academy.question_index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $question->text }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $question->text }}</h1>

         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.questions.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.questions.edit', $question) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.question_add')</span>
            </a>
            
            <button type="submit" form="question-delete-form-{{$question->id}}" 
                                @cannot('delete', $question) disabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.question_delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $question) ssdisabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="question-delete-form-{{$question->id}}"
									action="{{route('academy-admin.questions.destroy', $question)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    </div>
@endsection
