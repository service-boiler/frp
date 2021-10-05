@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-catalog-prices.index') }}">@lang('site::admin.esb_catalog_service.price_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$esbCatalogPrice->name}}</li>
        </ol>
         @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('admin.esb-catalog-prices.update',$esbCatalogPrice) }}">
                    @csrf
                    @method('PUT')



                    <div class="form-row required">
                        <div class="col-sm-8 mb-3">
                            <label class="control-label" for="service_id">@lang('site::admin.esb_catalog_service.esb_catalog_service_id')</label>
                            <select class="form-control{{  $errors->has('service_id') ? ' is-invalid' : '' }}"
                                    name="service_id"
                                    id="service_id">
                                @foreach($esbCatalogServices as $esbCatalogService)
                                    <option
                                            @if(old('service_id',$esbCatalogPrice->service->id) == $esbCatalogService->id)
                                            selected
                                            @endif
                                            value="{{ $esbCatalogService->id }}">{{ $esbCatalogService->name }} ({{ $esbCatalogService->brand->name }})
                                    </option>
                                @endforeach

                            </select>
                            <span class="invalid-feedback">{{ $errors->first('service_id') }}</span>
                        </div>


                        <div class="col-2 mb-3">
                            <label class="control-label"
                                   for="price">@lang('site::admin.esb_catalog_service.price')</label>
                            <input type="number" step="{{config('site.round_step')}}"
                                   name="price"
                                   id="price"
                                   min="0"
                                   class="form-control{{$errors->has('price') ? ' is-invalid' : ''}}"
                                   value="{{ old('price',$esbCatalogPrice->price) }}">
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
                                       @if(old('enabled',$esbCatalogPrice->enabled) == 1) checked @endif
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
                                       @if(old('enabled',$esbCatalogPrice->enabled) == 0) checked @endif
                                       id="enabled_0"
                                       value="0">
                                <label class="custom-control-label"
                                       for="enabled_0">@lang('site::messages.no')</label>
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
                        <a href="{{ route('admin.esb-catalog-prices.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection