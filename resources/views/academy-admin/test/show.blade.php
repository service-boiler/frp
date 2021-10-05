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
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.tests.index') }}">@lang('site::academy.test_index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $test->name }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $test->name }}</h1>

         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.tests.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.tests.edit', $test) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.test')</span>
            </a>
            
            <button type="submit" form="test-delete-form-{{$test->id}}" 
                                @cannot('delete', $test) aadisabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.test_delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $test) ssdisabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="test-delete-form-{{$test->id}}"
									action="{{route('academy-admin.tests.destroy', $test)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    </div>
@endsection
