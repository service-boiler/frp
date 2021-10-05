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
            <li class="breadcrumb-item active">Создание нового атрибута товара</li>
        </ol>
        <h1 class="header-title mb-4">Создание нового атрибута товара</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="form-form"
                              action="{{ route('admin.productspecs.store') }}">

                            @csrf

                           

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">Название атрибута</label>
                                    <input type="text" name="productspec[name]"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           >
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
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('name_for_site') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="unit">Единицы измерения</label>
                                    <input type="text" name="productspec[unit]"
                                           id="unit"
                                           class="form-control{{ $errors->has('unit') ? ' is-invalid' : '' }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('unit') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="sort_order">Порядок сортировки в таблице</label>
                                   <input type="text" name="productspec[sort_order]"
                                           id="sort_order"
                                           class="form-control{{ $errors->has('sort_order') ? ' is-invalid' : '' }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('sort_order') }}</span>
                                </div>
                            </div>

                            <hr />
                            <div class=" text-right">
                                <button name="_stay" form="form-form" value="0" type="submit" class="btn btn-ms">
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
                
                
            </div>
        </div>

    </div>
@endsection
