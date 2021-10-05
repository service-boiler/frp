@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.region-biz-districts.index') }}">@lang('site::region.region_biz_districts')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.create') @lang('site::region.region_biz_district')</h1>

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
                      action="{{ route('admin.region-biz-districts.store') }}">

                    @csrf

                    <div class="card mt-2 mb-2">
                        <div class="card-body">

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="region_biz_district_name">@lang('site::region.region_biz_district_name')</label>
                                    <input type="text"
                                           name="region_biz_district[name]"
                                           id="region_biz_district_name"
                                           required
                                           class="form-control{{$errors->has('region_biz_district.name') ? ' is-invalid' : ''}}"
                                           placeholder="Например, Волга-5"
                                           value="{{ old('region_biz_district.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('region_biz_district.name') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="region_biz_district_sort_order">@lang('site::region.region_biz_district_sort_order')</label>
                                    <input type="text"
                                           name="region_biz_district[sort_order]"
                                           id="region_biz_district_sort_order"
                                           required
                                           class="form-control{{$errors->has('region_biz_district.sort_order') ? ' is-invalid' : ''}}"
                                           value="{{ old('region_biz_district.sort_order',100) }}">
                                    <span class="invalid-feedback">{{ $errors->first('region_biz_district.sort_order') }}</span>
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
                                <a href="{{ route('admin.region-biz-districts.index') }}" class="btn btn-secondary mb-1">
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


