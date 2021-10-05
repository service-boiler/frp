@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.esb_catalog_service.services')</li>
        </ol>

        @alert()@endalert


            <div class="card-header with-elements">
                <div class="card-header-elements">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-catalog-services.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::admin.esb_catalog_service.service_add')</span>
            </a>
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.home')</span>
            </a>
            </div>
                <div class="card-header-elements ml-md-auto">
                <a target="_blank" href="{{route('public-user-card',auth()->user()->company())}}"
                   class="py-2 justify-content-between align-items-left">
                            <span>
                                <i class="fa fa-eye"></i>
                                @lang('site::admin.esb_catalog_service.public_card')
                            </span>
                </a>

            </div>
            </div>
    @if(auth()->user()->company()->addressesPublic()->first())
        <form id="asc_info" method="POST"
              action="{{ route('esb-catalog-services.update_address', auth()->user()->company()->addressesPublic()->first()) }}">

            @csrf
            @method('PUT')
        <div class="justify-content-start border p-3 mb-2">
            <div class="row"><div class="col-sm-10">
            <label class="control-label" for="comments">@lang('site::admin.esb_catalog_service.asc_info')</label>
            <textarea
                    form="asc_info"
                    name="address_asc_info"
                    id="asc_info"
                    class="form-control">{{auth()->user()->company()->addressesPublic()->first()->asc_info}}</textarea>
            <small class="text-success">@lang('site::admin.esb_catalog_service.asc_info_address_upd') {{auth()->user()->company()->addressesPublic()->first()->full}}, {{auth()->user()->company()->addressesPublic()->first()->name}}</small>
            </div>
            <div class="col-sm-2">
            <div class=" mb-2 text-right">
                <button form="asc_info" type="submit"
                        class="btn btn-ms mb-1 mt-sm-5">
                    <i class="fa fa-check"></i>
                    <span>@lang('site::messages.save')</span>
                </button>


            </div>
            </div>

        </div>
            <input type="hidden" name="address_id" value="{{auth()->user()->company()->addressesPublic()->first()->id}}">
        </form>
        @else
            <div class="justify-content-start border p-3 mb-2">
                <h5>Внимание! У Вас нет фактических адресов которые отображаются на карте.</h5>
                Проверьте свои фактические  <a href="{{route('addresses.index')}}"><i class="fa fa-external-link"></i> адреса </a>
            </div>
        @endif
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbCatalogServices])@endpagination
        {{$esbCatalogServices->render()}}
        
        <div class="row">
            @each('site::esb_catalog_service.index.row', $esbCatalogServices, 'esbCatalogService')
        </div>
        {{$esbCatalogServices->render()}}
    </div>
@endsection
