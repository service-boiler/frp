@extends('layouts.app')
@section('title')@lang('site::admin.customer.index')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.customer.index')</li>
        </ol>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.customers.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::admin.customer.add')</span>
            </a>
        </div>
        <div class="border p-3 mb-2">
            
        @foreach($customers as $customer)

            <div class="row mb-2">
                <div class="col-sm-4">
                <a href="{{route('admin.customers.show', $customer)}}">{{ $customer->name }}</a>
                </div>
               
                             
            </div>
        @endforeach
            
			
		</div>
    </div>
@endsection
