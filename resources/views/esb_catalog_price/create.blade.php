@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-catalog-prices.index') }}">@lang('site::admin.esb_catalog_service.price_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::admin.esb_catalog_service.price_add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('esb-catalog-prices.store') }}">
                    @csrf

                    <div class="form-row required">
                        <div class="col-sm-8 mb-3">
                            <label class="control-label" for="service_id">@lang('site::admin.esb_catalog_service.esb_catalog_service_id')</label>
                            <select class="form-control{{  $errors->has('service_id') ? ' is-invalid' : '' }}"
                                    name="service_id"
                                    id="service_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($esbCatalogServices as $esbCatalogService)
                                    <option
                                            @if(old('service_id') == $esbCatalogService->id)
                                            selected
                                            @endif
                                            value="{{ $esbCatalogService->id }}">{{ $esbCatalogService->name }} ({{ $esbCatalogService->brand->name }})
                                    </option>
                                @endforeach
                                <option value="none" disabled>---</option>
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('service_id') }}</span>
                            <button class="btn btn-sm btn-green mt-3" type="button"
                            onclick=" $('#new-service').removeClass('d-none'); $(this).remove(); document.getElementById('service_id').value='none'; document.getElementById('service_id').disabled='true';"><i class="fa fa-plus"></i> Услуги нет в справочнике, добавить новую</button>
                        </div>

                        <div class="col-2 mb-3">
                            <label class="control-label"
                                   for="price">@lang('site::admin.esb_catalog_service.price')</label>
                            <input type="number"
                                   name="price"
                                   id="price"
                                   min="0"
                                   class="form-control{{$errors->has('price') ? ' is-invalid' : ''}}"
                                   value="{{ old('price') }}">
                            <span class="invalid-feedback">{{ $errors->first('price') }}</span>
                        </div>
                        <div class="col-sm-2 mb-3">
                            <label class="control-label d-block"
                                   for="enabled">@lang('site::messages.enabled')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('enabled') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="enabled"
                                       required
                                       @if(old('enabled', 1) == 1) checked @endif
                                       id="enabled_1"
                                       value="1">
                                <label class="custom-control-label"
                                       for="enabled_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('enabled') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="enabled"
                                       required
                                       @if(old('enabled', 1) == 0) checked @endif
                                       id="enabled_0"
                                       value="0">
                                <label class="custom-control-label"
                                       for="enabled_0">@lang('site::messages.no')</label>
                            </div>
                        </div>
                    </div>

                    <div class="@if(!$errors->first('service.name'))d-none @endif" id="new-service">

                        <div class="form-row required">
                            <div class="col mb-3">
                                <label class="control-label" for="service_name">@lang('site::admin.esb_catalog_service.name')</label>
                                <input type="text" name="service[name]"
                                       id="service_name"
                                       class="form-control{{ $errors->has('service.name') ? ' is-invalid' : '' }}"
                                       placeholder="@lang('site::admin.esb_catalog_service.placeholder_name')"
                                       value="{{ old('service.name') }}">
                                <span class="invalid-feedback">{{ $errors->first('service.name') }}</span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-3"><div class="form-row required"><div class="col">

                                        <label class="control-label" for="type_id">@lang('site::admin.esb_catalog_service.esb_catalog_service_type')</label>
                                        <select class="form-control{{  $errors->has('service.type_id') ? ' is-invalid' : '' }}"
                                                name="service[type_id]"
                                                id="type_id">
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                            @foreach($esbCatalogServiceTypes as $esbCatalogServiceType)
                                                <option
                                                        @if(old('service.type_id') == $esbCatalogServiceType->id)
                                                        selected
                                                        @endif
                                                        value="{{ $esbCatalogServiceType->id }}">{{ $esbCatalogServiceType->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('service.type_id') }}</span>

                                    </div></div></div>

                                    <div class="col-sm-3"><div class="form-row required"><div class="col">

                                        <label class="control-label" for="brand_id">@lang('site::admin.esb_catalog_service.brand')</label>
                                        <select class="form-control{{  $errors->has('esb_catalog_service_type') ? ' is-invalid' : '' }}"
                                                name="service[brand_id]"
                                                id="brand_id">
                                            @foreach($brands as $brand)
                                                <option
                                                        @if(old('service.brand_id') == $brand->id)
                                                        selected
                                                        @endif
                                                        value="{{ $brand->id }}">{{ $brand->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('service.brand_id') }}</span>

                                    </div></div></div>
                            <input type="hidden" name="service[enabled]" value="1">

                        </div>

                    </div>

                </form>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button form="form-content" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('esb-catalog-prices.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection