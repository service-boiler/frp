                            <div>
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label" for="{{$field_name}}-{{$row_num}}">{{$title ? $title : "Название"}}</label>
                                    @if($row_num==0)
                                    <a href="javascript:void(0);"  data-field-name="{{$field_name}}" class="align-text-bottom text-left text-success add-field"> <i class="fa fa-plus"></i></a>
                                    @else
                                    <a href="javascript:void(0);" onclick="$(this).parent().parent().parent().remove()"   class="align-text-bottom text-left text-danger"> <i class="fa fa-close"></i></a>
                                    @endif
                                    <input type="text"
                                           name="customers[{{$field_name}}][{{$row_num}}][customer][name]"
                                           id="{{$field_name}}-{{$row_num}}" data-field-name="{{$field_name}}-{{$row_num}}"
                                           class="customers form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.name') ? ' is-invalid' : '' }} mb-0"
                                           required
                                            value="{{ !empty($customer->name) ? old('customers.' .$field_name .'.' .$row_num .'.name', $customer->name) : old('customers.' .$field_name .'.' .$row_num .'.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.name') }}</span>
                                </div>
                                <input type="hidden" id="{{$field_name}}-{{$row_num}}_customer_id" name="customers[{{$field_name}}][{{$row_num}}][customer][id]" 
                                    value="{{ !empty($customer->id) ? old('customers .' .$field_name .'.' .$row_num .'.customer.id', $customer->id) : old('customers .' .$field_name .'.' .$row_num .'.customer.id') }}">
                               
                            </div>
                           
                            <div class="form-row mb-0">
                                <div class="col">
                                    <div class="search_wrapper" id="{{$field_name}}-{{$row_num}}_wrapper"></div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="form-row d-none">
                            
     
                                    <div class="col-4">
                                        <label class="control-label"
                                                           for="{{$field_name}}-{{$row_num}}_search_address">Город<br/></label>
                                                <input class="mb-0 form-control search_address" type="text" name="customers[{{$field_name}}][{{$row_num}}][search_address]" id="{{$field_name}}-{{$row_num}}_search_address" 
                                                value="{{ !empty($customer->locality) ? old('customers .' .$field_name .'.' .$row_num .'.customer.locality', $customer->locality) : old('customers .' .$field_name .'.' .$row_num .'.customer.locality') }}" 
                                                data-field-name="{{$field_name}}-{{$row_num}}">
                                            
                                            
                                        <div class="ml-3 mt-5 search_wrapper" id="{{$field_name}}-{{$row_num}}_search_address_wrapper"></div>
                                        <span class="text-success text-small">Начните ввод и выберите из списка</span>
                                    </div>
                                    
                                        <input type="hidden" id="{{$field_name}}-{{$row_num}}_locality" name="customers[{{$field_name}}][{{$row_num}}][customer][locality]" value="{{ !empty($customer->locality) ? old('customers .' .$field_name .'.' .$row_num .'.customer.locality', $customer->locality) : old('customers .' .$field_name .'.' .$row_num .'.customer.locality') }}">
                                        <input type="hidden" id="{{$field_name}}-{{$row_num}}_region_id" name="customers[{{$field_name}}][{{$row_num}}][customer][region_id]" value="{{ !empty($customer->region_id) ? old('customers .' .$field_name .'.' .$row_num .'.customer.region_id', $customer->region_id) : old('customers .' .$field_name .'.' .$row_num .'.customer.region_id') }}">
                                    
                                    <div class="col-4">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_customer_phone">@lang('site::admin.customer.phone')</label>
                                            <input 
                                                   type="tel"
                                                   name="customers[{{$field_name}}][{{$row_num}}][customer][phone]"
                                                   id="{{$field_name}}-{{$row_num}}_customer_phone"
                                                   oninput="mask_phones()"
                                                   pattern="{{config('site.phone.pattern')}}"
                                                   maxlength="{{config('site.phone.maxlength')}}"
                                                   title="{{config('site.phone.format')}}"
                                                   data-mask="{{config('site.phone.mask')}}"
                                                   class="phone-mask form-control{{ $errors->has('customers.' .$field_name .'_customer.number') ? ' is-invalid' : (old($field_name .'_customer.phone') ? ' is-valid' : '') }}"
                                                   value="{{ !empty($customer->phone) ? old('customers .' .$field_name .'.' .$row_num .'_customer.phone', $customer->phone) : old('customers .' .$field_name .'.' .$row_num .'_customer.phone') }}">
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'_customer.phone') }}</span>
                                    </div>
                                     <div class="col-4">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_customer_email">@lang('site::admin.customer.email')</label>
                                            <input type="text" name="customers[{{$field_name}}][{{$row_num}}][customer][email]"
                                                   id="{{$field_name}}-{{$row_num}}_customer_email"
                                                   class="form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.customer.email') ? ' is-invalid' : '' }}"
                                                   value="{{ !empty($customer->email) ? old('customers .' .$field_name .'.' .$row_num .'.customer.email', $customer->email) : old('customers .' .$field_name .'.' .$row_num .'.customer.email') }}">
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.customer.email') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_contact_name">Контактное лицо. Имя</label>
                                            <input type="text" name="customers[{{$field_name}}][{{$row_num}}][contact][name]"
                                                   id="{{$field_name}}-{{$row_num}}_contact_name" data-form-id="{{$field_name}}-{{$row_num}}"
                                                   class="customer-contact-name form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.contact.name') ? ' is-invalid' : '' }}"
                                                    value="{{ !empty($contact->name) ? old('customers .' .$field_name .'.' .$row_num  .'.contact.name',$contact->name) : old('customers .' .$field_name .'.' .$row_num  .'.contact.name') }}"
                                                   >
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.contact.name') }}</span>
                                    </div>
                                    
                                    <input type="hidden" id="{{$field_name}}-{{$row_num}}_contact_id" name="customers[{{$field_name}}][{{$row_num}}][contact][id]" value="{{ !empty($contact->id) ? old('customers .' .$field_name .'.' .$row_num .'.contact.id', $contact->id) : old('customers .' .$field_name .'.' .$row_num .'.contact.id') }}">
                                 
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_contact_position">Должность</label>
                                            <input type="text" name="customers[{{$field_name}}][{{$row_num}}][contact][position]"
                                                   id="{{$field_name}}-{{$row_num}}_contact_position"
                                                   class="form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.contact.position') ? ' is-invalid' : '' }}"
                                                    value="{{ !empty($contact->position) ? old('customers .' .$field_name .'.' .$row_num .'.contact.position',$contact->position) : old('customers .' .$field_name .'.' .$row_num .'.contact.position') }}"
                                                   >
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.contact.position') }}</span>
                                    </div>
                                    
                                    <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_contact_phone">@lang('site::admin.customer.phone_contact')</label>
                                            <input 
                                                   type="tel"
                                                   name="customers[{{$field_name}}][{{$row_num}}][contact][phone]"
                                                   id="{{$field_name}}-{{$row_num}}_contact_phone"
                                                   oninput="mask_phones()"
                                                   pattern="{{config('site.phone.pattern')}}"
                                                   maxlength="{{config('site.phone.maxlength')}}"
                                                   title="{{config('site.phone.format')}}"
                                                   data-mask="{{config('site.phone.mask')}}"
                                                   class="phone-mask form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.contact.phone') ? ' is-invalid' : (old($field_name .'.contact.phone') ? ' is-valid' : '') }}"
                                                   value="{{ !empty($contact->phone) ? old('customers .' .$field_name .'.' .$row_num .'.contact.phone',$contact->phone) : old('customers .' .$field_name .'.' .$row_num .'.contact.phone') }}">
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.contact.phone') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}-{{$row_num}}_contact_email">@lang('site::admin.customer.email_contact')</label>
                                            <input type="text" name="customers[{{$field_name}}][{{$row_num}}][contact][email]"
                                                   id="{{$field_name}}-{{$row_num}}_contact_email"
                                                   class="form-control{{ $errors->has('customers.' .$field_name .'.' .$row_num .'.contact.email') ? ' is-invalid' : '' }}"
                                                   value="{{ !empty($contact->email) ? old('customers .' .$field_name .'.' .$row_num .'.contact.email',$contact->email) : old('customers .' .$field_name .'.' .$row_num .'.contact.email') }}">
                                            <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.contact.email') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       @if((!empty($contact->lpr) && $contact->lpr) || old('customers .' .$field_name .'.' .$row_num .'.contact.lpr')) checked @endif
                                                       class="custom-control-input{{  $errors->has('customers.' .$field_name .'.' .$row_num .'.contact.lpr') ? ' is-invalid' : '' }}"
                                                       id="{{$field_name}}-{{$row_num}}_contact_lpr"
                                                       name="customers[{{$field_name}}][{{$row_num}}][contact][lpr]">
                                                <label class="custom-control-label"
                                                       for="{{$field_name}}-{{$row_num}}_contact_lpr">@lang('site::admin.customer.lpr')</label>
                                                <span class="invalid-feedback">{{ $errors->first('customers.' .$field_name .'.' .$row_num .'.contact.lpr') }}</span>
                                            </div>
                                    </div>
                                    
                                    
                                  
                            </div>  
                            
                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('fixed-height-450');$(this).toggleClass('d-none')" 
                            class="align-text-bottom text-left text-success">
                                    <b>Заполнить контакты</b>
                            </a>
                            
                            <hr />
                            </div>