@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-catalog-services.index') }}">@lang('site::admin.esb_catalog_service.services')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::admin.esb_catalog_service.service_add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('admin.esb-catalog-services.update',$esbCatalogService) }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="sort_order" value="{{$esbCatalogService->sort_order}}" />
                    <input type="hidden" name="currency_id" value="{{config('site.main')}}" />

                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="name">@lang('site::admin.esb_catalog_service.name')</label>
                            <input type="text" name="name"
                                   id="name"
                                   required
                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::admin.esb_catalog_service.placeholder_name')"
                                   value="{{ old('name',$esbCatalogService->name) }}">
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-3">
                            <div class="form-row required">
                                <div class="col">

                                    <label class="control-label" for="type_id">@lang('site::admin.esb_catalog_service.esb_catalog_service_type')</label>
                                    <select class="form-control{{  $errors->has('type_id') ? ' is-invalid' : '' }}"
                                            name="type_id"
                                            id="type_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($esbCatalogServiceTypes as $esbCatalogServiceType)
                                            <option
                                                    @if(old('type_id',$esbCatalogService->type_id) == $esbCatalogServiceType->id)
                                                    selected
                                                    @endif
                                                    value="{{ $esbCatalogServiceType->id }}">{{ $esbCatalogServiceType->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('type_id') }}</span>

                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-row required">
                                <div class="col">

                                    <label class="control-label" for="brand_id">@lang('site::admin.esb_catalog_service.brand')</label>
                                    <select class="form-control{{  $errors->has('esb_catalog_service_type') ? ' is-invalid' : '' }}"
                                            name="brand_id"
                                            id="brand_id">
                                        @foreach($brands as $brand)
                                            <option
                                                    @if(old('brand_id',$esbCatalogService->brand_id) == $brand->id)
                                                    selected
                                                    @endif
                                                    value="{{ $brand->id }}">{{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('brand_id') }}</span>

                                </div>
                            </div>
                        </div>
                        <div class="col-3 mb-3">
                            <label class="control-label"
                                   for="cost_std">@lang('site::admin.esb_catalog_service.cost_std')</label>
                            <input type="number"
                                   name="cost_std"
                                   id="cost_std"
                                   min="0"
                                   class="form-control{{$errors->has('cost_std') ? ' is-invalid' : ''}}"
                                   value="{{ old('cost_std',$esbCatalogService->cost_std) }}">
                            <span class="invalid-feedback">{{ $errors->first('cost_std') }}</span>
                        </div>
                    </div>

                    <div class="form-row required">
                        <div class="col-sm-3 mb-3">
                            <label class="control-label d-block"
                                   for="enabled">@lang('site::messages.enabled')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('enabled') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="enabled"
                                       required
                                       @if(old('enabled',$esbCatalogService->enabled) == 1) checked @endif
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
                                       @if(old('enabled',$esbCatalogService->enabled) == 0) checked @endif
                                       id="enabled_0"
                                       value="0">
                                <label class="custom-control-label"
                                       for="enabled_0">@lang('site::messages.no')</label>
                            </div>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <label class="control-label d-block"
                                   for="shared">@lang('site::admin.esb_catalog_service.shared')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('shared') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="shared"
                                       required
                                       @if(old('shared',$esbCatalogService->shared) == 1) checked @endif
                                       id="shared_1"
                                       value="1">
                                <label class="custom-control-label"
                                       for="shared_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('shared') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="shared"
                                       required
                                       @if(old('shared',$esbCatalogService->shared) == 0) checked @endif
                                       id="shared_0"
                                       value="0">
                                <label class="custom-control-label"
                                       for="shared_0">@lang('site::messages.no')</label>
                            </div>
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
                        <a href="{{ route('admin.esb-catalog-services.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection