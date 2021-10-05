@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
           
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>

        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="user-form" method="POST"
                      action="{{ route('update_profile') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row required">
                        <div class="col-sm-4 mb-3">
                            <label class="control-label"
                                   for="last_name">@lang('site::user.last_name')</label>
                            <input type="text"
                                   name="last_name"
                                   id="last_name" required
                                   class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}"
                                   value="{{ old('last_name', $user->last_name) }}">
                            <span class="invalid-feedback">{{ $errors->first('last_name') }}</span>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="control-label"
                                   for="first_name">@lang('site::user.first_name')</label>
                            <input type="text"
                                   name="first_name"
                                   id="first_name" required
                                   class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}"
                                  
                                   value="{{ old('first_name', $user->first_name) }}">
                            <span class="invalid-feedback">{{ $errors->first('first_name') }}</span>
                        </div>
                        <div class="col-sm-4 mb-3">
                            <label class="control-label"
                                   for="middle_name">@lang('site::user.middle_name')</label>
                            <input type="text"
                                   name="middle_name"
                                   id="middle_name" required
                                   class="form-control{{ $errors->has('middle_name') ? ' is-invalid' : '' }}"
                                  
                                   value="{{ old('middle_name', $user->middle_name) }}">
                            <span class="invalid-feedback">{{ $errors->first('middle_name') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="email">@lang('site::user.email')</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.email')"
                                   value="{{ old('email', $user->email) }}">
                            <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="phone">@lang('site::user.phone')</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.phone')"
                                   value="{{ old('phone', $user->phone) }}">
                            <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col required">

                            <label class="control-label" for="region_id">Регион</label>
                            <select class="form-control{{  $errors->has('region_id') ? ' is-invalid' : '' }}"
                                    name="region_id"
                                    required 
                                    id="region_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($regions as $region)
                                    <option
                                            @if(old('region_id', $user->region_id) == $region->id)
                                            selected
                                            @endif
                                            value="{{ $region->id }}">{{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('region_id') }}</span>
                        </div>
                    </div>
                   
            </div>
        </div>
            
            
            
        <div class="card mt-2 mb-2">
            <div class="card-body" id="addresses">
            <h5>Ваши адреса:</h5>
            @foreach($user->addressesActual as $key=>$address)
                <div class="row mb-3 ">
                    <input type="hidden" name="addresses[{{$key}}][id]" value="{{$address->id}}">
                    <div class="col-md-2">
                        <div class="form-row mb-0 mt-0 required">
                            <div class="col">
                        <label class="control-label"
                               for="address_region_id_{{$key}}">@lang('site::address.region_id')</label>
                        <select class="form-control"
                                name="addresses[{{$key}}][region_id]"
                                required
                                id="address_region_id_{{$key}}">
                            <option value="">@lang('site::messages.select_from_list')</option>
                            @foreach($regions as $region)
                                <option
                                        @if(old('region_id',$address->region_id) == $region->id) selected
                                        @endif
                                        value="{{ $region->id }}">{{ $region->name }}
                                </option>
                            @endforeach
                        </select>
                        
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-row mb-0 mt-0 required">
                            <div class="col">
                                <label class="control-label"
                                       for="address_locality_{{$key}}">@lang('site::address.locality')</label>
                                <input type="text"
                                       name="addresses[{{$key}}][locality]"
                                       id="address_locality_{{$key}}"
                                       required autocomplete="off"
                                       data-fieldid="{{$key}}"
                                       data-field-name="address_locality_{{$key}}"
                                       data-filter-locality="0"
                                       data-frombound="city"
                                       data-tobound="settlement"
                                       class="search_address form-control"
                                       placeholder="@lang('site::address.placeholder.locality')"
                                       value="{{ old('locality', $address->locality) }}">
                            </div>            
                        </div>            
                        <div class="form-row mb-0 mt-0 required">
                            <div class="col">       
                               <div class="ml-1 mt-1 search_wrapper" id="address_locality_{{$key}}_wrapper"></div>
                                        
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-row mb-0 required">
                            <div class="col">
                            <label class="control-label"
                               for="address_street_{{$key}}">@lang('site::address.street')</label>
                               <input type="text"
                               name="addresses[{{$key}}][street]"
                               id="address_street_{{$key}}"
                               required autocomplete="off"
                               data-filter-locality="1"
                               data-fieldid="{{$key}}"
                               data-field-name="address_street_{{$key}}"
                               data-frombound="street"
                               data-tobound="house"
                               class="search_address form-control"
                               placeholder="@lang('site::address.placeholder.street')"
                               value="{{ old('addresses.$key.street', $address->street) }}">
                            </div>
                        </div>

                                        
                        <div class="form-row mb-0 mt-0">
                            <div class="col">       
                                <div class="ml-1 mt-1 search_wrapper" id="address_street_{{$key}}_wrapper"></div>
                        
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 required">
                            <label class="control-label"
                                   for="address_building_{{$key}}">@lang('site::address.house')</label>
                            <input type="text"
                                   name="addresses[{{$key}}][building]"
                                   id="address_building_{{$key}}"
                                   class="search_address form-control"
                                   value="{{ old('addresses.$key.building', $address->building) }}"
                            >
                                       
                    </div>
                    <div class="col-md-1">
                                        <label class="control-label"
                                               for="address_apartment_{{$key}}">@lang('site::address.apt')</label>
                                        <input type="text"
                                               name="addresses[{{$key}}][apartment]"
                                               id="address_apartment_{{$key}}"
                                               class="search_address form-control{{ $errors->has('addresses.$key.apartment') ? ' is-invalid' : '' }}"
                                               value="{{ old('addresses.$key.apartment', $address->apartment) }}"
                                        >
                                       
                    </div>
                    <div class="col-md-1 mt-auto pb-2">
                                        <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       @if(old('addresses.$key.main',$address->main)) checked @endif
                                                       class="custom-control-input"
                                                       id="address_main_{{$key}}"
                                                       name="addresses[{{$key}}][main]">
                                                <label class="custom-control-label"
                                                       for="address_main_{{$key}}">@lang('site::address.main')</label>
                                                
                                            </div>
                                       
                    </div>
                    <div class="col-md-1 text-right mt-auto">
                             <a href="javascript:void(0);" onclick="$(this).parent().parent().remove()"   class="btn btn-danger"> <i class="fa fa-close"></i></a>
                              
                                
                    </div>
                </div>
            @endforeach
        
            </div>   
            <div class="card-body">
            <div class="row mt-3">
                    <div class="col-md-1">
                             <a href="javascript:void(0);" class="btn btn-green add-address"> <i class="fa fa-plus"></i> Добавить адрес</a>
                              
                                
                    </div>
            </div>
            </div>
        </div>   

                
                <hr/>
                <div class=" mb-2 text-right">
                    <button form="user-form" type="submit"
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
            </form>
        </div>

    </div>
@endsection
@push('scripts')
<script>

 try {
            window.addEventListener('load', function () {

                
            
            
            suggest_count = 0;
            input_initial_value = '';
            suggest_selected = 0;
            $(document)
                .on('click', '.add-address', (function(I){
                    axios
                        .get("api/views/create-address-esb")
                        .then((response) => {

                            $('#addresses').append(response.data);
                            
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                
                }))
                .on('keyup', '.search_address', (function(I){
                    var field_name = $(this)[0].dataset.fieldName;
                    var field_id = $(this)[0].dataset.fieldid;
                    var frombound = $(this)[0].dataset.frombound;
                    var tobound = $(this)[0].dataset.tobound;
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
                                region_id = $('#address_region_id_0 option:selected').text();
                                locality = $('#address_locality_'+field_id).val();
                                input_initial_value = $(this).val();
                                
                                if($(this)[0].dataset.filterLocality==1) {
                                 str=locality+' '+$(this).val();
                                } else {
                                 str=$(this).val();
                                }
                                
                                
                                $.get("/api/dadata/address", { "str":str , "frombound":frombound, "tobound":tobound},function(data){
                                
                                    var list = JSON.parse(data);
                                    
                                    suggest_count = list.length;
                                    if(suggest_count > 0){
                                        $('#'+field_name+'_wrapper').html("").show();
                                        for(var i in list){
                                            if(list[i] != ''){
                                                $('#'+field_name+'_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                                    
                                                $('#'+field_name+'_wrapper').find('#result_id-'+list[i].id).click(function() {
                                              
                                               addr_data=list[$(this)[0].getAttribute('data-key')].alldata;
                                                    if(list[$(this)[0].getAttribute('data-key')].alldata.city){
                                                        document.getElementById('address_locality_'+field_id).value = addr_data.city_with_type;
                                                  
                                                    } else {
                                                        document.getElementById('address_locality_'+field_id).value = addr_data.area_with_type;
                                                    }
                                                    
                                                    if(addr_data.street_with_type){
                                                        document.getElementById('address_street_'+field_id).value = addr_data.street_with_type;
                                                    }
                                                     
                                                    
                                                    if(addr_data.block){
                                                        document.getElementById('address_building_'+field_id).value = addr_data.house_type+' '+addr_data.house + ' '+ addr_data.block_type+' '+addr_data.block;
                                                    } else if(addr_data.house){
                                                        document.getElementById('address_building_'+field_id).value = addr_data.house_type+' '+addr_data.house;
                                                    }
                                                    
                                                    document.getElementById('address_region_id_'+field_id).value = addr_data.region_iso_code;
                                                   // $('#address_region_id_'+field_id+ ' option[value="'+addr_data.region_iso_code+'"]').prop('selected', true);
                                                    // прячем слой подсказки
                                                    $('#'+field_name+'_wrapper').fadeOut(2350).html('');
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
                            $('.search_wrapper').hide();
                            return false;
                        break;
                        
                    }
                })
                );

                 $('html').on('click', '.search_wrapper', (function(){
                    $(this).hide();
                })); 

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush