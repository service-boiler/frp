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
                <a href="{{ route('admin.retail-sale-bonuses.index') }}">@lang('site::product.retail_sale_bonus.retail_sale_bonuses')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.show', $retail_sale_bonus->product) }}">
                    {{ $retail_sale_bonus->product->name}}
                    ({{ $retail_sale_bonus->product->sku }})
                </a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::product.retail_sale_bonus.retail_sale_bonus')</h1>

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
        <div class="row justify-content-center mb-5">
            <div class="col">
                <form id="form-content"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.retail-sale-bonuses.update', $retail_sale_bonus) }}">

                    @csrf
                    @method('PUT')

                    {{-- КЛИЕНТ --}}
                    <div class="card mt-2 mb-2">
                        <div class="card-body">

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="product_id">@lang('site::product.retail_sale_bonus.product_id')</label>
                                    <select class="form-control{{  $errors->has('retail_sale_bonus.product_id') ? ' is-invalid' : '' }}"
                                            required
                                            name="retail_sale_bonus[product_id]"
                                            id="product_id">
                                            <option value="{{ $retail_sale_bonus->product_id }}">
                                                {{ $retail_sale_bonus->product->name }}
                                                ({{ $retail_sale_bonus->product->sku }})
                                            </option>
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('retail_sale_bonus.product_id') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="retail_sale_bonus_start">@lang('site::product.retail_sale_bonus.start')</label>
                                    <input type="number"
                                           name="retail_sale_bonus[start]"
                                           id="retail_sale_bonus_start"
                                           min="0"
                                           step="10"
                                           max="30000"
                                           required
                                           class="form-control{{$errors->has('retail_sale_bonus.start') ? ' is-invalid' : ''}}"
                                           value="{{ old('retail_sale_bonus.start', $retail_sale_bonus->start) }}">
                                    <span class="invalid-feedback">{{ $errors->first('retail_sale_bonus.start') }}</span>
                                </div>

                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="retail_sale_bonus_value">@lang('site::product.retail_sale_bonus.value')</label>
                                    <input type="number"
                                           name="retail_sale_bonus[value]"
                                           id="retail_sale_bonus_value"
                                           min="0"
                                           step="10"
                                           max="30000"
                                           required
                                           class="form-control{{$errors->has('retail_sale_bonus.value') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::product.retail_sale_bonus.placeholder.value')"
                                           value="{{ old('retail_sale_bonus.value', $retail_sale_bonus->value) }}">
                                    <span class="invalid-feedback">{{ $errors->first('retail_sale_bonus.value') }}</span>
                                </div>

                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="retail_sale_bonus_profi">@lang('site::product.retail_sale_bonus.profi')</label>
                                    <input type="number"
                                           name="retail_sale_bonus[profi]"
                                           id="retail_sale_bonus_profi"
                                           min="0"
                                           step="10"
                                           max="30000"
                                           required
                                           class="form-control{{$errors->has('retail_sale_bonus.profi') ? ' is-invalid' : ''}}"
                                           value="{{ old('retail_sale_bonus.profi', $retail_sale_bonus->profi) }}">
                                    <span class="invalid-feedback">{{ $errors->first('retail_sale_bonus.profi') }}</span>
                                </div>

                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="retail_sale_bonus_super">@lang('site::product.retail_sale_bonus.super')</label>
                                    <input type="number"
                                           name="retail_sale_bonus[super]"
                                           id="retail_sale_bonus_social"
                                           min="0"
                                           step="10"
                                           max="30000"
                                           required
                                           class="form-control{{$errors->has('retail_sale_bonus.super') ? ' is-invalid' : ''}}"
                                           placeholder="@lang('site::product.retail_sale_bonus.placeholder.super')"
                                           value="{{ old('retail_sale_bonus.super', $retail_sale_bonus->super) }}">
                                    <span class="invalid-feedback">{{ $errors->first('retail_sale_bonus.super') }}</span>
                                </div>

                            </div>

                            
                        </div>
                    </div>

                </form>

                <div class="card my-2">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="col text-right">
                                <button form="form-content" type="submit"
                                        class="btn btn-ms mb-1">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.products.show', $retail_sale_bonus->product) }}" class="btn btn-secondary mb-1">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
