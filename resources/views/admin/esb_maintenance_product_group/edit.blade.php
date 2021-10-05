@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-maintenance-product-groups.index') }}">@lang('site::product.esb_maintenance_groups')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$esbMaintenanceProductGroup->name}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$esbMaintenanceProductGroup->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="edit-form"
                              action="{{ route('admin.esb-maintenance-product-groups.update', $esbMaintenanceProductGroup) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::product.name')</label>
                                    <input type="text" name="name"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{ old('name', $esbMaintenanceProductGroup->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label d-block"
                                           for="active">@lang('site::product.active')</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="active"
                                               required
                                               @if(old('active', $esbMaintenanceProductGroup->active) == 1) checked @endif
                                               id="active_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="active_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="active"
                                               required
                                               @if(old('active', $esbMaintenanceProductGroup->active) == 0) checked @endif
                                               id="active_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="active_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col-4 mb-3">
                                    <label class="control-label"
                                           for="contragent_inn">@lang('site::product.esb_maintenance_cost_single')</label>
                                    <input type="number"
                                           name="cost_single"
                                           id="cost_single"
                                           min="0" step="0.01"
                                           required
                                           class="form-control{{$errors->has('cost_single') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::product.placeholder.cost')"
                                           value="{{ old('cost_single', $esbMaintenanceProductGroup->cost_single) }}">
                                    <span class="invalid-feedback">{{ $errors->first('cost_single') }}</span>
                                </div>
                                
                                <div class="col-4 mb-3">
                                    <label class="control-label"
                                           for="contragent_inn">@lang('site::product.esb_maintenance_cost_year')</label>
                                    <input type="number"
                                           name="cost_year"
                                           id="cost_year"
                                           min="0" step="0.01"
                                           required
                                           class="form-control{{$errors->has('cost_year') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::product.placeholder.cost_year')"
                                           value="{{ old('cost_year', $esbMaintenanceProductGroup->cost_year) }}">
                                    <span class="invalid-feedback">{{ $errors->first('cost_year') }}</span>
                                </div>

                            </div>
                            <hr />
                            <div class=" text-right">
                                <button form="edit-form" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.esb-maintenance-product-groups.index') }}" class="d-block d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
