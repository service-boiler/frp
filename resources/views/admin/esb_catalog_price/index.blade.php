@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.esb_catalog_service.price_index')</li>
        </ol>

        @alert()@endalert

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbCatalogPrices])@endpagination
        {{$esbCatalogPrices->render()}}
        
        <div class="row">
            @each('site::admin.esb_catalog_price.index.row', $esbCatalogPrices, 'esbCatalogPrice')
        </div>
        {{$esbCatalogPrices->render()}}
    </div>
@endsection
