@extends('layouts.app')
@section('title')@lang('site::messages.edit') @lang('site::ticket.add')@lang('site::messages.title_separator')@endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.tickets.index') }}">@lang('site::ticket.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
    </ol>
  
    @alert()@endalert

<form id="form" method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
@csrf
@method('PUT')
    <div class="card mb-5">
        <div class="card-body">
            <div class="form-row">
                    <div class="col-md-3">
                    <div class="form-group required" id="form-group-theme_id">
                            <label class="control-label" for="theme_id">@lang('site::ticket.type_id')</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('type_id') ? ' is-invalid' : '' }}"
                                        name="ticket[type_id]"
                                        id="type_id">
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                    @foreach($types as $type)
                                        <option  @if(old('ticket.type_id',$ticket->type_id) == $type->id )
                                                selected
                                                @endif
                                                value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                    
                                </select>
                                
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('type_id') }}</span>
                    </div>     
                    </div>
                <div class="col-md-4">
                        <div class="form-group required" id="form-group-theme_id">
                            <label class="control-label" for="theme_id">@lang('site::ticket.theme_id')</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('theme_id') ? ' is-invalid' : '' }}"
                                        name="ticket[theme_id]"
                                        id="theme_id">
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                    @foreach($themes as $theme)
                                        <option @if(old('ticket.theme_id',$ticket->theme_id) == $theme->id)
                                                selected
                                                @endif
                                                data-receiver='{{ $theme->default_receiver_id }}'
                                                data-text='{{ $theme->default_text }}'
                                                value="{{ $theme->id }}">
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                    
                                </select>
                                
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('theme_id') }}</span>
                        </div>  
                </div>  
                <div class="col-md-5">
                   <div class="form-row required" id="group-receiver_user">
                        <div class="col">

                            <div class="form-group required">
                                <label class="control-label" for="user_id">
                                    @lang('site::ticket.receiver_id')
                                </label>
                                <select id="list_receiver_id"
                                        name="ticket[receiver_id]"
                                        style="width:100%"
                                        class="form-control">
                                    <option></option>
                                    @foreach($users as $user)
                                        <option @if(old('ticket.receiver_id',$ticket->receiver_id) == $user->id)
                                                selected
                                                @endif
                                                value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('ticket.receiver_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row required d-none" id="group-receiver_group">
                        <div class="col">

                            <div class="form-group required">
                                <label class="control-label" for="list_receiver_group_id">
                                    @lang('site::retail_sale_report.group_id')
                                </label>
                                <select 
                                        id="list_receiver_group_id"
                                        name="ticket[receiver_group_id]"
                                        style="width:100%"
                                        class="form-control">
                                         <option></option>
                                    @foreach($groups as $group)
                                        <option @if(old('ticket.receiver_group_id',$ticket->receiver_group_id) == $group->id)
                                                selected
                                                @endif
                                                value="{{$group->id}}">
                                            {{$group->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('ticket.receiver_group_id') }}</span>
                            </div>
                        </div>
                    </div> 
                
                </div>  
            </div>  
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="consumer_name">@lang('site::ticket.consumer_name')</label>
                            <input type="text"
                                   name="ticket[consumer_name]"
                                   id="consumer_name" 
                                   class="form-control{{ $errors->has('ticket.address') ? ' is-invalid' : '' }}"
                                   value="{{ old('ticket.consumer_name',$ticket->consumer_name) }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_name') }}</span>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="consumer_company">@lang('site::ticket.consumer_company')</label>
                            <input type="text" autocomplete="off"
                                           name="ticket[consumer_company]"
                                           id="consumer_company"
                                           maxlength="100"
                                           class="form-control{{ $errors->has('ticket.consumer_company') ? ' is-invalid' : '' }}"
                                           placeholder=""
                                           value="{{ old('ticket.consumer_company',$ticket->consumer_company) }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_company') }}</span>
                            <input type="hidden" id="consumer_company_id" name="ticket[consumer_company_id]" 
                                    value="{{ old('ticket.consumer_company_id',$ticket->consumer_company_id) }}">
                        </div>
                        
                    </div>
                    <div class="form-row mb-0">
                        <div class="col">
                            <div class="search_wrapper" id="search_wrapper"></div>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="consumer_email">@lang('site::ticket.consumer_email')</label>
                            <input type="text"
                                   name="ticket[consumer_email]"
                                   id="consumer_email" 
                                   class="form-control{{ $errors->has('ticket.address') ? ' is-invalid' : '' }}"
                                   value="{{ old('ticket.consumer_email',$ticket->consumer_email) }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_email') }}</span>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="consumer_phone">@lang('site::ticket.consumer_phone')</label>
                            <input type="tel"
                                   name="ticket[consumer_phone]"
                                   id="consumer_phone" 
                                   oninput="mask_phones()"
                                   pattern="{{config('site.phone.pattern')}}"
                                   maxlength="{{config('site.phone.maxlength')}}"
                                   title="{{config('site.phone.format')}}"
                                   data-mask="{{config('site.phone.mask')}}"
                                   class="phone-mask form-control{{ $errors->has('ticket.address') ? ' is-invalid' : '' }}"
                                   value="{{ old('ticket.consumer_phone', $ticket->consumer_phone) }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_phone') }}</span>
                        </div>
                    </div>
                
                </div>
                {{ $errors->first('ticket.consumer_contacts') }}
            </div>
            
            
                    <div class="row required">        
                        <div class="col-6 required">
                            <label class="control-label"
                                               for="search_address">Город<br/><span class="text-success text-small">Начните ввод и выберите из списка</span></label>
                                    <input class="form-control" type="text" name="search_address" id="search_address" value="{{ old('ticket.search_address', $ticket->locality) }}" autocomplete="off">
                                
                                
                            <div class="ml-3 mt-5" id="search_address_wrapper"></div>
                        </div>
                        
                        <div class="col-6 required">

                                    <label class="control-label" for="region_id">Регион<br /><span class="text-success text-small">Установится автоматически после выбора города</span></label>
                                    <select class="form-control{{  $errors->has('ticket.region_id') ? ' is-invalid' : '' }}"
                                            name="ticket[region_id]"
                                            id="region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('ticket.region_id', $ticket->region_id) == $region->id)
                                                    selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('ticket.region_id') }}</span>
                        </div>
                       
                        
                    </div>
                     
                     <input type="hidden" id="locality" name="ticket[locality]" value="{{ old('ticket.search_address', $ticket->locality) }}">
                     
            
            
            
                <div class="form-row required">
                            <div class="col mb-3">
                                <label class="control-label" for="text">@lang('site::ticket.text')<span class="text-success d-none" id="ticket_text"> кратко</span></label>
                                <textarea required
                                      name="ticket[text]"
                                      id="text"
                                      class="form-control{{ $errors->has('ticket.text') ? ' is-invalid' : '' }}"
                                      >{{ old('ticket.text', $ticket->text) }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('ticket.text') }}</span>
                            </div>
                </div>
            <!-- -->    
                <div class="form-row required">
                            <div class="col mb-3">
                                <label class="control-label" for="solution">@lang('site::ticket.solution')</label>
                                <textarea 
                                      name="ticket[solution]"
                                      id="solution"
                                      class="form-control{{ $errors->has('ticket.solution') ? ' is-invalid' : '' }}"
                                      >{{ old('ticket.solution', $ticket->solution) }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('ticket.solution') }}</span>
                            </div>
                </div>
               <!-- <div class="form-group">
                            <label class="control-label" for="receiver_type_id">@lang('site::ticket.receiver_type')</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('ticket.receiver_type_id') ? ' is-invalid' : '' }}"
                                        name="ticket[receiver_type_id]"
                                        id="receiver_type_id">
                                        <option value="user">Пользователь</option>
                                        <option value="group">Группа</option>
                                    
                                    
                                </select>
                                
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('ticket.receiver_type_id') }}</span>
                </div>  
                -->
                
                

         <hr/>
         
         <div id="esb_claim" class="@if($ticket->type_id != 3) d-none @endif">
             
        <!-- -->    
            <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="claim_text">@lang('site::user.esb_claim.claim_text')</label>
                            <textarea 
                                  name="esbClaim[claim_text]"
                                  id="claim_text"
                                  class="form-control{{ $errors->has('esbClaim.claim_text') ? ' is-invalid' : '' }}"
                                  >{{ old('esbClaim.claim_text', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->claim_text: null) }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('esbClaim.claim_text') }}</span>
                        </div>
            </div>
            
    <!-- -->          
         <div class="form-row mb-3">
            <div class="col-sm-4">
                <label class="control-label" for="client">@lang('site::user.esb_user_product.serial')</label>
                <div class="input-group">
                <input type="text"
                       id="serial"
                       name="esbClaim[product_serial]"
                       class="serial form-control{{ $errors->has('esbClaim.product_serial') ? ' is-invalid' : '' }}"
                       value="{{ old('esbClaim.product_serial', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->product_serial : null) }}"
                       placeholder="@lang('site::user.esb_user_product.serial_placeholder')">
                 <div class="input-group-append">
                        <div class="input-group-text">
                            <i class="fa fa-search" id="search_serial_submit"></i>
                        </div>
                    </div>
                </div>      
                <span class="invalid-feedback">{{ $errors->first('esbClaim.product_serial') }}</span>
                <span class="text-danger d-none" id="serial_not_found">SN not found</span>
            </div>
                                
                            <!-- -->      
                                <div class="col-sm-4">
                                    <label class="control-label"
                                           for="equipment_id">@lang('site::user.esb_user_product.equipment_id')</label>
                                    <select class="form-control{{  $errors->has('equipment_id') ? ' is-invalid' : '' }}"
                                            name="esbClaim[equipment_id]"
                                            id="equipment_id">
                                        @if($equipments->count() == 0 || $equipments->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($equipments as $equipment)
                                            <option
                                                    @if(old('esbClaim.equipment_id', $ticket->esbClaims()->exists() ?  $ticket->esbClaims()->first()->product->equipment_id : null) == $equipment->id) selected
                                                    @endif
                                                    value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.equipment_id') }}</span>
                                </div>

                                <!-- -->  
                                <div class="col-sm-4">
                                    <label class="control-label"
                                           for="product_id">@lang('site::mounter.product_id')</label>
                                    <select class="form-control{{  $errors->has('esbClaim.product_id') ? ' is-invalid' : '' }}"
                                            name="esbClaim[product_id]"
                                            id="product_id">
                                        @if($ticket->esbClaims()->exists())
                                            <option value="{{$ticket->esbClaims()->first()->product_id}}">{{$ticket->esbClaims()->first()->product->name}}</option>
                                        @elseif($products->count() == 0)
                                            <option value="">@lang('site::mounter.help.select_equipment')</option>
                                        @else
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($products as $product)
                                            <option
                                                    @if(old('esbClaim.product_id') == $product->id) selected
                                                    @endif
                                                    value="{{ $product->id }}">{{ $product->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.product_id') }}</span>
                                </div> 
                            
                                <!-- -->  
                                <div class="col-sm-3">
                                    <label class="control-label"
                                           for="date_sale">@lang('site::user.esb_user_product.date_sale')</label>
                                    <div class="input-group date datetimepicker" id="datetimepicker_date_sale"
                                         data-target-input="nearest">
                                        <input type="text"
                                               name="esbClaim[date_sale]"
                                               id="date_sale"
                                               maxlength="10"
                                               
                                               placeholder="@lang('site::mounter.placeholder.mounter_at')"
                                               data-target="#datetimepicker_date_sale"
                                               data-toggle="datetimepicker"
                                               class="datetimepicker-input form-control{{ $errors->has('esbClaim.date_sale') ? ' is-invalid' : '' }}"
                                               value="{{ old('esbClaim.date_sale', ($ticket->esbClaims()->exists() && $ticket->esbClaims()->first()->date_sale) ? $ticket->esbClaims()->first()->date_sale->format('d.m.Y') : null )}}">
                                        <div class="input-group-append"
                                             data-target="#datetimepicker_date_sale"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.date_sale') }}</span>
                                </div>
                                
                                <!-- -->  
                                <div class="col-sm-3">
                                    <label class="control-label"
                                           for="date_launch">@lang('site::user.esb_user_product.date_launch')</label>
                                    <div class="input-group date datetimepicker" id="datetimepicker_date_launch"
                                         data-target-input="nearest">
                                        <input type="text"
                                               name="esbClaim[date_launch]"
                                               id="date_launch"
                                               maxlength="10"
                                               
                                               placeholder="@lang('site::mounter.placeholder.mounter_at')"
                                               data-target="#datetimepicker_date_launch"
                                               data-toggle="datetimepicker"
                                               class="datetimepicker-input form-control{{ $errors->has('esbClaim.date_launch') ? ' is-invalid' : '' }}"
                                               value="{{ old('esbClaim.date_launch', ($ticket->esbClaims()->exists() && $ticket->esbClaims()->first()->date_launch) ? $ticket->esbClaims()->first()->date_launch->format('d.m.Y') : null )}}">
                                        <div class="input-group-append"
                                             data-target="#datetimepicker_date_launch"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.date_launch') }}</span>
                                </div>
                                
                                <!-- -->  
                                <div class="col-sm-6">
                                    <label class="control-label" for="client">@lang('site::user.esb_user_product.dealer')</label>
                                    <input type="text"
                                           id="sale_name"
                                           name="esbClaim[sale_name]"
                                           class="form-control{{ $errors->has('esbClaim.sale_name') ? ' is-invalid' : '' }}"
                                           value="{{ old('esbClaim.sale_name', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->sale_name: null) }}"
                                           placeholder="@lang('site::user.esb_user_product.dealer_placeholder')">
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.sale_name') }}</span>
                                </div>
                                
                            <!-- -->    
                                <div class="col-sm-6">
                                    <label class="control-label" for="client">@lang('site::user.esb_claim.mounter_name')</label>
                                    <input type="text"
                                           id="mounter_name"
                                           name="esbClaim[mounter_name]"
                                           class="form-control{{ $errors->has('esbClaim.mounter_name') ? ' is-invalid' : '' }}"
                                           value="{{ old('esbClaim.mounter_name', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->mounter_name: null) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.mounter_name') }}</span>
                                </div>
                                
                            <!-- -->     
                                <div class="col-sm-6">
                                    <label class="control-label" for="client">@lang('site::user.esb_claim.launcher_name')</label>
                                    <input type="text"
                                           id="launcher_name"
                                           name="esbClaim[launcher_name]"
                                           class="form-control{{ $errors->has('esbClaim.launcher_name') ? ' is-invalid' : '' }}"
                                           value="{{ old('esbClaim.launcher_name', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->launcher_name: null) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.launcher_name') }}</span>
                                </div>
                                
                            </div>
                            
                        <!-- -->
                            <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label" for="launch_comment">@lang('site::user.esb_claim.launch_comment')</label>
                                            <textarea
                                                  name="esbClaim[launch_comment]"
                                                  id="launch_comment"
                                                  class="form-control{{ $errors->has('esbClaim.launch_comment') ? ' is-invalid' : '' }}"
                                                  >{{ old('esbClaim.launch_comment', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->launch_comment: null) }}</textarea>
                                            <span class="invalid-feedback">{{ $errors->first('esbClaim.launch_comment') }}</span>
                                        </div>
                            </div>
                        
                            
                        <!-- -->
                            <div class="form-row required">
                            <label class="control-label" for="heat_system">@lang('site::user.esb_claim.heat_system')</label>
                             </div>
                             <div class="form-row required">               
                                        <div class="col-4 mb-3">
                                        @lang('site::user.esb_claim.heat_system_p')
                                        </div>
                                        <div class="col-8 mb-3">
                                            <textarea 
                                                  name="esbClaim[heat_system]"
                                                  id="heat_system"
                                                  rows="5"
                                                  class="form-control{{ $errors->has('esbClaim.heat_system') ? ' is-invalid' : '' }}"
                                                  >{{ old('esbClaim.heat_system', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->heat_system: null) }}</textarea>
                                            <span class="invalid-feedback">{{ $errors->first('esbClaim.heat_system') }}</span>
                                        </div>
                            </div>
                            
                        <!-- -->
                            <div class="form-row required">
                            <label class="control-label" for="electric_system">@lang('site::user.esb_claim.electric_system')</label>
                             </div>
                             <div class="form-row required">               
                                        <div class="col-4 mb-3">
                                        @lang('site::user.esb_claim.electric_system_p')
                                        </div>
                                        <div class="col-8 mb-3">
                                            <textarea 
                                                  name="esbClaim[electric_system]"
                                                  id="electric_system"
                                                  rows="5"
                                                  class="form-control{{ $errors->has('esbClaim.electric_system') ? ' is-invalid' : '' }}"
                                                  >{{ old('esbClaim.electric_system', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->electric_system: null) }}</textarea>
                                            <span class="invalid-feedback">{{ $errors->first('esbClaim.electric_system') }}</span>
                                        </div>
                            </div>
                            <div class="form-row required">
                            <div class="col-4"><label class="control-label" for="smokestack">@lang('site::user.esb_claim.smokestack')</label>
                            </div>
                            <div class="col-8">
                            <select class="form-control{{  $errors->has('esbClaim.smokestack_type') ? ' is-invalid' : '' }}"
                                            name="esbClaim[smokestack_type]"
                                            id="smokestack_type">
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                            <option
                                                    @if(old('esbClaim.smokestack_type', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->smokestack_type: null) == 'closed') selected
                                                    @endif
                                                    value="closed">@lang('site::user.esb_claim.smokestack_type_closed')</option>
                                       
                                            <option
                                                    @if(old('esbClaim.smokestack_type', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->smokestack_type: null) == 'open') selected
                                                    @endif
                                                    value="open">@lang('site::user.esb_claim.smokestack_type_open')</option>
                                       
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esbClaim.smokestack_type') }}</span>
                             </div>
                             </div>
                             <div class="form-row required">               
                                        <div class="col-4 mb-3"><span id="smokestack_pc">
                                        @lang('site::user.esb_claim.smokestack_pc')</span>
                                        <span id="smokestack_po">
                                        @lang('site::user.esb_claim.smokestack_po')</span>
                                        </div>
                                        <div class="col-8 mb-3">
                                            <textarea 
                                                  name="esbClaim[smokestack]"
                                                  id="smokestack"
                                                  rows="5"
                                                  class="form-control{{ $errors->has('esbClaim.smokestack') ? ' is-invalid' : '' }}"
                                                  >{{ old('esbClaim.smokestack', $ticket->esbClaims()->exists() ? $ticket->esbClaims()->first()->smokestack: null) }}</textarea>
                                            <span class="invalid-feedback">{{ $errors->first('esbClaim.smokestack') }}</span>
                                        </div>
                            </div>
                            
         
         </div>
         
            <div class="form-row">
                <div class="col text-right">
                    <button name="_status_id" form="form" value="{{$ticket->status_id}}" type="submit" class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <button name="status_id" form="form" value="4" type="submit" class="btn btn-green mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::ticket.save_and_close')</span>
                    </button>
                    <button name="notify_receiver" form="form" value="1" type="submit" class="btn btn-primary mb-1">
                        <i class="fa fa-envelope"></i>
                        <span>@lang('site::ticket.save_and_mailing')</span>
                    </button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    
</form>
</div>
@endsection

@push('scripts')
<script>

var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
 try {
            window.addEventListener('load', function () {

                let receiver_type_id = $('#receiver_type_id');
                let receiver_id = $('#list_receiver_id');
                let theme_id = $('#theme_id');
                let type_id = $('#type_id');
                let equipment_id = $('#equipment_id');
                let smokestack_type = $('#smokestack_type');
                let product_id = $('#product_id');
                 
                receiver_type_id.on('change', function (e) {
                
                if(receiver_type_id.val()=='group') {
                        console.log(receiver_type_id.val());
                    $('#group-receiver_user').addClass('d-none');
                    $('#group-receiver_group').removeClass('d-none');
                    }
                if(receiver_type_id.val()=='user') {
                    $('#group-receiver_user').removeClass('d-none');
                    $('#group-receiver_group').addClass('d-none');
                    }
                }); 
                
                type_id.on('change', function (e) {
                    let field = $('#esb_claim');
                    if($(this).val()==3){
                    $('#ticket_text').removeClass('d-none');
                    $('#esb_claim').removeClass('d-none');
                        
                    }
                
                });
                
                theme_id.on('change', function (e) {
                    receiver_id.val($('#theme_id option:selected').data("receiver"));
                    receiver_id.trigger('change.select2');
                    $('#text').val($('#theme_id option:selected').data("text"));
                
                });
                
                smokestack_type.on('change', function (e) {
                    if($(this).val()=='closed'){
                        $('#smokestack_pc').removeClass('d-none');  
                        $('#smokestack_po').addClass('d-none');  
                    }else {
                        $('#smokestack_po').removeClass('d-none');  
                        $('#smokestack_pc').addClass('d-none');  
                    }
                 
                 
                });
                
                receiver_id.select2({
                    theme: "bootstrap4",
                    placeholder: '@lang('site::messages.select_from_list')',
                    selectOnClose: true,
                    
                });
                
                 $(document)
                
                .on('change', '.serial', (function(I){
                    
                            $(this).attr('autocomplete','off');
                            

                                input_initial_value = $(this).val();
                                $.get("/api/serial-product", { "serial":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    if(list.product_id!='empty') {
                                        document.getElementById('equipment_id').value = list.equipment_id;
                                        document.getElementById('product_id').innerHTML = '<option selected value="'+list.product_id+'">'+list.product_name+'</option>'
                                    }
                                    
                                }, 'html');
                            
                        
                })
                )
                .on('click', '#search_serial_submit', (function(I){
                                let product_serial = $('#serial');
                                $('#serial_not_found').addClass('d-none');   
                                input_initial_value = product_serial.val();
                                $.get("/api/serial-product", { "serial":product_serial.val()},function(data){
                                    var list = JSON.parse(data);
                                    if(list.product_id!='empty') {
                                        document.getElementById('equipment_id').value = list.equipment_id;
                                        document.getElementById('product_id').innerHTML = '<option selected value="'+list.product_id+'">'+list.product_name+'</option>'
                                    } else {
                                        $('#serial_not_found').removeClass('d-none');                                  
                                    }
                                    
                                }, 'html');
                            
                        
                })
                );
                
                $("#consumer_company").keyup(function(I){
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            if($(this).val().length>2){

                                input_initial_value = $(this).val();
                                $.get("/api/user-search", { "filter[search_user]":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    suggest_count = list.data.length;
                                    if(suggest_count > 0){
                                        $("#search_wrapper").html("").show();
                                        for(var i in list.data){
                                            if(list.data[i] != ''){
                                            
                                                $('#search_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list.data[i].id+'">'+' '+list.data[i].name+'</div>');
                                                $('#search_wrapper').find('#result_id-'+list.data[i].id).click(function() {
                                                    console.log(list.data[$(this)[0].getAttribute('data-key')]);
                                                    
                                                    $('#consumer_company').val(list.data[$(this)[0].getAttribute('data-key')].name);
                                                    $('#consumer_company_id').val(list.data[$(this)[0].getAttribute('data-key')].id);
                                                    
                                                    // прячем слой подсказки
                                                    $('#search_wrapper').fadeOut(2350).html('');
                                                });
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                        break;
                    }
                });

                
                $("#consumer_company").keydown(function(I){
                    switch(I.keyCode) {
                        case 27: // escape
                            $('#search_wrapper').hide();
                            return false;
                        break;
                        
                    }
                });

                 $('html').click(function(){
                    $('#search_wrapper').hide();
                }); 
                
                // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
                $('#consumer_company').click(function(event){
                    if(suggest_count)
                        $('#search_wrapper').show();
                    event.stopPropagation();
                });
                    
                function key_activate(n){
                    $('#search_wrapper div').eq(suggest_selected-1).removeClass('active');

                    if(n == 1 && suggest_selected < suggest_count){
                        suggest_selected++;
                    }else if(n == -1 && suggest_selected > 0){
                        suggest_selected--;
                    }

                    if( suggest_selected > 0){
                        $('#search_wrapper div').eq(suggest_selected-1).addClass('active');
                        $("#consumer_company").val( $('#search_wrapper div').eq(suggest_selected-1).text() );
                    } else {
                        $("#consumer_company").val( input_initial_value );
                    }
                }
                
                
                 $("#search_address").keyup(function(I){
                        switch(I.keyCode) {
                            // игнорируем нажатия 
                            case 13:  // enter
                            case 27:  // escape
                            case 38:  // стрелка вверх
                            case 40:  // стрелка вниз
                            break;

                            default:
                                if($(this).val().length>3){

                                    input_initial_value = $(this).val();
                                    $.get("/api/dadata/address", { "str":$(this).val(), "tobound":"settlement" },function(data){
                                        var list = JSON.parse(data);
                                        
                                        suggest_count = list.length;
                                        if(suggest_count > 0){
                                            $("#search_address_wrapper").html("").show();
                                            for(var i in list){
                                                if(list[i] != ''){
                                                    $('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                                    $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                        if(list[$(this)[0].getAttribute('data-key')].alldata.city) {
                                                            document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                        } else {
                                                                document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.area_with_type + ' ' + list[$(this)[0].getAttribute('data-key')].alldata.settlement;
                                                        }
                                                        
                                                        document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                                        if(list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code=='UA-40'){
                                                            document.getElementById('region_id').value='RU-SVP';
                                                        }
                                                        if(list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code=='UA-43'){
                                                            document.getElementById('region_id').value='RU-KRM';
                                                        }
                                                        
                                                        $('#search_address').val($(this).text());
                                                        
                                                        // прячем слой подсказки
                                                        $('#search_address_wrapper').fadeOut(2350).html('');
                                                    });
                                                }
                                            }
                                        }
                                    }, 'html');
                                }
                            break;
                        }
                    });

                    
                    $("#search_address").keydown(function(I){
                        switch(I.keyCode) {
                            case 27: // escape
                                $('#search_address_wrapper').hide();
                                return false;
                            break;
                            
                        }
                    });

                     $('html').click(function(){
                        $('#search_address_wrapper').hide();
                    }); 
                    
                    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
                    $('#search_address').click(function(event){
                        if(suggest_count)
                            $('#search_address_wrapper').show();
                        event.stopPropagation();
                    });
                
                
                
//---- Select product start ---- 
            equipment_id.on('change', function (e) {
                let product_id = document.getElementById('product_id');
                
                product_id.disabled = true;
                product_id.innerHTML = '<option value="">{{trans('site::messages.data_load')}}</option>';

                function checkStatus(response) {
                    if (response.status >= 200 && response.status < 300) {
                        return response;
                    } else {
                        let error = new Error(response.statusText);
                        error.response = response;
                        throw error
                    }
                }

                function parseJSON(response) {
                    return response.json()
                }

                function renderProductsList(data) {
                    product_id.disabled = false;
                    if (data.data.length > 0) {
                        let list = '<option value="">{{trans('site::messages.select_from_list')}}</option>';
                        data.data.forEach(function (product, index) {
                            list += `<option value="${product.id}">${product.name}</option>`;
                        });
                        product_id.innerHTML = list;
                    } else{
                        product_id.innerHTML = '<option value="">{{trans('site::mounter.help.select_equipment')}}</option>';
                    }
                }

                fetch('/api/products/mounter?filter[equipment_id]=' + event.target.value)
                    .then(checkStatus)
                    .then(parseJSON)
                    .then(renderProductsList)
                    .catch(error => console.error(error));
            });
//---- Select product stop ----  
                
                
                

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush