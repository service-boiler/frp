@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_repair.index')</li>
        </ol>

        @alert()@endalert()
        <div class="card mb-2">
           <div class="card-header with-elements">
                    <div class="card-header-elements">
                       <a href="{{route('esb-repairs.create')}}" class="btn btn-ms"><i class="fa fa-plus"></i> @lang('site::user.esb_repair.add')</a>
                    </div>
                    <div class="card-header-elements ml-md-auto">
                       
            
                    </div>
            </div>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbRepairs])@endpagination
        {{$esbRepairs->render()}}
        @foreach($esbRepairs as $esbRepair)
            <div class="card my-4" id="mounter-{{$esbRepair->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-repairs.show', $esbRepair)}}" class="mr-3 ml-0">
                            @lang('site::user.esb_repair.repair') № {{$esbRepair->number ? $esbRepair->number : $esbRepair->id}}
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_repair.date_maintenance')</dt>
                            <dd class="col-12">{{$esbRepair->date_repair ? $esbRepair->date_repair->format('d.m.Y') : null}}</dd>
                            
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_repair.product')</dt>
                            <dd class="col-12">
                            @if($esbRepair->esbProduct)
                            {{$esbRepair->esbProduct->product ? $esbRepair->esbProduct->product->name : $esbRepair->esbProduct->product_no_cat}}
                            @else
                             {{$esbRepair->product_name ? $esbRepair->product_name : 'не указан'}}
                            @endif
                            </dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.serial')</dt>
                            <dd class="col-12">
                            @if($esbRepair->esbProduct)
                                {{$esbRepair->esbProduct->serial ? $esbRepair->esbProduct->serial : 'не указан'}}
                            @else
                                {{$esbRepair->product_serial ? $esbRepair->product_serial : 'не указан'}}
                            @endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounter.address')</dt>
                            <dd class="col-12">
                            @if($esbRepair->esbProduct)
                                {{$esbRepair->esbProduct->address ? $esbRepair->esbProduct->address_filtred : 'не указан'}}</dd>
                            @else
                                {{$esbRepair->address ? $esbRepair->address : 'не указан'}}
                            @endif
                            <dt class="col-12">@lang('site::user.esb_repair.user')</dt>
                            <dd class="col-12">
                            @if($esbRepair->esbProduct)
                                {{$esbRepair->esbProduct->user_filtred}}
                            @else
                                {{$esbRepair->user_name ? $esbRepair->user_name : 'не указан'}}
                            @endif</dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$esbRepairs->render()}}
    </div>
@endsection
