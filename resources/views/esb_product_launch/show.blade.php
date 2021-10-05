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
                <a href="{{ route('esb-product-launches.index') }}">@lang('site::user.esb_product_launch.index')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $esbProductLaunch->id }}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_product_launch.launch') № {{ $esbProductLaunch->number ? $esbProductLaunch->number : $esbProductLaunch->id }}</h1>
        @alert()@endalert()
        <div class="p-3 mb-0">
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-product-launches.edit', $esbProductLaunch) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::user.esb_product_launch.launch')</span>
            </a>
            <a href="{{ route('esb-product-launches.index') }}" class="d-block d-sm-inline-block btn btn-secondary mr-sm-3">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            
            <button
            class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 btn btn-danger btn-row-delete mt-1"
                    data-form="#delete-form"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::user.esb_product_launch.launch')? "
                    data-toggle="modal" data-target="#form-modal"
                    title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i>
                @lang('site::messages.delete')
            </button>
            <form id="delete-form"
                      method="POST"
                      action="{{ route('esb-product-launches.destroy',$esbProductLaunch) }}">
                    @csrf
                    @method('DELETE')
                    
                    </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.date_launch')</dt>
                    <dd class="col-sm-8">{{ $esbProductLaunch->date_launch->format('d.m.Y') }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.date_sale')</dt>
                    <dd class="col-sm-8">{{ $esbProductLaunch->esbProduct->date_sale->format('d.m.Y') }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.number')</dt>
                    <dd class="col-sm-8">{{ $esbProductLaunch->number }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.user')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->user_filtred}}

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->address_filtred}}

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.phone')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->phone_filtred}}
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.product')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->product ? $esbProductLaunch->esbProduct->product->name : $esbProductLaunch->esbProduct->product_no_cat}}
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->serial ? $esbProductLaunch->esbProduct->serial : 'не указан'}}
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                    <dd class="col-sm-8">{{$esbProductLaunch->esbProduct->product_no_cat}}
                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_product_launch.comment')</dt>
                    <dd class="col-sm-8">{!! $esbProductLaunch->comments !!}</dd>
                    @if(!$esbProductLaunch->esbProduct->permission_service)
                    <dt class="col-sm-4 text-left text-sm-right"></dt>
                    <dd class="col-sm-8 text-success">@lang('site::user.esb_user_product.perm_help')</dd>
                    @endif
                </dl>
                
                
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">@lang('site::repair.header.files')</h5>
                @include('site::file.files')
            </div>
        </div>
    </div>
@endsection
