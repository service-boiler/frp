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
        <li class="breadcrumb-item active">@lang('site::messages.create')</li>
    </ol>
    <h1 class="header-title mb-4"><i
                class="fa fa-magic"></i> @lang('site::user.partner_plus_request.add_new')</h1>

    @alert()@endalert()
    
    
    <div class="card-body">
        <form id="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('partner-plus-requests.store') }}">
                    @csrf

    <input type="hidden" name="partnerPlusRequest[partner_id]" value="{{$user->id}}">

        <div class="form-group required">
            
            <label class="control-label" for="name_for_site">@lang('site::user.partner_plus_request.name_for_site')</label>
            <div class="input-group">
                        <input name="partnerPlusRequest[name_for_site]" id="name_for_site"
                        class="form-control{{ $errors->has('partnerPlusRequest.name_for_site') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.name_for_site',$user->name_for_site ? $user->name_for_site : $user->name)}}"
                        >
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>
                    </div>
                
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.name_for_site') }}</span>
            
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
                                @if(old('partnerPlusRequest.partner_contragent_id') == $contragent->id) selected
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
                                @if(old('partnerPlusRequest.partner_address_id') == $address->id) selected
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
                        value="{{old('partnerPlusRequest.found_year','2000')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.found_year') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="annual_turnover">@lang('site::user.partner_plus_request.annual_turnover')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[annual_turnover]"
                        id="annual_turnover" type="number"
                        max="999"
                        class="form-control{{ $errors->has('partnerPlusRequest.annual_turnover') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.annual_turnover')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.annual_turnover') }}</span>
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
                        value="{{old('partnerPlusRequest.sales_staff')}}"
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
                        value="{{old('partnerPlusRequest.shop_count')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.shop_count') }}</span>
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
                                   @if(old('partnerPlusRequest.has_service') == 1) checked
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
                                   @if(old('partnerPlusRequest.has_service') == 0) checked
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
                                   @if(old('partnerPlusRequest.has_mounters') == 1) checked
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
                                   @if(old('partnerPlusRequest.has_mounters') == 0) checked
                                   @endif
                                   id="has_mounters_0"
                                   value="0">
                            <label class="custom-control-label"
                                   for="has_mounters_0">@lang('site::messages.no')</label>
                        </div>
                </div>
            </div>
            
            <div class="col-3">
                <div class="form-group required">
                     <label class="control-label col pl-sm-0"
                               for="has_project">@lang('site::user.partner_plus_request.has_project')</label>
                        <div class="custom-control custom-radio custom-control-inline ml-sm-4">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_project') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_project]"
                                   required
                                   @if(old('partnerPlusRequest.has_project') == 1) checked
                                   @endif
                                   id="has_project_1"
                                   value="1">
                            <label class="custom-control-label"
                                   for="has_project_1">@lang('site::messages.yes')</label>
                        </div> 
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_project') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_project]"
                                   required
                                   @if(old('partnerPlusRequest.has_project') == 0) checked
                                   @endif
                                   id="has_project_0"
                                   value="0">
                            <label class="custom-control-label"
                                   for="has_project_0">@lang('site::messages.no')</label>
                        </div>
                </div>
            </div>
            
            
            <div class="col-3">
                <div class="form-group required">
                     <label class="control-label col pl-sm-0"
                               for="has_csc">@lang('site::user.partner_plus_request.has_csc')</label>
                        <div class="custom-control custom-radio custom-control-inline ml-sm-4">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_csc') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_csc]"
                                   required
                                   @if(old('partnerPlusRequest.has_csc') == 1) checked
                                   @endif
                                   id="has_csc_1"
                                   value="1">
                            <label class="custom-control-label"
                                   for="has_csc_1">@lang('site::messages.yes')</label>
                        </div> 
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input
                                {{$errors->has('partnerPlusRequest.has_csc') ? ' is-invalid' : ''}}"
                                   type="radio"
                                   name="partnerPlusRequest[has_csc]"
                                   required
                                   @if(old('partnerPlusRequest.has_csc') == 0) checked
                                   @endif
                                   id="has_csc_0"
                                   value="0">
                            <label class="custom-control-label"
                                   for="has_csc_0">@lang('site::messages.no')</label>
                        </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label"
                           for="servisantes_staff">@lang('site::user.partner_plus_request.servisantes_staff')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[servisantes_staff]"
                        id="servisantes_staff" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.servisantes_staff') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.servisantes_staff')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.servisantes_staff') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label"
                           for="servisantes_free">@lang('site::user.partner_plus_request.servisantes_free')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[servisantes_free]"
                        id="servisantes_free" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.servisantes_free') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.servisantes_free')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.servisantes_free') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label"
                           for="mounters_staff">@lang('site::user.partner_plus_request.mounters_staff')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[mounters_staff]"
                        id="mounters_staff" type="number"
                        max="999"
                        class="form-control{{ $errors->has('partnerPlusRequest.mounters_staff') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.mounters_staff')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.mounters_staff') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label"
                           for="mounters_free">@lang('site::user.partner_plus_request.mounters_free')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[mounters_free]"
                        id="mounters_free" type="number"
                        max="999"
                        class="form-control{{ $errors->has('partnerPlusRequest.mounters_free') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.mounters_free')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.mounters_free') }}</span>
                </div>
            </div>
        </div>
        
<!------->        
        
        
        <div class="row">
            <div class="col-3">
                <div class="form-group required">
                    <label class="control-label"
                           for="warehouse_area">@lang('site::user.partner_plus_request.warehouse_area')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[warehouse_area]"
                        id="warehouse_area" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.warehouse_area') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.warehouse_area')}}"
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
                        value="{{old('partnerPlusRequest.warehouse_count')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.warehouse_count') }}</span>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label class="control-label"
                           for="warehouse_csc_summ">@lang('site::user.partner_plus_request.warehouse_csc_summ')</label>
                    <div class="input-group">
                        <input name="partnerPlusRequest[warehouse_csc_summ]"
                        id="warehouse_csc_summ" type="number"
                        class="form-control{{ $errors->has('partnerPlusRequest.warehouse_csc_summ') ? ' is-invalid' : '' }}"
                        value="{{old('partnerPlusRequest.warehouse_csc_summ')}}"
                        >
                    </div>
                    <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.warehouse_csc_summ') }}</span>
                </div>
            </div>
            
        </div>
        
        
<!------->        
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group required">
                    
                    <label class="control-label" for="benefit_name">@lang('site::user.partner_plus_request.benefit_name')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[benefit_name]" id="benefit_name"
                                class="form-control{{ $errors->has('partnerPlusRequest.benefit_name') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.benefit_name')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.benefit_name') }}</span>
                    
                </div>      
            </div>      
            <div class="col-sm-6">    
                <div class="form-group required">
                    
                    <label class="control-label" for="director_name">@lang('site::user.partner_plus_request.director_name')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[director_name]" id="director_name"
                                class="form-control{{ $errors->has('partnerPlusRequest.director_name') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.director_name')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.director_name') }}</span>
                    
                </div> 
            </div> 
        </div> 
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <label class="control-label" for="ferroli_sale_years">@lang('site::user.partner_plus_request.ferroli_sale_years')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[ferroli_sale_years]" id="ferroli_sale_years"
                                class="form-control{{ $errors->has('partnerPlusRequest.ferroli_sale_years') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.ferroli_sale_years')}}"
                                placeholder="@lang('site::user.partner_plus_request.ferroli_sale_years_ph')"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.ferroli_sale_years') }}</span>
                    
                </div> 
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <label class="control-label" for="ferroli_annual_turnover">@lang('site::user.partner_plus_request.ferroli_annual_turnover')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[ferroli_annual_turnover]" id="ferroli_annual_turnover"
                                class="form-control{{ $errors->has('partnerPlusRequest.ferroli_annual_turnover') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.ferroli_annual_turnover')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-line-chart"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.ferroli_annual_turnover') }}</span>
                    
                </div> 
            </div>
        </div>
        <h5 class="mb-1 mt-2">@lang('site::user.partner_plus_request.cause_h')</h5>
        
        <div class="row mb-0">
            <div class="col-12">
                <div class="form-group">
                    <div class="input-group">
                                <input name="partnerPlusRequest[cause]" id="cause"
                                class="form-control{{ $errors->has('partnerPlusRequest.cause') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.cause')}}"
                                
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-flag"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.cause') }}</span>
                    
                </div> 
            </div>
            
        </div> 
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <label class="control-label" for="sales_plan">@lang('site::user.partner_plus_request.sales_plan')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[sales_plan]" id="sales_plan"
                                class="form-control{{ $errors->has('partnerPlusRequest.sales_plan') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.sales_plan')}}"
                                placeholder="@lang('site::user.partner_plus_request.sales_plan_ph')"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-eur"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.sales_plan') }}</span>
                    
                </div> 
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    
                    <label class="control-label" for="base_assort">@lang('site::user.partner_plus_request.base_assort')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[base_assort]" id="base_assort"
                                class="form-control{{ $errors->has('partnerPlusRequest.base_assort') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.base_assort')}}"
                                placeholder="@lang('site::user.partner_plus_request.base_assort_ph')"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-reorder"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.base_assort') }}</span>
                    
                </div> 
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    
                    <label class="control-label" for="base_assort_any">@lang('site::user.partner_plus_request.base_assort_any')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[base_assort_any]" id="base_assort_any"
                                class="form-control{{ $errors->has('partnerPlusRequest.base_assort_any') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.base_assort_any')}}"
                                placeholder="@lang('site::user.partner_plus_request.base_assort_any_ph')"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-reorder"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.base_assort_any') }}</span>
                    
                </div> 
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    
                    <label class="control-label" for="base_assort_any_annual">@lang('site::user.partner_plus_request.base_assort_any_annual')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[base_assort_any_annual]" id="base_assort_any_annual"
                                class="form-control{{ $errors->has('partnerPlusRequest.base_assort_any_annual') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.base_assort_any_annual')}}"
                                placeholder="@lang('site::user.partner_plus_request.base_assort_any_annual_ph')"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-eur"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.base_assort_any_annual') }}</span>
                    
                </div> 
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    
                    <label class="control-label" for="direct_contracts">@lang('site::user.partner_plus_request.direct_contracts')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[direct_contracts]" id="direct_contracts"
                                class="form-control{{ $errors->has('partnerPlusRequest.direct_contracts') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.direct_contracts')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-reorder"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.direct_contracts') }}</span>
                    
                </div> 
            </div>
            
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    
                    <label class="control-label" for="asc_csc">@lang('site::user.partner_plus_request.asc_csc')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[asc_csc]" id="asc_csc"
                                class="form-control{{ $errors->has('partnerPlusRequest.asc_csc') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.asc_csc')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-reorder"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.asc_csc') }}</span>
                    
                </div> 
            </div>
            
        </div>
        
        <h5 class="mb-0 mt-2">@lang('site::user.partner_plus_request.partner_manager_h')</h5>
        
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group required">
                    
                    <label class="control-label" for="partner_manager_name">@lang('site::user.partner_plus_request.partner_manager_name')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[partner_manager_name]" id="partner_manager_name"
                                class="form-control{{ $errors->has('partnerPlusRequest.partner_manager_name') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.partner_manager_name')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.partner_manager_name') }}</span>
                    
                </div> 
            </div> 
            <div class="col-sm-3">
                <div class="form-group required">
                    
                    <label class="control-label" for="partner_manager_phone">@lang('site::user.partner_plus_request.partner_manager_phone')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[partner_manager_phone]" id="partner_manager_phone"
                                class="form-control{{ $errors->has('partnerPlusRequest.partner_manager_phone') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.partner_manager_phone')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.partner_manager_phone') }}</span>
                    
                </div> 
            </div> 
            <div class="col-sm-3">
                <div class="form-group required">
                    
                    <label class="control-label" for="partner_manager_email">@lang('site::user.partner_plus_request.partner_manager_email')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[partner_manager_email]" id="partner_manager_email"
                                class="form-control{{ $errors->has('partnerPlusRequest.partner_manager_email') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.partner_manager_email')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-envelope-o"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.partner_manager_email') }}</span>
                    
                </div> 
            </div> 
            <div class="col-sm-3">
                <div class="form-group required">
                    
                    <label class="control-label" for="partner_manager_position">@lang('site::user.partner_plus_request.partner_manager_position')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[partner_manager_position]" id="partner_manager_position"
                                class="form-control{{ $errors->has('partnerPlusRequest.partner_manager_position') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.partner_manager_position')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user-secret"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.partner_manager_position') }}</span>
                    
                </div> 
            </div> 
        </div> 
        
       
                <div class="form-group required">
                    
                    <label class="control-label" for="distributor_id">Дистрибьютор</label>
                    @if(auth()->user()->hasRole('distr') || auth()->user()->hasRole('gendistr'))
                        <input type="hidden" name="partnerPlusRequest[distributor_id]" value="{{auth()->user()->id}}">
                        <h5 class="ml-3">{{auth()->user()->name_for_site ? auth()->user()->name_for_site : auth()->user()->name}}</h5>
                    @else
                        <span id="usersHelp" class="d-block form-text text-success">
                            Введите название или ИНН и выберите вариант из выпадающего списка.
                        </span>
                        <fieldset id="users-search-fieldset"
                                  style="display: block; padding-left: 5px;">
                            <div class="form-row">
                                <select class="form-control" id="users_search"  name="partnerPlusRequest[distributor_id]">
                                @if(old('partnerPlusRequest.distributor_id')) <option selected value="{{old('partnerPlusRequest.distributor_id')}}"> {{old('partnerPlusRequest.distributor_id')}}</option>@endif
                                    <option></option>
                                </select>
                                
                            </div>
                        </fieldset>
                    @endif
                   
                </div>
            
                <div class="form-group required">
                    
                    <label class="control-label" for="distr_manager">@lang('site::user.partner_plus_request.distr_manager')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[distr_manager]" id="distr_manager"
                                class="form-control{{ $errors->has('partnerPlusRequest.distr_manager') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.distr_manager')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user-secret"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.distr_manager') }}</span>
                    
                </div> 
            
        <h5 class="mb-0 mt-4">@lang('site::user.partner_plus_request.agreement_h')</h5>
                <div class="form-group required">
                    
                    <label class="control-label" for="distr_manager">@lang('site::user.partner_plus_request.distr_manager')</label>
                    <div class="input-group">
                                <input name="partnerPlusRequest[distr_manager]" id="distr_manager"
                                class="form-control{{ $errors->has('partnerPlusRequest.distr_manager') ? ' is-invalid' : '' }}"
                                value="{{old('partnerPlusRequest.distr_manager')}}"
                                >
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-user-secret"></i>
                                    </div>
                                </div>
                            </div>
                        
                            <span class="invalid-feedback">{{ $errors->first('partnerPlusRequest.distr_manager') }}</span>
                    
                </div> 
            
  
        <div class="row no-gutters">
            
        <div class="form-group d-flex col flex-column"><label class="control-label"
                   for="message_text">@lang('site::messages.comment')</label>
                                    <div class="flex-grow-1 position-relative">
                                        <div class="form-group">
                                            <input type="hidden" name="message[receiver_id]"
                                                   value="1">
                                            <textarea name="message[text]"
                                                      id="message_text"
                                                      rows="4"
                                                      class="form-control{{  $errors->has('message.text') ? ' is-invalid' : '' }}"></textarea>
                                            <span class="invalid-feedback">{{ $errors->first('message.text') }}</span>
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
                <a href="{{ route('partner-plus-requests.index') }}" class="btn btn-secondary mb-1">
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
                        users = $('#users')
                        ;
                    
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
