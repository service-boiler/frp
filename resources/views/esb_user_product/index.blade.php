@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_user_product.index')</li>
            <div class="ml-md-auto">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.esb-clients.index') }}">
                        <i class="fa fa-dollar"></i> Клиенты - потребители</a>
                </li>
            </div>
        </ol>

        @alert()@endalert()
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbUserProducts])@endpagination
        {{$esbUserProducts->render()}}
        @foreach($esbUserProducts as $esbUserProduct)
            <div class="card my-4" id="product-{{$esbUserProduct->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-user-products.show', $esbUserProduct)}}" class="mr-3 ml-0">
                           {{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_launch.date_launch')</dt>
                            <dd class="col-12">{{optional(optional($esbUserProduct->launch())->date_launch)->format('d.m.Y')?: '---'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.date_sale')</dt>
                            <dd class="col-12">{{optional($esbUserProduct->date_sale)->format('d.m.Y') ?: '---'}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            
                            <dt class="col-12">@lang('site::user.esb_user_product.serial')</dt>
                            <dd class="col-12">{{$esbUserProduct->serial ? $esbUserProduct->serial : '---'}}
                                
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-7">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounter.address')</dt>
                            <dd class="col-12">{{$esbUserProduct->address ? $esbUserProduct->address_filtred : '---'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_product_launch.user')</dt>
                            <dd class="col-12">{{$esbUserProduct->user_filtred}}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$esbUserProducts->render()}}
                                    
           
        
    </div>
@endsection
