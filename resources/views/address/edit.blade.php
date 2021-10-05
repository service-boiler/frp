@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            @if($address->addressable_type == 'contragents')
                <li class="breadcrumb-item">
                    <a href="{{ route('contragents.index') }}">@lang('site::contragent.contragents_user')</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('contragents.show', $address->addressable) }}">{{$address->addressable->name}}</a>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ route('addresses.index') }}">@lang('site::address.addresses')</a>
                </li>
            @endif
            <li class="breadcrumb-item">
                <a href="{{ route('addresses.show', $address) }}">{{$address->type->name}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{ $address->type->name }}</h1>

        @alert()@endalert()
		   @if($address->type->id == '2')
			<div class="alert alert-warning ">
			@lang('site::address.help.edit')
			</div>
			@endif
        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="address-form" method="POST"
                      action="{{ route('addresses.update', $address) }}">

                    @csrf
                    @method('PUT')

                    <div class="form-row @if($address->addressable_type == 'users') required @endif">
                        <div class="col mb-3">
                            <label class="control-label" for="name">@lang('site::address.name')</label>
                            <input type="text"
                                   name="address[name]"
                                   id="name"
                                   @if($address->addressable_type == 'users')
                                   required
                                   @endif
                                   class="form-control{{ $errors->has('address.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.name')"
                                   value="{{ old('address.name',$address->name) }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.name') }}</strong>
                                    </span>
                        </div>
                    </div>
						   @if($address->addressable_type == 'users')
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="description">@lang('site::address.description')</label>
                             <textarea
                                    class="form-control{{ $errors->has('address.description') ? ' is-invalid' : '' }}"
                                    placeholder="@lang('site::address.placeholder.description')"
                                    name="address[description]"
                                    rows="5"
                                    id="description">{!! old('address.description', $address->description) !!}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('address.description') }}</span>
                        </div>
                    </div>
						  
						  
						   @endif

                    <div class="form-group required">
                        <label class="control-label"
                               for="address_type_id">@lang('site::address.type_id')</label>
                        <select required
                                id="address_type_id"
                                class="form-control{{  $errors->has('address.type_id') ? ' is-invalid' : '' }}">
                                <option value="{{ $address->type_id }}">{{  $address->type->name }}</option>
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
                                            @if(old('address.country_id',$address->country_id) == $country->id) selected
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
                        <div class="col mb-3">
                            <label class="control-label" for="postal">@lang('site::address.postal')</label>
                            <input type="text"
                                   name="address[postal]"
                                   id="postal"
                                   maxlength="8"
                                   required
                                   class="form-control{{ $errors->has('address.postal') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.postal')"
                                   value="{{ old('address.postal',$address->postal) }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address.postal') }}</strong>
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
                                            @if(old('address.region_id',$address->region_id) == $region->id) selected
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
                                   value="{{ old('address.locality',$address->locality) }}">
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
                                   value="{{ old('address.street',$address->street) }}">
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
                                           value="{{ old('address.building',$address->building) }}">
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
                                           value="{{ old('address.apartment',$address->apartment) }}">
                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.apartment') }}</strong>
                                            </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr/>

                    <div class="form-row @if(old('address.type_id', $address->type_id) == 6) required @endif">
                        <div class="col mb-3">
                            <label class="control-label" for="email">@lang('site::address.email')</label>
                            <input type="email"
                                   @if(old('address.type_id', $address->type_id) == 6) required @endif
                                   name="address[email]"
                                   id="address_email"
                                   class="form-control{{ $errors->has('address.email') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.email')"
                                   value="{{ old('address.email',$address->email) }}">
                            <span class="invalid-feedback">{{ $errors->first('address.email') }}</span>
                        </div>
                    </div>
		            <div class="form-row @if(old('address.type_id', $address->type_id) == 5) required @endif">
                        <div class="col mb-3">
                            <label class="control-label" for="email">@lang('site::address.web')</label>
                            <input type="text"
                                   @if(old('address.type_id', $address->type_id) == 5) required @endif
                                   name="address[web]"
                                   id="address_web"
                                   class="form-control{{ $errors->has('address.web') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::address.placeholder.web')"
                                   value="{{ old('address.web',$address->web) }}">
                            <span class="invalid-feedback">{{ $errors->first('address.web') }}</span>
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
                    <a href="{{ route('addresses.show', $address) }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>

                </div>
            </div>
        </div>

    </div>
@endsection