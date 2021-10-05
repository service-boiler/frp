@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.faq.index') }}">@lang('site::faq.faqs')</a>
            </li>
            <li class="breadcrumb-item active">{{ $faq->title }}</li>
        </ol>
        @alert()@endalert
        <!-- <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.faq.edit', $faq) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::admin.faq_edit')</span>
            </a>

            <a href="{{ route('admin.faq.index') }}" class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <button type="submit" form="faq-delete-form-{{$faq->id}}"
                    class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
            </button>
            <form id="faq-delete-form-{{$faq->id}}"
                  action="{{route('admin.faq.destroy', $faq)}}"
                  method="POST">
                @csrf
                @method('DELETE')
            </form>
        </div> -->
        <div class="card mb-2">
            <div class="card-body">
                <div class="row"><div class="col">
                {!! $faq->body !!}
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
