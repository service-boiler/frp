@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-repairs.index') }}">@lang('site::user.esb_repair.index')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $esbRepair->id }}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_repair.repair') № {{ $esbRepair->number ? $esbRepair->number : $esbRepair->id }}</h1>
        @alert()@endalert()
        <div class="p-3 mb-0">
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-repairs.edit', $esbRepair) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::user.esb_repair.repair')</span>
            </a>
            <a href="{{ route('esb-repairs.index') }}" class="d-block d-sm-inline-block btn btn-secondary mr-sm-3">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            
            <button
            class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 btn btn-danger btn-row-delete mt-1"
                    data-form="#delete-form"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::user.esb_repair.repair')? "
                    data-toggle="modal" data-target="#form-modal"
                    title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i>
                @lang('site::messages.delete')
            </button>
            <form id="delete-form"
                      method="POST"
                      action="{{ route('esb-repairs.destroy',$esbRepair) }}">
                    @csrf
                    @method('DELETE')
                    
                    </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.date_maintenance')</dt>
                    <dd class="col-sm-8">{{ $esbRepair->date_repair ? $esbRepair->date_repair->format('d.m.Y') : null}}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.number')</dt>
                    <dd class="col-sm-8">{{ $esbRepair->number }}</dd>

                    @if($esbRepair->esbProduct)
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.user')</dt>
                    <dd class="col-sm-8">{{$esbRepair->esbProduct->user_filtred}}

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                    <dd class="col-sm-8">{{$esbRepair->esbProduct->address_filtred}}

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.phone')</dt>
                    <dd class="col-sm-8">{{$esbRepair->esbProduct->phone_filtred}}
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.product')</dt>
                    <dd class="col-sm-8">@if($esbRepair->esbProduct->product)
                     <a href="{{route('esb-user-products.show',$esbRepair->esbProduct)}}">{{$esbRepair->esbProduct->product->name}}</a>
                      @else 
                        {{$esbRepair->esbProduct->product_no_cat}}
                      @endif 
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                    <dd class="col-sm-8">{{$esbRepair->esbProduct->serial ? $esbRepair->esbProduct->serial : 'не указан'}}
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                    <dd class="col-sm-8">{{$esbRepair->esbProduct->product_no_cat}}
                    </dd>
                    @if(!$esbRepair->esbProduct->permission_service)
                    <dt class="col-sm-4 text-left text-sm-right"></dt>
                    <dd class="col-sm-8 text-success">@lang('site::user.esb_user_product.perm_help')</dd>
                    @endif
                    @else
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                    <dd class="col-sm-8">{{$esbRepair->product_serial ? $esbRepair->product_serial : 'не указан'}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.product')</dt>
                    <dd class="col-sm-8">{{$esbRepair->product_name ? $esbRepair->product_name : 'не указан'}}</dd>
                    
                    @endif

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.comments')</dt>
                    <dd class="col-sm-8">{!! $esbRepair->comments !!}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.cost')</dt>
                    <dd class="col-sm-8">{!! $esbRepair->cost ? moneyFormatRub($esbRepair->cost) : 'не указан' !!}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.parts_cost')</dt>
                    <dd class="col-sm-8">{!! $esbRepair->parts_cost ? moneyFormatRub($esbRepair->parts_cost) : 'не указан' !!}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.engineer_id')</dt>
                    <dd class="col-sm-8">{{ $esbRepair->engineer ? $esbRepair->engineer->name : 'не указан' }}</dd>
                    
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_user_product.act_created_at')</dt>
                    <dd class="col-sm-8">{{ $esbRepair->created_at->format('d.m.Y') }}</dd>

                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_repair.parts')</dt>
                    <dd class="col-sm-8">@if($esbRepair->parts()->exists())
                    @foreach($esbRepair->parts as $part)
                        <p>{{$part->product->name}} - {{$part->count}} шт - {{$part->cost}} р/шт</p>
                    @endforeach
                    @endif
                    </dd>

                </dl>
                
                
            </div>
        </div>
    </div>
@endsection
