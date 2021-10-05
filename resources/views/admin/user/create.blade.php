@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">@lang('site::user.users')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add') дилера или АСЦ</li>
        </ol>
        

        @alert()@endalert

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <form id="register-form" method="POST" action="{{ route('admin.users.store') }}">
                            @csrf

                            <input type="hidden" name="verified" value="0">
                            <input type="hidden" name="dealer" value="1">
                            <input type="hidden" name="sc[type_id]" value="2">

                            <div class="form-row required mb-3">
                                <div class="col">
                                    <label class="control-label" for="name">@lang('site::user.name')</label>
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           required
                                           class="form-control form-control-lg
                                    {{ $errors->has('name')
                                    ? ' is-invalid'
                                    : (old('name') ? ' is-valid' : '') }}"
                                           placeholder="@lang('site::user.placeholder.name')"
                                           value="{{ old('name') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                    <small id="nameHelp" class="form-text text-success">
                                        Юридическое имя компании, по которой бухгалтерия может быстро идентифицировать клиента. 
                                    </small>
                                </div>
                            </div>
                            <div class="form-row required mb-3">
                                <div class="col">
                                    <label class="control-label" for="name_for_site">Название для сайта</label>
                                    <input type="text"
                                           name="name_for_site"
                                           id="name_for_site"
                                           required
                                           class="form-control form-control-lg
                                    {{ $errors->has('name_for_site')
                                    ? ' is-invalid'
                                    : (old('name_for_site') ? ' is-valid' : '') }}"
                                           placeholder="Например, Тадел"
                                           value="{{ old('name_for_site') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name_for_site') }}</strong>
                                    </span>
                                    <small id="nameHelp" class="form-text text-success">
                                        Указывайте общеизвестное название компании или бренд. 
                                    </small>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>@lang('site::register.sc_phone')</h4>
                                    <div class="form-row required">
                                        <div class="col">

                                            <label class="control-label"
                                                   for="phone_sc_country_id">@lang('site::phone.country_id')</label>
                                            <select class="form-control{{  $errors->has('phone.sc.country_id') ? ' is-invalid' : '' }}"
                                                    name="phone[sc][country_id]"
                                                    required
                                                    id="phone_sc_country_id">
                                                @if($countries->count() == 0 || $countries->count() > 1)
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                @endif
                                                @foreach($countries as $country)
                                                    <option
                                                            @if(old('phone.sc.country_id') == $country->id) selected
                                                            @endif
                                                            value="{{ $country->id }}">{{ $country->name }}
                                                        ({{ $country->phone }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('phone.sc.country_id') }}</strong>
                                                </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-row required">
                                                <div class="col">
                                                    <label class="control-label" for="phone_sc_number">@lang('site::phone.number')</label>
                                                    <input required
                                                           type="tel"
                                                           name="phone[sc][number]"
                                                           id="phone_sc_number"
                                                           oninput="mask_phones()"
                                                           pattern="{{config('site.phone.pattern')}}"
                                                           maxlength="{{config('site.phone.maxlength')}}"
                                                           title="{{config('site.phone.format')}}"
                                                           data-mask="{{config('site.phone.mask')}}"
                                                           class="phone-mask form-control{{ $errors->has('phone.sc.number') ? ' is-invalid' : '' }}"
                                                           placeholder="@lang('site::engineer.placeholder.phone')"
                                                           value="{{ old('phone.sc.number') }}">
                                                    <span class="invalid-feedback">{{ $errors->first('phone.sc.number') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-row">
                                                <div class="col">
                                                    <label class="control-label"
                                                           for="phone_sc_extra">@lang('site::phone.extra')</label>
                                                    <input type="text"
                                                           name="phone[sc][extra]"
                                                           id="phone_sc_extra"
                                                           class="form-control{{ $errors->has('phone.sc.extra') ? ' is-invalid' : '' }}"
                                                           placeholder="@lang('site::phone.placeholder.extra')"
                                                           value="{{ old('phone.sc.extra') }}">
                                                    <span class="invalid-feedback">
                                                            <strong>{{ $errors->first('phone.sc.extra') }}</strong>
                                                        </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">

                                    {{-- АДРЕС АСЦ --}}

                                    <h4>@lang('site::register.sc_address')</h4>

                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label"
                                                   for="address_sc_name">@lang('site::address.name')</label>
                                            <input type="text"
                                                   name="address[sc][name]"
                                                   id="address_sc_name"
                                                   required
                                                   class="form-control{{ $errors->has('address.sc.name') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.name')"
                                                   value="{{ old('address.sc.name') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.sc.name') }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-row required">
                                        <div class="col">
                                            <input type="hidden" name="address[sc][type_id]" value="2">
                                            <label class="control-label"
                                                   for="address_sc_country_id">@lang('site::address.country_id')</label>
                                            <select class="country-select form-control{{  $errors->has('address.sc.country_id') ? ' is-invalid' : '' }}"
                                                    name="address[sc][country_id]"
                                                    required
                                                    data-regions="#address_sc_region_id"
                                                    data-empty="@lang('site::messages.select_from_list')"
                                                    id="address_sc_country_id">
                                                @if($countries->count() == 0 || $countries->count() > 1)
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                @endif
                                                @foreach($countries as $country)
                                                    <option
                                                            @if(old('address.sc.country_id') == $country->id) selected
                                                            @endif
                                                            value="{{ $country->id }}">{{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.sc.country_id') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-row required">
                                        <div class="col">

                                            <label class="control-label"
                                                   for="address_sc_region_id">@lang('site::address.region_id')</label>
                                            <select class="form-control{{  $errors->has('address.sc.region_id') ? ' is-invalid' : '' }}"
                                                    name="address[sc][region_id]"
                                                    required
                                                    id="address_sc_region_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($regions as $region)
                                                    <option
                                                            @if(old('address.sc.region_id') == $region->id) selected
                                                            @endif
                                                            value="{{ $region->id }}">{{ $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.sc.region_id') }}</strong>
                                            </span>
                                        </div>
                                    </div>


                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label"
                                                   for="address_sc_locality">@lang('site::address.locality')</label>
                                            <input type="text"
                                                   name="address[sc][locality]"
                                                   id="address_sc_locality"
                                                   required
                                                   class="form-control{{ $errors->has('address.sc.locality') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.locality')"
                                                   value="{{ old('address.sc.locality') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.sc.locality') }}</strong>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label" for="email">@lang('site::user.email')</label>
                                            <input type="email"
                                                   name="email"
                                                   id="email"
                                                   required
                                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::user.placeholder.email')"
                                                   value="{{ old('email') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-row">
                                        <div class="col">
                                            <label class="control-label"
                                                   for="web">@lang('site::user.web')</label>
                                            <input type="text"
                                                   name="web"
                                                   id="web"
                                                   class="form-control{{ $errors->has('web') ? ' is-invalid' : '' }}"
                                                   pattern="https?://.+" title="@lang('site::user.help.web')"
                                                   placeholder="@lang('site::user.placeholder.web')"
                                                   value="{{ old('web') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('web') }}</strong>
                                            </span>
                                            <small id="webHelp" class="form-text text-success">
                                                @lang('site::user.help.web')
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label d-block"
                                           for="active">Пользователь включен?</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="active"
                                               required
                                               @if(old('active', 1) == 1) checked @endif
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
                                               @if(old('active', 1) == 0) checked @endif
                                               id="active_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="active_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label d-block"
                                           for="display">Отображать пользователя?</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('display') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="display"
                                               required
                                               @if(old('display', 1) == 1) checked @endif
                                               id="display_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="display_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('display') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="display"
                                               required
                                               @if(old('display', 1) == 0) checked @endif
                                               id="display_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="display_0">@lang('site::messages.no')</label>
                                    </div>

                                </div>
                            </div>
 </div>
                </div>

                            <div class="card mt-2">
                        <div class="card-body">
                            <div class="row">
                        <div class="col-md-4">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.show_ferroli')) checked @endif
                                               class="custom-control-input{{  $errors->has('address.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="address[sc][show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.show_ferroli') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.show_lamborghini')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('address.show_lamborghini') ? ' is-invalid' : '' }}"
                                               id="show_lamborghini"
                                               name="address[sc][show_lamborghini]">
                                        <label class="custom-control-label"
                                               for="show_lamborghini">@lang('site::messages.show_lamborghini')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.show_lamborghini') }}</span>
                                    </div>
                                </div>
                            </div>
									 
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.show_market_ru')) checked @endif
                                               class="custom-control-input{{  $errors->has('address.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="address[sc][show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.show_market_ru') }}</span>
                                    </div>
                                </div>
                            </div>
									 
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.approved')) checked @endif
                                               class="custom-control-input{{  $errors->has('address.approved') ? ' is-invalid' : '' }}"
                                               id="approved"
                                               name="address[sc][approved]">
                                        <label class="custom-control-label"
                                               for="approved">@lang('site::messages.approved')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.approved') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" @if(old('address.is_shop')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('address.is_shop') ? ' is-invalid' : '' }}"
                                               id="is_shop"
                                               name="address[sc][is_shop]">
                                        <label class="custom-control-label"
                                               for="is_shop">@lang('site::address.is_shop')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.is_shop') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.is_service')) checked @endif
                                               class="custom-control-input{{  $errors->has('address.is_service') ? ' is-invalid' : '' }}"
                                               id="is_service"
                                               name="address[sc][is_service]">
                                        <label class="custom-control-label"
                                               for="is_service">@lang('site::address.is_service')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.is_service') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" @if(old('address.is_eshop')) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('address.is_eshop') ? ' is-invalid' : '' }}"
                                               id="is_eshop"
                                               name="address[sc][is_eshop]">
                                        <label class="custom-control-label"
                                               for="is_eshop">@lang('site::address.is_eshop')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.is_eshop') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('address.is_mounter')) checked @endif
                                               class="custom-control-input{{  $errors->has('address.is_mounter') ? ' is-invalid' : '' }}"
                                               id="is_mounter"
                                               name="address[sc][is_mounter]">
                                        <label class="custom-control-label"
                                               for="is_mounter">@lang('site::address.is_mounter')</label>
                                        <span class="invalid-feedback">{{ $errors->first('address.is_mounter') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">

                            @foreach($product_group_types as $product_group_type)
                                <div class="form-row">
                                    <div class="col">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox"
                                                   @if((is_array(old('product_group_types')) && in_array($product_group_type->id, old('product_group_types'))))
                                                   checked
                                                   @endif
                                                   class="custom-control-input{{  $errors->has('product_group_types') ? ' is-invalid' : '' }}"
                                                   id="pgt-{{$product_group_type->id}}"
                                                   value="{{$product_group_type->id}}"
                                                   name="product_group_types[]">
                                            <label class="custom-control-label"
                                                   for="pgt-{{$product_group_type->id}}">{{$product_group_type->name}}</label>
                                            <span class="invalid-feedback">{{ $errors->first('product_group_types') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                            

                            {{--<div class="form-row required">--}}
                            {{--<div class="col">--}}
                            {{--<label class="control-label" for="password">@lang('site::user.password')</label>--}}
                            {{--<input type="password"--}}
                            {{--name="password"--}}
                            {{--required--}}
                            {{--id="password"--}}
                            {{--minlength="6"--}}
                            {{--maxlength="20"--}}
                            {{--class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"--}}
                            {{--placeholder="@lang('site::user.placeholder.password')"--}}
                            {{--value="{{ old('password') }}">--}}
                            {{--<span class="invalid-feedback">--}}
                            {{--<strong>{{ $errors->first('password') }}</strong>--}}
                            {{--</span>--}}

                            {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-row required">--}}
                            {{--<div class="col">--}}
                            {{--<label class="control-label"--}}
                            {{--for="password-confirmation">@lang('site::user.password_confirmation')</label>--}}
                            {{--<input id="password-confirmation"--}}
                            {{--type="password"--}}
                            {{--required--}}
                            {{--class="form-control"--}}
                            {{--placeholder="@lang('site::user.placeholder.password_confirmation')"--}}
                            {{--name="password_confirmation">--}}
                            {{--</div>--}}
                            {{--</div>--}}
                    </div>
                </div>

                            <div class="card mt-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('rbac::role.roles')</h5>
                            <div class="form-row">
                                <div class="col mb-3">
                                    @foreach($roles->all() as $role)

                                        <div class="custom-control custom-checkbox"
                                             style="@if($role->name == 'admin') display:none;@endif">
                                            <input name="roles[]"
                                                   value="{{ $role->id }}"
                                                   type="checkbox"
                                                   class="custom-control-input" id="role-{{ $role->id }}">
                                            <label class="custom-control-label"
                                                   for="role-{{ $role->id }}">{{ $role->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-ms">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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
