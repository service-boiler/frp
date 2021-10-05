@extends('layouts.app')
@section('title')@lang('site::register.title')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::register.title'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::register.title')]
        ]
    ])
@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <h4 class="alert-heading">@lang('site::register.help.mail')</h4>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
				<span class="d-block d-sm-inline rng"><b>@lang('site::register.help.fl')</b></span>
				
                <div class="row mt-2">
                <div class="col">
                <a  class="btn btn-ms mr-3 mb-2"  href="{{ route('register_fl') }}"><b>@lang('site::register.help.fl_btn')</b></a>
                <a  class="btn btn-ms mr-3 mb-2"  href="{{ route('register_fls') }}"><b>@lang('site::register.help.fls_btn')</b></a>
                <a  class="btn btn-ms mb-2"  href="{{ route('register_flm') }}"><b>@lang('site::register.help.flm_btn')</b></a>
				</div>
				</div>
        
        <div class="row pt-5 pb-5">
            <div class="col">
                <form id="register-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-row required">
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
                                Короткое наименование компании.
                            </small>
                        </div>
                    </div>
				
                    {{-- КОНТАКТНОЕ ЛИЦО --}}

                    <h4 class="my-4" id="sc_info">@lang('site::contact.header')</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col">
                                    <input type="hidden" name="contact[type_id]" value="1">
                                    <label class="control-label" for="contact_name">@lang('site::contact.name')</label>
                                    <input type="text" name="contact[name]" id="contact_name"
                                           class="form-control
                                           required
                                           {{$errors->has('contact.name')
                                           ? ' is-invalid'
                                           : (old('contact.name') ? ' is-valid' : '')}}"
                                           placeholder="@lang('site::contact.placeholder.name')"
                                           value="{{ old('contact.name') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact.name') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label"
                                           for="contact_position">@lang('site::contact.position')</label>
                                    <input type="text" name="contact[position]" id="contact_position"
                                           class="form-control{{ $errors->has('contact.position') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contact.placeholder.position')"
                                           value="{{ old('contact.position') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact.position') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">

                                    <label class="control-label"
                                           for="phone_contact_country_id">@lang('site::phone.country_id')</label>
                                    <select class="form-control{{  $errors->has('phone.contact.country_id') ? ' is-invalid' : (old('phone.contact.country_id') ? ' is-valid' : '') }}"
                                            required
                                            name="phone[contact][country_id]"
                                            id="phone_contact_country_id">
                                        @foreach($countries as $country)
                                            <option
                                                    @if(old('phone.contact.country_id') == $country->id) selected
                                                    @endif
                                                    value="{{ $country->id }}">{{ $country->name }}
                                                ({{ $country->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone.contact.country_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="phone_contact_number">@lang('site::phone.number')</label>
                                    <input required
                                           type="tel"
                                           name="phone[contact][number]"
                                           id="phone_contact_number"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('phone.contact.number') ? ' is-invalid' : (old('phone.contact.number') ? ' is-valid' : '') }}"
                                           placeholder="@lang('site::phone.placeholder.number')"
                                           value="{{ old('phone.contact.number') }}">
                                    <span class="invalid-feedback">{{ $errors->first('phone.contact.number') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label"
                                           for="phone_contact_extra">@lang('site::phone.extra')</label>
                                    <input type="text"
                                           name="phone[contact][extra]"
                                           id="phone_contact_extra"
                                           class="form-control{{ $errors->has('phone.contact.extra') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::phone.placeholder.extra')"
                                           value="{{ old('phone.contact.extra') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone.contact.extra') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- КОНТРАГЕНТ --}}

                    <h4 class="my-4" id="sc_info">@lang('site::contragent.header.contragent')</h4>
                    
                    
                    
                    
                     <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-4 mt-2" id="company_info">@lang('site::contragent.header.legal')</h4>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <div class="row"><div class="col">
                                    
                                    <label class="control-label"
                                           for="contragent_inn">@lang('site::contragent.inn')</label>
                                    <input type="text"
                                           name="contragent[inn]"
                                           id="contragent_inn"
                                           maxlength="12"
                                           required
                                           
                                           class="search-inn form-control{{ $errors->has('contragent.inn') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.inn')"
                                           value="{{ old('contragent.inn') }}">
                                           
                                    <span class="invalid-feedback">
                                        <strong>{!! $errors->first('contragent.inn') !!}</strong>
                                    </span>
                                    </div></div>
                                    
                                    <div class="row"><div class="col">
                                    <div class="ml-3 mt-1 search_wrapper" id="contragent_inn_wrapper"></div>
                                    </div></div>
                                </div>
                                
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_ogrn">@lang('site::contragent.ogrn')</label>
                                    <input type="text"
                                           name="contragent[ogrn]"
                                           id="contragent_ogrn"
                                           maxlength="15"
                                           required
                                           
                                           class="form-control{{ $errors->has('contragent.ogrn') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.ogrn')"
                                           value="{{ old('contragent.ogrn') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.ogrn') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_okpo">@lang('site::contragent.okpo')</label>
                                    <input type="text"
                                           name="contragent[okpo]"
                                           id="contragent_okpo"
                                           maxlength="10"
                                           required
                                           
                                           class="form-control{{ $errors->has('contragent.okpo') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.okpo')"
                                           value="{{ old('contragent.okpo') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.okpo') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_kpp">@lang('site::contragent.kpp')</label>
                                    <input type="text"
                                           name="contragent[kpp]"
                                           id="contragent_kpp"
                                           maxlength="9"
                                           class="form-control{{ $errors->has('contragent.kpp') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.kpp')"
                                           value="{{ old('contragent.kpp') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.kpp') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-4 mt-2" id="company_info">@lang('site::contragent.header.payment')</h4>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_rs">@lang('site::contragent.rs')</label>
                                    <input type="text"
                                           name="contragent[rs]"
                                           required
                                           id="contragent_rs" maxlength="20"
                                           
                                           class="form-control{{ $errors->has('contragent.rs') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.rs')"
                                           value="{{ old('contragent.rs') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.rs') }}</strong>
                                    </span>
                                </div>
                            </div>


                            <div class="form-row required">
                                <div class="col mb-3">
                                    <div class="row"><div class="col">
                                    <label class="control-label"
                                           for="contragent_bik">@lang('site::contragent.bik')</label>
                                    <input type="text"
                                           name="contragent[bik]"
                                           id="contragent_bik"
                                           required
                                           maxlength="11"
                                           class="search-bik form-control{{ $errors->has('contragent.bik') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.bik')"
                                           value="{{ old('contragent.bik') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.bik') }}</strong>
                                    </span>
                                    </div></div>
                                    <div class="row"><div class="col">
                                    <div class="ml-3 mt-1 search_wrapper" id="contragent_bik_wrapper"></div>
                                    </div></div>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_bank">@lang('site::contragent.bank')</label>
                                    <input type="text"
                                           name="contragent[bank]"
                                           id="contragent_bank"
                                           required
                                           maxlength="255"
                                           class="form-control{{ $errors->has('contragent.bank') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.bank')"
                                           value="{{ old('contragent.bank') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.bank') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_ks">@lang('site::contragent.ks')</label>
                                    <input type="number"
                                           name="contragent[ks]"
                                           id="contragent_ks"
                                           maxlength="20"
                                           pattern="\d{20}"

                                           class="form-control{{ $errors->has('contragent.ks') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.ks')"
                                           value="{{ old('contragent.ks') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.ks') }}</strong>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">

                            {{-- ЮРИДИЧЕСКИЙ АДРЕС --}}

                            <h4 class="mb-2 mt-4">@lang('site::address.header.legal')</h4>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <input type="hidden"
                                           name="address[legal][type_id]"
                                           value="1">
                                    <label class="control-label"
                                           for="address_legal_country_id">@lang('site::address.country_id')</label>
                                    <select class="country-select form-control
                                    {{$errors->has('address.legal.country_id') ? ' is-invalid' : ''}}"
                                            data-regions="#address_legal_region_id"
                                            data-empty="@lang('site::messages.select_from_list')"
                                            required
                                            name="address[legal][country_id]"
                                            id="address_legal_country_id">
                                        @if($countries->count() == 0 || $countries->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($countries as $country)
                                            <option
                                                    @if(old('address.legal.country_id') == $country->id) selected
                                                    @endif
                                                    value="{{ $country->id }}">{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.legal.country_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">

                                    <label class="control-label"
                                           for="address_legal_region_id">@lang('site::address.region_id')</label>
                                    <select class="form-control{{  $errors->has('address.legal.region_id') ? ' is-invalid' : '' }}"
                                            name="address[legal][region_id]"
                                            required
                                            id="address_legal_region_id">
                                        @if($regions->count() == 0 || $regions->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('address.legal.region_id') == $region->id) selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.legal.region_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="address_legal_locality">@lang('site::address.locality')</label>
                                    <input type="text"
                                           name="address[legal][locality]"
                                           id="address_legal_locality"
                                           required
                                           class="form-control{{ $errors->has('address.legal.locality') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.locality')"
                                           value="{{ old('address.legal.locality') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.legal.locality') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="address_legal_street">@lang('site::address.street')</label>
                                    <input type="text"
                                           name="address[legal][street]"
                                           id="address_legal_street"
                                           class="form-control{{ $errors->has('address.legal.street') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.street')"
                                           value="{{ old('address.legal.street') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.legal.street') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_legal_building">@lang('site::address.building')</label>
                                            <input type="text"
                                                   name="address[legal][building]"
                                                   id="address_legal_building"
                                                   required
                                                   class="form-control{{ $errors->has('address.legal.building') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.building')"
                                                   value="{{ old('address.legal.building') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.legal.building') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_legal_apartment">@lang('site::address.apartment')</label>
                                            <input type="text"
                                                   name="address[legal][apartment]"
                                                   id="address_legal_apartment"
                                                   class="form-control{{ $errors->has('address.legal.apartment') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.apartment')"
                                                   value="{{ old('address.legal.apartment') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.legal.apartment') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">

                            {{-- ПОЧТОВЫЙ АДРЕС --}}

                            <h4 class="mb-2 mt-4">@lang('site::address.header.postal')</h4>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <input type="hidden"
                                           name="address[postal][type_id]"
                                           value="3">
                                    <label class="control-label"
                                           for="address_postal_country_id">@lang('site::address.country_id')</label>
                                    <select class="country-select form-control{{  $errors->has('address.postal.country_id') ? ' is-invalid' : '' }}"
                                            name="address[postal][country_id]"
                                            required
                                            data-regions="#address_postal_region_id"
                                            data-empty="@lang('site::messages.select_from_list')"
                                            id="address_postal_country_id">
                                        @if($countries->count() == 0 || $countries->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($countries as $country)
                                            <option
                                                    @if(old('address.postal.country_id') == $country->id) selected
                                                    @endif
                                                    value="{{ $country->id }}">{{ $country->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.postal.country_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3 required">

                                    <label class="control-label"
                                           for="address_postal_region_id">@lang('site::address.region_id')</label>
                                    <select class="form-control{{  $errors->has('address.postal.region_id') ? ' is-invalid' : '' }}"
                                            name="address[postal][region_id]"
                                            required
                                            id="address_postal_region_id">
                                        @if($regions->count() == 0 || $regions->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('address.postal.region_id') == $region->id) selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.postal.region_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="address_postal_locality">@lang('site::address.locality')</label>
                                    <input type="text"
                                           name="address[postal][locality]"
                                           id="address_postal_locality"
                                           required
                                           class="form-control{{ $errors->has('address.postal.locality') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.locality')"
                                           value="{{ old('address.postal.locality') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.postal.locality') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="address_postal_street">@lang('site::address.street')</label>
                                    <input type="text"
                                           name="address[postal][street]"
                                           id="address_postal_street"
                                           class="form-control{{ $errors->has('address.postal.street') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.street')"
                                           value="{{ old('address.postal.street') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.postal.street') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_postal_building">@lang('site::address.building')</label>
                                            <input type="text"
                                                   name="address[postal][building]"
                                                   required
                                                   id="address_postal_building"
                                                   class="form-control{{ $errors->has('address.postal.building') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.building')"
                                                   value="{{ old('address.postal.building') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.postal.building') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_postal_postal">@lang('site::address.postal')</label>
                                            <input type="text"
                                                   name="address[postal][postal]"
                                                   id="address_postal_postal"
                                                   class="form-control{{ $errors->has('address.postal.postal') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.postal')"
                                                   value="{{ old('address.postal.postal') }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.postal.postal') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="contragent_name">@lang('site::contragent.name')</label>
                            <input type="text"
                                   required
                                   name="contragent[name]"
                                   id="contragent_name"
                                   class="form-control{{ $errors->has('contragent.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contragent.placeholder.name')"
                                   value="{{ old('contragent.name') }}">
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('contragent.name') }}</strong>
                            </span>
                            <small id="contragent_nameHelp" class="form-text text-success">
                                @lang('site::contragent.help.name')
                            </small>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="contragent_name_short">@lang('site::contragent.name_short')</label>
                            <input type="text"
                                   required
                                   name="contragent[name_short]"
                                   id="contragent_name_short"
                                   class="form-control{{ $errors->has('contragent.name_short') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contragent.placeholder.name_short')"
                                   value="{{ old('contragent.name_short') }}">
                            <span class="invalid-feedback">
                                <strong>{{ $errors->first('contragent.name_short') }}</strong>
                            </span>
                            <small id="contragent_nameHelp" class="form-text text-success">
                                @lang('site::contragent.help.name_short')
                            </small>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_type_id"><strong>@lang('site::contragent.type_id')</strong></label>
                                    @foreach($types as $key => $type)
                                        <div class="custom-control custom-radio">
                                            <input class="custom-control-input
                                            {{$errors->has('contragent.type_id') ? ' is-invalid' : ''}}"
                                                   type="radio"
                                                   required
                                                   name="contragent[type_id]"
                                                   @if(old('contragent.type_id') == $type->id) checked @endif
                                                   id="contragent_type_id_{{ $type->id }}"
                                                   value="{{ $type->id }}">
                                            <label class="custom-control-label"
                                                   for="contragent_type_id_{{ $type->id }}">
                                                {{ $type->name }}
                                            </label>
                                            @if($key == $types->count()-1)
                                                <span class="invalid-feedback">
                                                    <strong>{{ $errors->first('contragent.type_id') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="contragent_nds">@lang('site::contragent.nds')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_1"
                                                       name="contragent[nds]"
                                                       required
                                                       @if(old('contragent.nds') == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('contragent.nds') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_0"
                                                       name="contragent[nds]"
                                                       required
                                                       @if(old('contragent.nds') == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('contragent.nds') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('contragent.nds') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="contragent_nds_act">@lang('site::contragent.nds_act')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_act_1"
                                                       name="contragent[nds_act]"
                                                       required
                                                       @if(old('contragent.nds_act') == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('contragent.nds_act') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_act_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_act_0"
                                                       name="contragent[nds_act]"
                                                       required
                                                       @if(old('contragent.nds_act') == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('contragent.nds_act') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_act_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('contragent.nds_act') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    
                    
                    
                    
                    
                    
                    
                    

                    <h4 class="my-4" id="company_info">@lang('site::user.header.info')</h4>

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
                            <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            <small id="emailHelp" class="form-text text-success">
                                @lang('site::user.help.email')
                            </small>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label" for="password">@lang('site::user.password')</label>
                            <input type="password"
                                   name="password"
                                   required
                                   id="password"
                                   minlength="6"
                                   maxlength="20"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.password')"
                                   value="{{ old('password') }}">
                            <span class="invalid-feedback">{{ $errors->first('password') }}</span>

                        </div>
                    </div>

                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label"
                                   for="password-confirmation">@lang('site::user.password_confirmation')</label>
                            <input id="password-confirmation"
                                   type="password"
                                   required
                                   class="form-control"
                                   placeholder="@lang('site::user.placeholder.password_confirmation')"
                                   name="password_confirmation">
                        </div>
                    </div>

                    <hr class="my-4"/>

                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label"
                                   for="captcha">@lang('site::register.captcha')</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text"
                                           name="captcha"
                                           required
                                           id="captcha"
                                           class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::register.placeholder.captcha')"
                                           value="">
                                    <span class="invalid-feedback">{{ $errors->first('captcha') }}</span>
                                </div>
                                <div class="col-md-9 captcha">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <button data-toggle="tooltip" data-placement="top"
                                            title="@lang('site::messages.refresh')" type="button"
                                            class="btn btn-outline-secondary" id="captcha-refresh">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <div class="form-row required">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="accept" required value="1" class="custom-control-input"
                                       id="accept">
                                <label class="custom-control-label" for="accept"><span
                                            style="color:red;margin-right: 2px;">*</span>@lang('site::register.accept')
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-row text-center mt-5 mb-3">
                        <div class="col">
                            <button class="btn btn-ms" type="submit">@lang('site::user.sign_up')</button>
                        </div>
                    </div>
                </form>
                <div class="text-center">
                    <a class="d-block" href="{{route('login')}}">@lang('site::user.already')</a>
                </div>
                <div class="text-center mb-3">
                    <h5>@lang('site::register.help.mail')</h5>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script defer>
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
    try {
        document.querySelector('#captcha-refresh').addEventListener('click', function () {
            fetch('/captcha/flat')
                .then(response => {
                    response.blob().then(blobResponse => {
                        const urlCreator = window.URL || window.webkitURL;
                        document.querySelector('.captcha span img').src = urlCreator.createObjectURL(blobResponse);
                    });
                });
        });

       /* document.querySelector('#legal').addEventListener('change', function(event){
            document.querySelector('#legal-fields').disabled = event.target.checked;
        })
        */
        
        window.addEventListener('load', function () {

                let contragent_type_id_1 = $('#contragent_type_id_1');
                let contragent_type_id_2 = $('#contragent_type_id_2');
                
                 
                contragent_type_id_1.on('change', function (e) {
                    $('#contragent_kpp').prop('disabled', false);
                }); 
                contragent_type_id_2.on('change', function (e) {
                    $('#contragent_kpp').prop('disabled', true);
                }); 
                
                
                suggest_count = 0;
                input_initial_value = '';
                suggest_selected = 0;
                
                 $('html').click(function(){
                    $('#search_wrapper').hide();
                }); 
               
                $(document)
                    .on('keyup', '.search-inn', (function(I){
                        
                        var field_name = $(this)[0].dataset.fieldName;
                        switch(I.keyCode) {
                            // игнорируем нажатия 
                            case 13:  // enter
                            case 27:  // escape
                            case 38:  // стрелка вверх
                            case 40:  // стрелка вниз
                            break;

                            default:
                                $(this).attr('autocomplete','off');
                            
                                if($(this).val().length>3){

                                    input_initial_value = $(this).val();
                                    $.get("/api/dadata/inn", { "str":$(this).val() },function(data){
                                        var list = JSON.parse(data);
                                        
                                        suggest_count = list.length;
                                        if(suggest_count > 0){
                                            $('#contragent_inn_wrapper').html("").show();
                                            for(var i in list){
                                                if(list[i] != ''){
                                               
                                                    $('#contragent_inn_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].inn+'">'+list[i].inn+' '+list[i].name+'</div>');
                                                    $('#contragent_inn_wrapper').find('#result_id-'+list[i].inn).click(function() {
                                                         console.log(list[$(this)[0].getAttribute('data-key')]);
                                                        
document.getElementById('contragent_name').value = list[$(this)[0].getAttribute('data-key')].alldata.name.full_with_opf;
if(list[$(this)[0].getAttribute('data-key')].alldata.name.short_with_opf) {
    document.getElementById('contragent_name_short').value = list[$(this)[0].getAttribute('data-key')].alldata.name.short_with_opf;
} else {
    document.getElementById('contragent_name_short').value = list[$(this)[0].getAttribute('data-key')].alldata.opf.short + ' ' + list[$(this)[0].getAttribute('data-key')].alldata.name.full;
}

document.getElementById('contragent_inn').value = list[$(this)[0].getAttribute('data-key')].alldata.inn;
document.getElementById('contragent_ogrn').value = list[$(this)[0].getAttribute('data-key')].alldata.ogrn;
if(list[$(this)[0].getAttribute('data-key')].alldata.okpo) 
    {document.getElementById('contragent_okpo').value = list[$(this)[0].getAttribute('data-key')].alldata.okpo;}
if(list[$(this)[0].getAttribute('data-key')].alldata.kpp) 
    {document.getElementById('contragent_kpp').value = list[$(this)[0].getAttribute('data-key')].alldata.kpp;}
    
   let address = list[$(this)[0].getAttribute('data-key')].alldata.address.data;
    
if(address.region_iso_code) 
    {document.getElementById('address_legal_region_id').value = address.region_iso_code;
    document.getElementById('address_postal_region_id').value = address.region_iso_code;}

if(address.city_with_type) 
    {document.getElementById('address_legal_locality').value = address.city_with_type;
    document.getElementById('address_postal_locality').value = address.city_with_type;}
    
if(address.street_with_type) 
    {document.getElementById('address_legal_street').value = address.street_with_type;
    document.getElementById('address_postal_street').value = address.street_with_type;}
    
if(address.house) 
    {document.getElementById('address_legal_building').value = address.house_type + ' ' +address.house;
    document.getElementById('address_postal_building').value = address.house_type + ' ' +address.house;}
    
if(address.block) 
    {document.getElementById('address_legal_building').value = document.getElementById('address_legal_building').value + ' ' +address.block_type + ' ' +address.block;
    document.getElementById('address_postal_building').value = document.getElementById('address_postal_building').value + ' ' +address.block_type + ' ' +address.block;}
    
if(address.flat) 
    {document.getElementById('address_legal_apartment').value = address.flat_type + ' ' +address.flat;}

if(address.postal_code) 
    {document.getElementById('address_postal_postal').value = address.postal_code;}
                                                        
                                                        $('#contragent_inn_wrapper').fadeOut(2350).html('');
                                                    });
                                                }
                                            }
                                        }
                                    }, 'html');
                                }
                            break;
                        }
                    })
                    )

                    
                    .on('keydown', '.search_address', (function(I){
                        switch(I.keyCode) {
                            case 27: // escape
                                $('#search_wrapper').hide();
                                return false;
                            break;
                            
                        }
                    })
                    );

                     $('html').on('click', '.search_wrapper', (function(){
                        $(this).hide();
                    })); 
                    
                    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
                    $('#search_address').click(function(event){
                        if(suggest_count)
                            $('#search_address_wrapper').show();
                        event.stopPropagation();
                    });

                
                $(document)
                    .on('keyup', '.search-bik', (function(I){
                        
                        var field_name = $(this)[0].dataset.fieldName;
                        switch(I.keyCode) {
                            // игнорируем нажатия 
                            case 13:  // enter
                            case 27:  // escape
                            case 38:  // стрелка вверх
                            case 40:  // стрелка вниз
                            break;

                            default:
                                $(this).attr('autocomplete','off');
                            $('#contragent_bik_wrapper').hide();
                                if($(this).val().length>6){

                                    input_initial_value = $(this).val();
                                    $.get("/api/dadata/bank", { "str":$(this).val() },function(data){
                                        var list = JSON.parse(data);
                                        
                                        suggest_count = list.length;
                                        if(suggest_count > 0){
                                            $('#contragent_bik_wrapper').html("").show();
                                            for(var i in list){
                                                if(list[i] != ''){
                                               
                                                    $('#contragent_bik_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].bic+'">'+list[i].bic+' '+list[i].name+'</div>');
                                                    $('#contragent_bik_wrapper').find('#result_id-'+list[i].bic).click(function() {
                                                         
                                                        
                                document.getElementById('contragent_ks').value = list[$(this)[0].getAttribute('data-key')].alldata.correspondent_account;                
                                document.getElementById('contragent_bik').value = list[$(this)[0].getAttribute('data-key')].alldata.bic;                
                                document.getElementById('contragent_bank').value = list[$(this)[0].getAttribute('data-key')].name;                
                                                        
                                                        $('#contragent_bik_wrapper').fadeOut(2350).html('');
                                                    });
                                                }
                                            }
                                        }
                                    }, 'html');
                                }
                            break;
                        }
                    })
                    );
                
               
                

            });
        
        
    } catch (e) {
        console.log(e);
    }
</script>
@endpush
