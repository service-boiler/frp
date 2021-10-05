@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.esb_catalog_service.services')</li>
        </ol>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.esb-catalog-services.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::admin.esb_catalog_service.service_add')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbCatalogServices])@endpagination
        {{$esbCatalogServices->render()}}
        
        <div class="row items-row-view" data-target="{{route('admin.esb-catalog-services.sort')}}" id="sort-list">
            @each('site::admin.esb_catalog_service.index.row', $esbCatalogServices, 'esbCatalogService')
        </div>
        {{$esbCatalogServices->render()}}
    </div>
@endsection
