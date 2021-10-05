
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label" for="{{$field_name}}">{{$title}}</label>
                                    <input type="text"
                                           name="tender[{{$field_name}}][name]"
                                           id="{{$field_name}}" data-field-name="{{$field_name}}"
                                           autocomplete="off"
                                           class="customers form-control{{ $errors->has('tender.' .$field_name .'.name') ? ' is-invalid' : '' }} mb-0"
                                           required
                                           value="{{ old('tender.' .$field_name .'.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('tender.' .$field_name .'.name') }}</span>
                                </div>
                                <input type="hidden" id="{{$field_name}}_customer_id" name="{{$field_name}}[customer][id]" value="{{ old($field_name .'.customer.id') }}">
                               
                            </div>
                           
                            <div class="form-row mb-0">
                                <div class="col">
                                    <div class="search_wrapper" id="{{$field_name}}_wrapper"></div>
                                </div>
                            </div>
                            
                            
                            
                            <div class="form-row d-none">
                            
     
                                    <div class="col-4">
                                        <label class="control-label"
                                                           for="{{$field_name}}_search_address">Город<br/></label>
                                                <input class="mb-0 form-control search_address" type="text" name="{{$field_name}}_search_address" id="{{$field_name}}_search_address" value="" autocomplete="off"
                                                data-field-name="{{$field_name}}">
                                            
                                            
                                        <div class="ml-3 mt-5 search_wrapper" id="{{$field_name}}_search_address_wrapper"></div>
                                        <span class="text-success text-small">Начните ввод и выберите из списка</span>
                                    </div>
                                    
                                        <input type="hidden" id="{{$field_name}}_locality" name="{{$field_name}}[customer][locality]" value="{{ old($field_name .'.customer.locality') }}">
                                        <input type="hidden" id="{{$field_name}}_region_id" name="{{$field_name}}[customer][region_id]" value="{{ old($field_name .'.customer.region_id') }}">
                                    
                                    <div class="col-4">
                                            <label class="control-label" for="{{$field_name}}.customer.phone">@lang('site::admin.customer.phone')</label>
                                            <input 
                                                   type="tel"
                                                   name="{{$field_name}}[customer][phone]"
                                                   id="{{$field_name}}_customer_phone"
                                                   oninput="mask_phones()"
                                                   pattern="{{config('site.phone.pattern')}}"
                                                   maxlength="{{config('site.phone.maxlength')}}"
                                                   title="{{config('site.phone.format')}}"
                                                   data-mask="{{config('site.phone.mask')}}"
                                                   class="phone-mask form-control{{ $errors->has($field_name .'_customer.number') ? ' is-invalid' : (old($field_name .'_customer.phone') ? ' is-valid' : '') }}"
                                                   value="{{ old($field_name .'_customer.phone') }}">
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'_customer.phone') }}</span>
                                    </div>
                                     <div class="col-4">
                                            <label class="control-label" for="{{$field_name}}_customer_email">@lang('site::admin.customer.email')</label>
                                            <input type="text" name="{{$field_name}}[customer][email]"
                                                   id="{{$field_name}}_customer_email"
                                                   class="form-control{{ $errors->has($field_name .'_customer.email') ? ' is-invalid' : '' }}"
                                                   value="{{ old($field_name .'[customer][email]') }}">
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'_customer.email') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}_contact_name">Контактное лицо. Имя</label>
                                            <input type="text" name="{{$field_name}}[contact][name]"
                                                   id="{{$field_name}}_contact_name"
                                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                                    value="{{ old($field_name .'.contact.name') }}"
                                                   >
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'.contact.name') }}</span>
                                    </div>
                                    
                                    <input type="hidden" id="{{$field_name}}_contact_id" name="{{$field_name}}[contact][id]" value="{{ old($field_name .'.contact.id') }}">
                                 
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}_contact_position">Должность</label>
                                            <input type="text" name="{{$field_name}}[contact][position]"
                                                   id="{{$field_name}}_contact_position"
                                                   class="form-control{{ $errors->has($field_name .'.contact.position') ? ' is-invalid' : '' }}"
                                                    value="{{ old($field_name .'.contact.position') }}"
                                                   >
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'.contact.position') }}</span>
                                    </div>
                                    
                                    <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}_contact_phone">@lang('site::admin.customer.phone_contact')</label>
                                            <input 
                                                   type="tel"
                                                   name="{{$field_name}}[contact][phone]"
                                                   id="{{$field_name}}_contact_phone"
                                                   oninput="mask_phones()"
                                                   pattern="{{config('site.phone.pattern')}}"
                                                   maxlength="{{config('site.phone.maxlength')}}"
                                                   title="{{config('site.phone.format')}}"
                                                   data-mask="{{config('site.phone.mask')}}"
                                                   class="phone-mask form-control{{ $errors->has($field_name .'.contact.phone') ? ' is-invalid' : (old($field_name .'.contact.phone') ? ' is-valid' : '') }}"
                                                   value="{{ old($field_name .'.contact.phone') }}">
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'.contact.phone') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <label class="control-label" for="{{$field_name}}_contact_email">@lang('site::admin.customer.email_contact')</label>
                                            <input type="text" name="{{$field_name}}[contact][email]"
                                                   id="{{$field_name}}_contact_email"
                                                   class="form-control{{ $errors->has($field_name .'.contact.email') ? ' is-invalid' : '' }}"
                                                   value="{{ old($field_name .'[contact][email]') }}">
                                            <span class="invalid-feedback">{{ $errors->first($field_name .'.contact.email') }}</span>
                                    </div>
                                     <div class="col-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       @if(old($field_name .'.contact.lpr')) checked @endif
                                                       class="custom-control-input{{  $errors->has($field_name .'.contact.lpr') ? ' is-invalid' : '' }}"
                                                       id="{{$field_name}}_contact_lpr"
                                                       name="{{$field_name}}[contact][lpr]">
                                                <label class="custom-control-label"
                                                       for="{{$field_name}}_contact_lpr">@lang('site::admin.customer.lpr')</label>
                                                <span class="invalid-feedback">{{ $errors->first($field_name .'.contact.lpr') }}</span>
                                            </div>
                                    </div>
                                    
                                    
                                  
                            </div>  
                            
                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('fixed-height-450');$(this).toggleClass('d-none')" 
                            class="align-text-bottom text-left text-success">
                                    <b>Заполнить контакты</b>
                            </a>
                            
                            <hr />