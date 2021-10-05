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
                <a href="{{ route('admin.equipments.index') }}">@lang('site::equipment.equipments')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::equipment.equipment')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body" id="summernote">

                <form id="form-content" method="POST" action="{{ route('admin.equipments.store') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('equipment.show_ferroli')) checked @endif
                                               class="custom-control-input{{  $errors->has('equipment.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="equipment[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('equipment.show_ferroli') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('equipment.show_market_ru')) checked @endif
                                               class="custom-control-input{{  $errors->has('equipment.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="equipment[show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('equipment.show_market_ru') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('equipment.show_lamborghini')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('equipment.show_lamborghini') ? ' is-invalid' : '' }}"
                                               id="show_lamborghini"
                                               name="equipment[show_lamborghini]">
                                        <label class="custom-control-label"
                                               for="show_lamborghini">@lang('site::messages.show_lamborghini')</label>
                                        <span class="invalid-feedback">{{ $errors->first('equipment.show_lamborghini') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('equipment.enabled')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('equipment.enabled') ? ' is-invalid' : '' }}"
                                               id="enabled"
                                               name="equipment[enabled]">
                                        <label class="custom-control-label"
                                               for="enabled">@lang('site::equipment.enabled')</label>
                                        <span class="invalid-feedback">{{ $errors->first('equipment.enabled') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('equipment.mounter_enabled')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('equipment.mounter_enabled') ? ' is-invalid' : '' }}"
                                               id="mounter_enabled"
                                               name="equipment[mounter_enabled]">
                                        <label class="custom-control-label"
                                               for="mounter_enabled">@lang('site::equipment.mounter_enabled')</label>
                                        <span class="invalid-feedback">{{ $errors->first('equipment.mounter_enabled') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group required mt-3">
                        <label class="control-label" for="catalog_id">@lang('site::equipment.catalog_id')</label>
                        <select class="form-control{{  $errors->has('equipment.name') ? ' is-invalid' : '' }}"
                                name="equipment[catalog_id]"
                                required
                                id="catalog_id">
                            <option value="">@lang('site::equipment.default.catalog_id')</option>
                            @include('site::admin.catalog.tree.create', ['value' => $tree, 'level' => 0])
                        </select>
                        <span class="invalid-feedback">{{ $errors->first('equipment.catalog_id') }}</span>
                    </div>

                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label" for="name">@lang('site::equipment.name')</label>
                            <input type="text"
                                   name="equipment[name]"
                                   id="name"
                                   required
                                   class="form-control{{ $errors->has('equipment.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::equipment.placeholder.name')"
                                   value="{{ old('equipment.name') }}">
                            <span class="invalid-feedback">{{ $errors->first('equipment.name') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="h1">@lang('site::equipment.h1')</label>
                            <input type="text"
                                   name="equipment[h1]"
                                   id="h1"
                                   class="form-control{{ $errors->has('equipment.h1') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::equipment.placeholder.h1')"
                                   value="{{ old('equipment.h1') }}">
                            <span class="invalid-feedback">{{ $errors->first('equipment.h1') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="title">@lang('site::equipment.title')</label>
                            <input type="text"
                                   name="equipment[title]"
                                   id="title"
                                   class="form-control{{ $errors->has('equipment.title') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::equipment.placeholder.title')"
                                   value="{{ old('equipment.title') }}">
                            <span class="invalid-feedback">{{ $errors->first('equipment.title') }}</span>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col">
                            <label for="metadescription">@lang('site::equipment.metadescription')</label>
                            <textarea
                                    class="form-control{{ $errors->has('equipment.metadescription') ? ' is-invalid' : '' }}"
                                    placeholder="@lang('site::equipment.placeholder.metadescription')"
                                    name="equipment[metadescription]"
                                    rows="5"
                                    id="metadescription">{!! old('equipment.metadescription') !!}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('equipment.metadescription') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="annotation">@lang('site::equipment.annotation_ru')</label>
                            <input type="text"
                                   name="equipment[annotation]"
                                   id="annotation"
                                   class="form-control{{ $errors->has('equipment.annotation') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::equipment.placeholder.annotation')"
                                   value="{{ old('equipment.annotation') }}">
                            <span class="invalid-feedback">{{ $errors->first('equipment.annotation') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="annotation_by">@lang('site::equipment.annotation_by')</label>
                            <input type="text"
                                   name="equipment[annotation_by]"
                                   id="annotation_by"
                                   class="form-control{{ $errors->has('equipment.annotation_by') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::equipment.placeholder.annotation')"
                                   value="{{ old('equipment.annotation_by') }}">
                            <span class="invalid-feedback">{{ $errors->first('equipment.annotation_by') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="description">@lang('site::equipment.description')</label>
                            <textarea class="summernote form-control{{ $errors->has('equipment.description') ? ' is-invalid' : '' }}"

                                      placeholder="@lang('site::equipment.placeholder.description')"
                                      name="equipment[description]"
                                      id="description">{{ old('equipment.description') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('equipment.description') }}</span>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col">
                            <label class="control-label" for="specification">@lang('site::equipment.specification')</label>
                            <textarea class="summernote form-control{{ $errors->has('equipment.specification') ? ' is-invalid' : '' }}"
                                      placeholder="@lang('site::equipment.placeholder.specification')"
                                      name="equipment[specification]"
                                      id="specification">{{ old('equipment.specification') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('equipment.specification') }}</span>
                        </div>
                    </div>
                </form>
                <hr />
                <div class="form-row">
                    <div class="col text-right">
                        <button form="form-content" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.equipments.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection