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
                <a href="{{ route('admin.products.index') }}">@lang('site::product.cards')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.show', $product) }}">{{$product->name}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::analog.analogs')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::analog.analogs') {{$product->name}} {{$product->sku}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <form method="POST" id="form-content"
                              action="{{ route('admin.products.analogs.update', $product) }}">

                            @csrf

                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label d-block"
                                                   for="separator_row">@lang('site::messages.separator.row')</label>
                                            @foreach(trans('site::messages.symbol') as $key => $value)
                                                <div class="custom-control d-block custom-radio custom-control-inline">
                                                    <input class="custom-control-input
                                                         {{$errors->has('separator_row') ? ' is-invalid' : ''}}"
                                                           type="radio"
                                                           name="separator_row"
                                                           required
                                                           @if(old('separator_row', '\r\n') == $key) checked @endif
                                                           id="separator_row_{{$key}}"
                                                           value="{{$key}}">
                                                    <label class="custom-control-label"
                                                           for="separator_row_{{$key}}">{!! $value !!}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="mirror">@lang('site::messages.mirror')</label>
                                        <select class="form-control{{  $errors->has('mirror') ? ' is-invalid' : '' }}"
                                                required
                                                name="mirror"
                                                id="mirror">
                                            <option @if(old('mirror', 1) == 1) selected @endif
                                            value="1">@lang('site::messages.yes')</option>
                                            <option @if(old('mirror', 1) == 0) selected @endif
                                            value="0">@lang('site::messages.no')</option>
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('mirror') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label for="analogs">@lang('site::analog.analogs')</label>
                                            <textarea
                                                    class="form-control{{ $errors->has('analogs') ? ' is-invalid' : '' }}"
                                                    {{--placeholder="@lang('site::product.placeholder.analogs')"--}}
                                                    name="analogs" rows="9"
                                                    id="analogs">{{ old('analogs') }}</textarea>
                                            <span class="invalid-feedback">{{ $errors->first('analogs') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <ul class="list-group list-group-flush" id="product-analogs-list">
                            @foreach($analogs as $analog)
                                @include('site::admin.product.show.analog')
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class=" border p-3 mt-2 mb-4 text-right">
            <button name="_stay" form="form-content" value="1" type="submit" class="btn btn-ms">
                <i class="fa fa-check"></i>
                <span>@lang('site::messages.save_stay')</span>
            </button>
            <button name="_stay" form="form-content" value="0" type="submit" class="btn btn-ms">
                <i class="fa fa-check"></i>
                <span>@lang('site::messages.save')</span>
            </button>
            <a href="{{ route('admin.products.show', $product) }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-close"></i>
                <span>@lang('site::messages.cancel')</span>
            </a>
        </div>
    </div>
@endsection
