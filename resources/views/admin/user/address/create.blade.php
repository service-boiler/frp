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
                <a href="{{ route('admin.users.index') }}">@lang('site::user.users')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.show', $user) }}">{{$user->name}}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.addresses.index', $user) }}">@lang('site::address.addresses')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.create') @lang('site::address.address')</h1>

        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="address-form" method="POST"
                      action="{{ route('admin.users.addresses.store', $user) }}">

                    @csrf

                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label d-block"
                                   for="active">@lang('site::address.active')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.active') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[active]" required checked  id="active_1" value="1">
                                <label class="custom-control-label" for="active_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.active') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[active]" required id="active_0" value="0">
                                <label class="custom-control-label" for="active_0">@lang('site::messages.no')</label>
                            </div>
                        </div>
                    
                        <div class="col mb-3">
                            <label class="control-label d-block" for="is_shop">@lang('site::address.is_shop')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.is_shop') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[is_shop]" required checked id="is_shop_1" value="1">
                                <label class="custom-control-label" for="is_shop_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.is_shop') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[is_shop]" required checked id="is_shop_0" value="0">
                                <label class="custom-control-label" for="is_shop_0">@lang('site::messages.no')</label>
                            </div>
                        </div>                    
                        <div class="col mb-3">
                            <label class="control-label d-block" for="is_service">@lang('site::address.is_service')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.is_service') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[is_service]" required id="is_service_1" value="1">
                                <label class="custom-control-label" for="is_service_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input {{$errors->has('address.is_service') ? ' is-invalid' : ''}}"
                                       type="radio" name="address[is_service]" required checked id="is_service_0" value="0">
                                <label class="custom-control-label" for="is_service_0">@lang('site::messages.no')</label>
                            </div>
                        </div>
						<div class="col mb-3">
                            <label class="control-label d-block"
                                   for="is_eshop">@lang('site::address.is_eshop')</label>
                            <div class="custom-control custom-radio  custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('address.is_eshop') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="address[is_eshop]"
                                       required
                                       
                                       id="is_eshop_1"
                                       value="1">
                                <label class="custom-control-label"
                                       for="is_eshop_1">@lang('site::messages.yes')</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input class="custom-control-input
                                                    {{$errors->has('address.is_eshop') ? ' is-invalid' : ''}}"
                                       type="radio"
                                       name="address[is_eshop]" required   checked
                                       id="is_eshop_0"
                                       value="0">
                                <label class="custom-control-label"
                                       for="is_eshop_0">@lang('site::messages.no')</label>
                            </div>
                        </div>
                    </div>
					
					
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="name">@lang('site::address.name')</label>
                            <input type="text"
                                   name="address[name]"
                                   id="name"
                                   required
                                   class="form-control{{ $errors->has('address.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.name')"
                                   value="{{ old('address.name') }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.name') }}</strong>
                                    </span>
                        </div>
                    </div>

                    <div class="form-group required">
                        <label class="control-label"
                               for="type_id">@lang('site::address.type_id')</label>
                        <select class="form-control{{  $errors->has('address.type_id') ? ' is-invalid' : '' }}"
                                required
                                name="address[type_id]"
                                id="type_id">
                            @if($types->count() == 0 || $types->count() > 1)
                                <option value="">@lang('site::messages.select_from_list')</option>
                            @endif
                            @foreach($types as $type)
                                <option @if(old('address.type_id') == $type->id) selected
                                        @endif
                                        value="{{ $type->id }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                        <span class="invalid-feedback">{{ $errors->first('address.type_id') }}</span>
                    </div>

                    <div class="form-row required">
                        <div class="col mb-3">

                            <label class="control-label" for="country_id">@lang('site::address.country_id')</label>
                            <select class="country-select form-control{{  $errors->has('address.country_id') ? ' is-invalid' : '' }}"
                                    name="address[country_id]"
                                    required
                                    data-regions="#region_id"
                                    data-empty="@lang('site::messages.select_from_list')"
                                    id="country_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($countries as $country)
                                    <option
                                            @if(old('address.country_id') == $country->id) selected
                                            @endif
                                            value="{{ $country->id }}">{{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.country_id') }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col mb-3 required">

                            <label class="control-label" for="region_id">@lang('site::address.region_id')</label>
                            <select class="form-control{{  $errors->has('address.region_id') ? ' is-invalid' : '' }}"
                                    name="address[region_id]"
                                    required
                                    id="region_id">
                                <option value="">@lang('site::address.help.select_country')</option>
                                @foreach($regions as $region)
                                    <option
                                            @if(old('address.region_id') == $region->id) selected
                                            @endif
                                            value="{{ $region->id }}">{{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.region_id') }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="locality">@lang('site::address.locality')</label>
                            <input type="text"
                                   name="address[locality]"
                                   id="locality"
                                   required
                                   class="form-control{{ $errors->has('address.locality') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.locality')"
                                   value="{{ old('address.locality') }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.locality') }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="street">@lang('site::address.street')</label>
                            <input type="text"
                                   name="address[street]"
                                   id="street"
                                   class="form-control{{ $errors->has('address.street') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.street')"
                                   value="{{ old('address.street') }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.street') }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="building">@lang('site::address.building')</label>
                                    <input type="text"
                                           name="address[building]"
                                           id="building"
                                           class="form-control{{ $errors->has('address.building') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.building')"
                                           value="{{ old('address.building') }}">
                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.building') }}</strong>
                                            </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="apartment">@lang('site::address.apartment')</label>
                                    <input type="text"
                                           name="address[apartment]"
                                           id="apartment"
                                           class="form-control{{ $errors->has('address.apartment') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.apartment')"
                                           value="{{ old('address.apartment') }}">
                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.apartment') }}</strong>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>
                    {{-- E-MAIL --}}

                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="email">@lang('site::address.email')</label>
                            <input type="email"
                                   name="address[email]"
                                   id="address[email]"
                                   class="form-control{{ $errors->has('address.email') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.email')"
                                   value="{{ old('address.email') }}">
                            <span class="invalid-feedback">{{ $errors->first('address.email') }}</span>
                        </div>
                    </div>

                    {{-- E-SHOP --}}

                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="email">@lang('site::address.web')</label>
                            <input type="text"
                                   name="address[web]"
                                   id="address_web"
                                   class="form-control{{ $errors->has('address.web') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.web')"
                                   value="{{ old('address.web') }}">
                            <span class="invalid-feedback">{{ $errors->first('address.web') }}</span>
                        </div>
                    </div>


                    <hr/>

                    {{-- ТЕЛЕФОН --}}

                    <h4 class="mb-2 mt-2">@lang('site::register.sc_phone')</h4>
                    <div class="form-row required">
                        <div class="col mb-3">

                            <label class="control-label"
                                   for="phone_country_id">@lang('site::phone.country_id')</label>
                            <select class="form-control{{  $errors->has('phone.country_id') ? ' is-invalid' : '' }}"
                                    name="phone[country_id]"
                                    required
                                    id="phone_country_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($countries as $country)
                                    <option
                                            @if(old('phone.country_id') == $country->id) selected
                                            @endif
                                            value="{{ $country->id }}">{{ $country->name }}
                                        ({{ $country->phone }})
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone.country_id') }}</strong>
                                    </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col">
									<label class="control-label"
										   for="number">@lang('site::phone.number')</label>
									<input required
										   type="tel"
										   name="phone[number]"
										   id="number"
										   oninput="mask_phones()"
										   pattern="{{config('site.phone.pattern')}}"
										   maxlength="{{config('site.phone.maxlength')}}"
										   title="{{config('site.phone.format')}}"
										   data-mask="{{config('site.phone.mask')}}"
										   class="phone-mask form-control{{ $errors->has('phone.number') ? ' is-invalid' : (old('phone.number') ? ' is-valid' : '') }}"
										   placeholder="@lang('site::phone.placeholder.number')"
										   value="{{ old('phone.number') }}">
									<span class="invalid-feedback">{{ $errors->first('phone.number') }}</span>
								</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="phone_extra">@lang('site::phone.extra')</label>
                                    <input type="text"
                                           name="phone[extra]"
                                           id="phone_extra"
                                           class="form-control{{ $errors->has('phone.extra') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::phone.placeholder.extra')"
                                           value="{{ old('phone.extra') }}">
                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('phone.extra') }}</strong>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </form>
                <hr/>
                <div class=" mb-2 text-right">
                    <button form="address-form" type="submit"
                            class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('admin.users.addresses.index', $user) }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>

                </div>
            </div>
        </div>

    </div>
@endsection
