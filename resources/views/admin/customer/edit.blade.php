@extends('layouts.app')
@section('title')@lang('site::messages.add') @lang('site::admin.customer.add')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.customers.index') }}">@lang('site::admin.customer.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.customer.edit')</li>
        </ol>
        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.customers.update',$customer) }}">
                    @csrf
                    
                    @method('PUT')
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.customer.name')</label>
                                    <input type="text" name="customer[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            value="{{ old('customer.name',$customer->name) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                            
                    <h5 class="card-title">@lang('site::admin.customer.roles')</h5>
                            <div class="form-row">
                                <div class="col mb-3">
                                    @foreach($roles->all() as $role)

                                        <div class="custom-control custom-checkbox"
                                             style="@if($role->name == 'admin') display:none;@endif">
                                            <input name="roles[]"
                                                   value="{{ $role->id }}"
                                                   type="checkbox" 
                                                   @if($customer->customerRoles->contains('id', $role->id))
                                                   checked
                                                   @endif
                                                   
                                                   class="custom-control-input" id="role-{{ $role->id }}">
                                            <label class="custom-control-label"
                                                   for="role-{{ $role->id }}">{{ $role->title }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div> 
                    <h5 class="card-title">@lang('site::admin.customer.contacts_common')</h5>  
                    <span class="text-success">Внимание! Здесь заполняятся только общая контактная информация. Контактные лица заполняются строго отдельно!</span>
                    
                    <div class="row required">        
                        <div class="col-6 required">
                            <label class="control-label"
                                               for="search_address">Город<br/></label>
                                    <input class="mb-0 form-control" type="text" name="search_address" id="search_address" value="{{$customer->locality}}" autocomplete="off">
                                
                                
                            <div class="ml-3 mt-5" id="search_address_wrapper"></div>
                            <span class="text-success text-small">Начните ввод города и выберите из списка</span>
                        </div>
                        
                            <input type="hidden" id="locality" name="customer[locality]" value="{{ old('customer.locality',$customer->locality) }}">
                        
                        <div class="col-6 required">

                                    <label class="control-label" for="region_id">Регион<br /></label>
                                    <select class="mb-0 form-control{{  $errors->has('customer.region_id') ? ' is-invalid' : '' }}"
                                            name="customer[region_id]"
                                            required 
                                            id="region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('customer.region_id',$customer->region_id) == $region->id)
                                                    selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('customer.region_id') }}</span>
                                    <span class="text-success text-small">Установится автоматически после выбора города</span>
                        </div>
                       
                        
                    </div>
                    
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="phone">@lang('site::admin.customer.phone')</label>
                                    <input required
                                           type="tel"
                                           name="customer[phone]"
                                           id="phone"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('customer.number') ? ' is-invalid' : (old('cusomer.phone') ? ' is-valid' : '') }}"
                                           value="{{ old('customer.phone',$customer->phone) }}">
                                    <span class="invalid-feedback">{{ $errors->first('phone.number') }}</span>
                                </div>
                    </div>   
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="email">@lang('site::admin.customer.email')</label>
                                    <input type="text" name="customer[email]"
                                           id="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('customer.email',$customer->email) }}">
                                    <span class="invalid-feedback">{{ $errors->first('customer.email') }}</span>
                                </div>
                    </div>   
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="any_contacts">@lang('site::admin.customer.any_contacts')</label>
                                    <textarea name="customer[any_contacts]"
                                           id="any_contacts"
                                           class="form-control{{ $errors->has('customer.any_contacts') ? ' is-invalid' : '' }}"
                                           >{{ old('customer.any_contacts',$customer->any_contacts) }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('customer.any_contacts') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="comment">@lang('site::admin.customer.comment')</label>
                                    <textarea name="customer[comment]"
                                           id="comment"
                                           class="form-control{{ $errors->has('customer.comment') ? ' is-invalid' : '' }}"
                                           >{{ old('customer.comment',$customer->comment) }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('customer.comment') }}</span>
                                </div>
                    </div>
                           
                            
                </form>
					 
                <hr/>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mb-1">
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
                            $.get("/api/dadata/address", { "str":$(this).val(), "bound":"city" },function(data){
                                var list = JSON.parse(data);
                                
                                suggest_count = list.length;
                                if(suggest_count > 0){
                                    $("#search_address_wrapper").html("").show();
                                    for(var i in list){
                                        if(list[i] != ''){
                                            $('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                            $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                console.log($(this)[0].getAttribute('data-key'));
                                                console.log(list[$(this)[0].getAttribute('data-key')].alldata);
                                                document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                //document.getElementById('city_hidden').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                //document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                                document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
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
        



        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush