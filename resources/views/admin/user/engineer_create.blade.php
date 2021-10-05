@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.engineers.index') }}"> @lang('site::engineer.engineers')</a>
            </li>
            <li class="breadcrumb-item active">Создать инженера</li>
        </ol>

        @alert()@endalert()

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <form id="user-form" method="POST"
                      action="{{ route('admin.engineers.store') }}">

                    @csrf
                    @method('POST')
                    <div class="card mb-4">
                        <div class="card-body">

                            
                            <div class="form-row required">
                                <div class="col-sm-6">
                                    <label class="control-label" for="name">Имя / Наименование</label>
                                    <input type="text"
                                           name="user[name]"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('user.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::user.placeholder.name')"
                                           value="{{ old('user.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.name') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="name_for_site">Название для сайта</label>
                                    <input type="text"
                                           name="user[name_for_site]"
                                           id="name_for_site"
                                           required
                                           class="form-control{{ $errors->has('user.name_for_site') ? ' is-invalid' : '' }}"
                                           placeholder="Например, название бренда. Должно быть узнаваемым"
                                           value="{{ old('user.name_for_site') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.name_for_site') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-3 form-group required">
                                    <label class="control-label" for="phone">Основной телефон</label>
                                    <input type="tel"
                                           name="user[phone]"
                                           id="phone"
                                           required
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern_mobile')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('user.phone') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.phone') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.phone') }}</span>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="email"
                                           name="user[email]"
                                           id="email"
                                           class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.email') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.email') }}</span>
                                </div>
                                <div class="col-sm-2 form-group required">
                                    <label class="control-label" for="password">Пароль</label>
                                    <input type="text"
                                           name="user[password]"
                                           id="password"
                                           class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.password') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.password') }}</span>
                                </div>
                                <div class="col-sm-4 mb-3 form-group required">
                                    <label class="control-label"
                                           for="user_region_id">Основной регион пользователя</label>
                                    <select class="form-control
                                            {{$errors->has('user.region_id') ? ' is-invalid' : ''}}"
                                            required
                                            name="user[region_id]"
                                            id="user_region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option   @if(old('user.region_id') == $region->id)
                                                            selected
                                                            @endif
                                                            value="{{ $region->id }}">{{ $region->name }}</option>

                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user.region_id') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col-sm-2 mb-3">
                                    <label class="control-label d-block"
                                           for="user_active">@lang('site::user.active')</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[active]"
                                               required
                                               @if(old('user.active') == 1) checked @endif
                                               id="user_active_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="user_active_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[active]"
                                               required
                                               @if(old('user.active') == 0) checked @endif
                                               id="user_active_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="user_active_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>

                                    <div class="col-sm-2 mb-3">
                                        <label class="control-label d-block"
                                               for="user_display">@lang('site::user.display')</label>
                                        <div class="custom-control custom-radio  custom-control-inline">
                                            <input class="custom-control-input
                                                    {{$errors->has('user.display') ? ' is-invalid' : ''}}"
                                                   type="radio"
                                                   name="user[display]"
                                                   required
                                                   @if(old('user.display') == 1) checked @endif
                                                   id="user_display_1"
                                                   value="1">
                                            <label class="custom-control-label"
                                                   for="user_display_1">@lang('site::messages.yes')</label>
                                        </div>
                                        <div class="custom-control custom-radio  custom-control-inline">
                                            <input class="custom-control-input
                                                    {{$errors->has('user.display') ? ' is-invalid' : ''}}"
                                                   type="radio"
                                                   name="user[display]"
                                                   required
                                                   @if(old('user.display') == 0) checked @endif
                                                   id="user_display_0"
                                                   value="0">
                                            <label class="custom-control-label"
                                                   for="user_display_0">@lang('site::messages.no')</label>
                                        </div>

                                    </div>
                                <div class="col-sm-2 mb-3">
                                    <label class="control-label d-block"
                                           for="user_verified">@lang('site::user.verified')</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.verified') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[verified]"
                                               required
                                               @if(old('user.verified') == 1) checked @endif
                                               id="user_verified_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="user_verified_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.verified') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[verified]"
                                               required
                                               @if(old('user.verified') == 0) checked @endif
                                               id="user_verified_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="user_verified_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <div class="form-row">
                                <div class="col-sm-6 mb-3">
                                    <h5 class="card-title">@lang('rbac::role.roles')</h5>
                                    @foreach($roles->all() as $role)

                                        <div class="custom-control custom-checkbox"
                                             style="@if($role->name == 'админ') display:none;@endif">
                                            <input name="roles[]"
                                                   value="{{ $role->id }}"
                                                   type="checkbox"
                                                   @if(old('roles') && in_array($role->id,old('roles')))
                                                           checked
                                                   @endif
                                                   class="custom-control-input" id="role-{{ $role->id }}">
                                            <label class="custom-control-label"
                                                   for="role-{{ $role->id }}">{{ $role->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <label class="control-label"
                                           for="user_type">Тип пользователя</label>
                                    <select class="form-control
                                            {{$errors->has('user.type_id') ? ' is-invalid' : ''}}"
                                            required
                                            name="user[type_id]"
                                            id="user_type">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($userTypes as $userType)
                                            <option   @if(old('user.type_id') == $userType->id || (!old('user.type_id') && $userType->id == 3))
                                                      selected
                                                      @endif
                                                      value="{{ $userType->id }}">{{ $userType->name }}</option>

                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user.region_id') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-ms">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('admin.engineers.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-close"></i>
                                        <span>@lang('site::messages.cancel')</span>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection