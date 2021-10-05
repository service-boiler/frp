@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-requests.index') }}">@lang('site::user.esb_request.esb_requests')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_request.new')</h1>

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
                <form id="form"
                      method="POST"
                      action="{{ route('esb-requests.store') }}">
                    @csrf
                    <div class="card mb-2">
                        <div class="card-body">
                        
                       
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-row required"> <label class="control-label"
                                               for="type_id">@lang('site::user.esb_request.type_select')</label>
                                    <select class="form-control{{  $errors->has('type_id') ? ' is-invalid' : '' }}"
                                                name="type_id"
                                                id="type_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                            
                                        @foreach($request_types as $type_id)
                                            <option value="{{$type_id->id}}">{{$type_id->name}}</option>
                                        @endforeach
                                   </select>
                                        <span class="invalid-feedback">{{ $errors->first('type_id') }}</span>
                                   
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-row mb-2">
                                        <label class="control-label"
                                               for="date_planned">@lang('site::user.esb_request.date_planned')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_planned"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="date_planned"
                                                   id="date_planned"
                                                   maxlength="10"
                                                   
                                                   placeholder="@lang('site::user.esb_request.date_planned_placeholder')"
                                                   data-target="#datetimepicker_date_planned"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('date_planned') ? ' is-invalid' : '' }}"
                                                   value="{{ old('date_planned') }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_planned"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('date_planned') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="form-row required">
                                        <label class="control-label"
                                               for="phone">@lang('site::user.phone_second')</label>
                                        <input required
                                               type="tel"
                                               oninput="mask_phones()"
                                               id="phone"
                                               name="phone"
                                               class="phone-mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               pattern="{{config('site.phone.pattern')}}"
                                               maxlength="{{config('site.phone.maxlength')}}"
                                               title="{{config('site.phone.format')}}"
                                               data-mask="{{config('site.phone.mask')}}"
                                               value="{{ old('phone',$user->phone) }}"
                                               placeholder="@lang('site::mounter.placeholder.phone')">
                                        <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-3">

                                    <div class="form-row required">
                                        <label class="control-label"
                                               for="contact_name">@lang('site::user.contact')</label>
                                        <input required
                                               type="text"
                                               id="contact_name"
                                               name="contact_name"
                                               class="form-control{{ $errors->has('contact_name') ? ' is-invalid' : '' }}"
                                               value="{{ old('contact_name',($user->first_name .' ' .$user->middle_name)) }}"
                                               >
                                        <span class="invalid-feedback">{{ $errors->first('contact_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($user->esbProducts()->exists())
                            <div class="form-row mb-3">
                                <label class="control-label"
                                       for="user_product_id">@lang('site::user.esb_request.product_id')</label>
                                <select class="form-control{{  $errors->has('.user_product_id') ? ' is-invalid' : '' }}"
                                        name="user_product_id"
                                        id="user_product_id">
                                    @foreach($user->esbProducts as $product)
                                        <option
                                                @if(old('user_product_id') == $product->id) selected
                                                @endif
                                                value="{{ $product->id }}">{{ $product->product ? $product->product->name : substr($product->product_no_cat,0,20)}}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('user_product_id') }}</span>
                                <span class="text-success mt-1">Если Вы еще не зарегистрировали Ваше оборудование, Вы можете написать модель и другую информацию в поле ниже</span>
                            </div>
                            @endif

                            <div class="form-row">
                                
                                    <div class="col mb-3">
                                        <label class="control-label" for="comments">@lang('site::user.esb_request.comments_equipment')</label>
                                        <textarea
                                              name="comments"
                                              id="comments"
                                              class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              >{{ old('comments') }}</textarea>
                                        <span class="invalid-feedback">{{ $errors->first('comments') }}</span>
                                    </div>
                            </div>
                        
                        
                            @if($multiple)
                                <div class="form-row required mb-2">
                                    @if(count($services)==0) 
										<div class="col-12"><h5>@lang('site::user.esb_request.no_sc')</h5> </div>
									@else 
										<div class="col-12"><h5>@lang('site::user.esb_request.select_sc') ({{$user->region->name}})</h5></div>
									
										<table class="table bg-white table-hover">
											<tbody>
                                            @foreach($services->all() as $service)
                                                <tr>
                                                <td>
                                                        <input type="checkbox"
                                                                   name="recipient[]"
                                                                   value="{{ $service->id }}"
                                                                   
                                                                   form="cart-form"
                                                                   
                                                                   class="inline-block form-check-input ml-1" id="recipient-{{ $service->id }}">
                                                            <h5 class="inline-block mt-1 text-sm ml-4">{{$service->name_for_site ? $service->name_for_site : $service->name}}</h5>
                                                            
                                                       
                                                @foreach($service->addresses as $key => $address)
                                                    @if($key == 2)
                                                        <div class="d-none">
                                                    @endif
                                                        <div class="form-check mb-2">
                                                            
                                                             <b>{{ $address->locality }}</b>, 
                                                                   
                                                                   <a href="javascript:void(0);" 
                                                                    data-form-action="{{ route('api.service-address', ['address' => $address->id]) }}" data-label="{{$address->name}}" 
                                                                    class="dynamic-modal-form-card mr-3">{{ $address->name }} &nbsp;&nbsp; <i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                                                   {{ $address->street }}, {{ $address->building ? $address->building :'' }}
                                                                   
                                                            
                                                        </div>
                                                    
                                                @endforeach
                                                    @if($service->addresses->count() > 2)
                                                        </div>
                                                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).addClass('d-none')" 
                                                            class="align-text-bottom text-left">
                                                            <b>Еще адреса</b></a>
                                                            @endif
                        
                                                
                                                    </td>
                                                </tr>
                                            @endforeach
											</tbody>
										</table>
                                            
                                            
                                    @endif
                                </div>
                            @else
                                <h5 class="card-title">Сервисный центр: {{$checkedService->name}}</h5>
                                <input type="hidden" name="recipient[]" value="{{$checkedService->id}}">
                                <a href="{{route('service-centers').'?filter[region_id]='.$user->region_id .'#sc_list'}}">
                                    <span class="text-success">Изменить Ваш основной сервисный центр <i class="fa fa-external-link"></i></span>
                                </a>
                            @endif


                            <div class="form-row">
                                <div class="col text-right">
                                    <button form="form" type="submit"
                                            class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-secondary mb-1">
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
