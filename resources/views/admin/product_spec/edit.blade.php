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
                <a href="{{ route('admin.productspecs.index') }}">Технические характеристики товаров</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$productspec->name}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$productspec->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="page-edit-form"
                              action="{{ route('admin.productspecs.update', $productspec) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">Название атрибута</label>
                                    <input type="text" name="productspec[name]"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{ old('name', $productspec->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name_for_site">Название для сайта</label>
                                    <input type="text" name="productspec[name_for_site]"
                                           id="name_for_site"
                                           required
                                           class="form-control{{ $errors->has('name_for_site') ? ' is-invalid' : '' }}"
                                           value="{{ old('name_for_site', $productspec->name_for_site) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name_for_site') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="unit">Единицы измерения</label>
                                    <input type="text" name="productspec[unit]"
                                           id="unit"
                                           class="form-control{{ $errors->has('unit') ? ' is-invalid' : '' }}"
                                           value="{{ old('unit', $productspec->unit) }}">
                                    <span class="invalid-feedback">{{ $errors->first('unit') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="sort_order">Порядок сортировки в таблице</label>
                                   <input type="text" name="productspec[sort_order]"
                                           id="sort_order"
                                           class="form-control{{ $errors->has('sort_order') ? ' is-invalid' : '' }}"
                                           value="{{ old('sort_order', $productspec->sort_order) }}">
                                    <span class="invalid-feedback">{{ $errors->first('sort_order') }}</span>
                                </div>
                            </div>

                            <hr />
                            <div class=" text-right">
                                <button name="_stay" form="page-edit-form" value="0" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.productspecs.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="card">
                    <div class="card-body">
                    <div class="row mb-2"><div class="col"><b>Связанные атрибуты товаров:</b></div></div>
                    <div class="row mb-2">
                    <div class="col-1">Порядок</div>
                    <div class="col-11">Название</div>
                 </div>
                @foreach($specs as $spec)
                
                <div class="row mb-1">
                    <div class="col-1"> {{$spec->sort_order}}</div>
                    <div class="col-11"> {{$spec->name}}</div>
                 </div>   
                
                @endforeach

                    </div>
                    </div>
                
            </div>
        </div>

    </div>
@endsection
