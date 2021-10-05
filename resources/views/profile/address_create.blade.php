    <div class="row mb-3">
        
        <div class="col-md-2 required">

            <label class="control-label"
                   for="address_region_id_{{$row_num}}">@lang('site::address.region_id')</label>
            <select class="form-control"
                    name="addresses[{{$row_num}}][region_id]" form="user-form"
                    required
                    id="address_region_id_{{$row_num}}">
                <option value="">@lang('site::messages.select_from_list')</option>
                @foreach($regions as $region)
                    <option
                            @if(old('region_id') == $region->id) selected
                            @endif
                            value="{{ $region->id }}">{{ $region->name }}
                    </option>
                @endforeach
            </select>
            <span class="invalid-feedback">{{ $errors->first('region_id') }}</span>
        </div>
        <div class="col-md-2">
            <div class="form-row mb-0 mt-0 required">
                <div class="col">
                    <label class="control-label"
                           for="address_locality_{{$row_num}}">@lang('site::address.locality')</label>
                    <input type="text" form="user-form"
                           name="addresses[{{$row_num}}][locality]"
                           id="address_locality_{{$row_num}}"
                           required autocomplete="off"
                           data-filter-locality="0"
                           data-fieldid="{{$row_num}}"
                           data-field-name="address_locality_{{$row_num}}"
                           data-frombound="city"
                           data-tobound="settlement"
                           class="search_address form-control"
                           placeholder="@lang('site::address.placeholder.locality')"
                           value="{{ old('locality') }}">
                </div>            
            </div>            
            <div class="form-row mb-0 mt-0 required">
                <div class="col">       
                   <div class="ml-1 mt-1 search_wrapper" id="address_locality_{{$row_num}}_wrapper"></div>
                            
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-row mb-0 required">
                <div class="col">
                <label class="control-label"
                   for="address_street_{{$row_num}}">@lang('site::address.street')</label>
                   <input type="text" form="user-form"
                   name="addresses[{{$row_num}}][street]"
                   id="address_street_{{$row_num}}"
                   required autocomplete="off"
                   data-filter-locality="1"
                   data-fieldid="{{$row_num}}"
                   data-field-name="address_street_{{$row_num}}"
                   data-frombound="street"
                   data-tobound="house"
                   class="search_address form-control"
                   placeholder="@lang('site::address.placeholder.street')"
                   value="{{ old('addresses.$row_num.street') }}">
                </div>
            </div>

                            
            <div class="form-row mb-0 mt-0">
                <div class="col">       
                    <div class="ml-1 mt-1 search_wrapper" id="address_street_{{$row_num}}_wrapper"></div>
            
                </div>
            </div>
        </div>
        <div class="col-md-2 required">
                <label class="control-label"
                       for="address_building_{{$row_num}}">@lang('site::address.house')</label>
                <input type="text" form="user-form"
                       name="addresses[{{$row_num}}][building]"
                       id="address_building_{{$row_num}}"
                       class="search_address form-control"
                       value="{{ old('addresses.$row_num.building') }}"
                >
                           
        </div>
        <div class="col-md-1">
                            <label class="control-label"
                                   for="address_apartment_{{$row_num}}">@lang('site::address.apt')</label>
                            <input type="text" form="user-form"
                                   name="addresses[{{$row_num}}][apartment]"
                                   id="address_apartment_{{$row_num}}"
                                   class="search_address form-control{{ $errors->has('addresses.$row_num.apartment') ? ' is-invalid' : '' }}"
                                   value="{{ old('addresses.$row_num.apartment') }}"
                            >
                           
        </div>
        <div class="col-md-1 mt-auto pb-2">
                            <div class="custom-control custom-checkbox">
                                    <input type="checkbox" form="user-form"
                                           @if(old('addresses.$row_num.main')) checked @endif
                                           class="custom-control-input"
                                           id="address_main_{{$row_num}}"
                                           name="addresses[{{$row_num}}][main]">
                                    <label class="custom-control-label"
                                           for="address_main_{{$row_num}}">@lang('site::address.main')</label>
                                    
                                </div>
                           
        </div>
        <div class="col-md-1 text-right mt-auto">
                 <a href="javascript:void(0);" onclick="$(this).parent().parent().remove()"   class="btn btn-danger"> <i class="fa fa-close"></i></a>
                  
                    
        </div>
    </div>
