@extends('layouts.app')

@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('partner-plus-requests.index') }}">@lang('site::user.partner_plus_request.partner_plus_requests')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('partner-plus-requests.show',$partnerPlusRequest) }}">{{$user->name_for_site ? $user->name_for_site : $user->name}}</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
    </ol>
    @alert()@endalert()
    
    
    <div class="card-body">
        <form id="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('partner-plus-requests.update',$partnerPlusRequest) }}">
                    @csrf
                    @method('PUT')

    <input type="hidden" name="partnerPlusRequest[partner_id]" value="{{$user->id}}">

        <div class="form-group required">
            
            <label class="control-label" for="name_for_site">@lang('site::user.partner_plus_request.name_for_site')</label>
            <div class="input-group">
                        <input name="partnerPlusRequest[name_for_site]" id="name_for_site"
                        class="form-control{{ $errors->has('partnerPlusRequest.name_for_site') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.name_for_site',$partnerPlusRequest->name_for_site)}}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.shop_count') }}</span>
            
        </div>    
        <div class="form-group required">
            
            <label class="control-label" for="distributor_id">@lang('site::user.partner_plus_request.distributor')</label>
            <div class="col h5" id="users_search_sel">{{$partnerPlusRequest->distributor->name}}</div>
            <span id="usersHelp" class="d-block form-text text-success">
                Введите название или ИНН и выберите вариант из выпадающего списка.
            </span>
                        <fieldset id="users-search-fieldset"
                                  style="display: block; padding-left: 5px;">
                            <div class="form-row">
                                <select class="form-control" id="users_search"  name="partnerPlusRequest[distributor_id]">
                                @if(old('partnerPlusRequest.distributor_id',$partnerPlusRequest->distributor_id)) 
                                <option selected value="{{old('partnerPlusRequest.distributor_id',$partnerPlusRequest->distributor_id)}}"> 
                                {{old('partnerPlusRequest.distributor_id',$partnerPlusRequest->distributor->name)}}</option>@endif
                                    <option></option>
                                </select>
                                
                            </div>
                        </fieldset>
           
        </div>
        
        <div class="form-group required">
            <label class="control-label"
                   for="partner_contragent_id">@lang('site::user.partner_plus_request.contragent')</label>
            <div class="input-group">
                <select required
                        class="form-control{{  $errors->has('partnerPlusRequest.partner_contragent_id') ? ' is-invalid' : '' }}"
                        name="partnerPlusRequest[partner_contragent_id]"
                        id="partner_contragent_id">
                    @if($contragents->count() == 0 || $contragents->count() > 1)
                        <option value="">@lang('site::messages.select_from_list')</option>
                    @endif
                    @foreach($contragents as $contragent)
                        <option
                                @if(old('partnerPlusRequest.partner_contragent_id',$partnerPlusRequest->partner_contragent_id) == $contragent->id) selected
                                @endif
                                value="{{ $contragent->id }}">
                            {{ $contragent->name }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-@lang('site::contragent.icon')"></i>
                    </div>
                </div>
            </div>
            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.partner_contragent_id') }}</span>
        </div>
        
      
        <div class="form-group required">
            <label class="control-label"
                   for="partner_address_id">@lang('site::user.partner_plus_request.address')</label>
            <div class="input-group">
                <select required
                        class="form-control{{  $errors->has('partnerPlusRequest.address') ? ' is-invalid' : '' }}"
                        name="partnerPlusRequest[partner_address_id]"
                        id="partner_address_id">
                    @if($addresses->count() == 0 || $addresses->count() > 1)
                        <option value="">@lang('site::messages.select_from_list')</option>
                    @endif
                    @foreach($addresses as $address)
                        <option
                                @if(old('partnerPlusRequest.partner_address_id',$partnerPlusRequest->partner_address_id) == $address->id) selected
                                @endif
                                value="{{ $address->id }}">
                            {{ $address->name }} {{ $address->full }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-@lang('site::address.icon')"></i>
                    </div>
                </div>
            </div>
            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.address') }}</span>
        </div>
        
        <div class="row">
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="found_year">@lang('site::user.partner_plus_request.found_year')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[found_year]"
                        id="found_year" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.found_year') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.found_year',$partnerPlusRequest->found_year)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.found_year') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="sales_staff">@lang('site::user.partner_plus_request.sales_staff')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[sales_staff]"
                        id="sales_staff" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.sales_staff') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.sales_staff',$partnerPlusRequest->sales_staff)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.sales_staff') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="shop_count">@lang('site::user.partner_plus_request.shop_count')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[shop_count]"
                        id="shop_count" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.shop_count') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.shop_count',$partnerPlusRequest->shop_count)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.shop_count') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="annual_turnover">@lang('site::user.partner_plus_request.annual_turnover')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[annual_turnover]"
                        id="annual_turnover" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.annual_turnover') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.annual_turnover',$partnerPlusRequest->annual_turnover)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.annual_turnover') }}</span>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="warehouse_area">@lang('site::user.partner_plus_request.warehouse_area')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[warehouse_area]"
                        id="warehouse_area" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.warehouse_area') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.warehouse_area',$partnerPlusRequest->warehouse_area)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.warehouse_area') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="warehouse_count">@lang('site::user.partner_plus_request.warehouse_count')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[warehouse_count]"
                        id="warehouse_count" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.warehouse_count') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.warehouse_count',$partnerPlusRequest->warehouse_count)}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.warehouse_count') }}</span>
                </div>
            </div>
            <div class="col-3 mr-0">
                <div class="form-group required">
                     <label class="control-label col pl-sm-0"
                               for="has_service">@lang('site::user.partner_plus_request.has_service')</label>
                        <div class="custom-control custom-radio custom-control-inline ml-sm-4">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_service') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_service]"
                                   required
                                   @if(old('partnerPlusRequest.has_service',$partnerPlusRequest->has_service) == 1) checked
                                   @endif
                                   id="has_service_1"
                                   value="1">
                            <label class="custom-control-label"
                                   for="has_service_1">@lang('site::messages.yes')</label>
                        </div> 
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_service') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_service]"
                                   required
                                   @if(old('partnerPlusRequest.has_service',$partnerPlusRequest->has_service) == 0) checked
                                   @endif
                                   id="has_service_0"
                                   value="0">
                            <label class="custom-control-label"
                                   for="has_service_0">@lang('site::messages.no')</label>
                        </div>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                     <label class="control-label col pl-sm-0"
                               for="has_mounters">@lang('site::user.partner_plus_request.has_mounters')</label>
                        <div class="custom-control custom-radio custom-control-inline ml-sm-4">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_mounters') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_mounters]"
                                   required
                                   @if(old('partnerPlusRequest.has_mounters',$partnerPlusRequest->has_mounters) == 1) checked
                                   @endif
                                   id="has_mounters_1"
                                   value="1">
                            <label class="custom-control-label"
                                   for="has_mounters_1">@lang('site::messages.yes')</label>
                        </div> 
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_mounters') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_mounters]"
                                   required
                                   @if(old('partnerPlusRequest.has_mounters',$partnerPlusRequest->has_mounters) == 0) checked
                                   @endif
                                   id="has_mounters_0"
                                   value="0">
                            <label class="custom-control-label"
                                   for="has_mounters_0">@lang('site::messages.no')</label>
                        </div>
                </div>
            </div>
        </div>
        
        
    </form>    
        <div class="form-group">
            <div class="col text-right">
                <button form="form" type="submit"
                        class="btn btn-ms mb-1">
                    <i class="fa fa-check"></i>
                    <span>@lang('site::messages.save')</span>
                </button>
                <a href="{{ route('stand-orders.index') }}" class="btn btn-secondary mb-1">
                    <i class="fa fa-close"></i>
                    <span>@lang('site::messages.cancel')</span>
                </a>
            </div>
        </div>     
    </div>
</div>    
</div>    
@endsection


@push('scripts')
<script>

 try {
        window.addEventListener('load', function () {

            
                
                let users_search = $('#users_search'),
                    users = $('#users'),
                    selected = [];                        ;
                    
                    users_search.select2({
                        theme: "bootstrap4",
                        ajax: {
                            url: '/api/user-search',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    'filter[search_user]': params.term,
                                    'filter[has_distr_roles]': '1',
                                    
                                };
                            },
                            processResults: function (data, params) {
                                return {
                                    results: data.data,
                                };
                            }
                        },
                        
                        minimumInputLength: 3,
                        templateResult: function (user) {
                            if (user.loading) return "...";
                            let markup = user.name;
                            return markup;
                        },
                        templateSelection: function (user) {
                            if (user.id !== "") {
                                return user.name;
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });
                    users_search.on('select2:select', function (e) {
                        let id = $(this).find('option:selected').val();
                        if (!selected.includes(id)) {
                            users_search.removeClass('is-invalid');
                            selected.push(id);
                            $(users_search_sel).addClass('d-none');
                        } else {
                            users_search.addClass('is-invalid');
                        }
                    });

        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush
