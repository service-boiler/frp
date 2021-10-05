@extends('layouts.app')
@section('title')@lang('site::messages.add') @lang('site::ticket.add')@lang('site::messages.title_separator')@endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.tickets.index') }}">@lang('site::ticket.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.add')</li>
    </ol>
  
    @alert()@endalert

<form id="form" method="POST" action="{{ route('admin.tickets.store') }}">
@csrf
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
                                        <option  @if(old('ticket.type_id') == $type->id || (!empty($request_params['consumer_phone']) && $type->id=='1'))
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
                                        <option @if(old('ticket.theme_id') == $theme->id)
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
                                        <option @if(old('ticket.receiver_id', !empty($request_params['receiver_id'])? $request_params['receiver_id'] : 'none') == $user->id)
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
                                        <option @if(old('ticket.receiver_group_id', !empty($request_params['receiver_group_id'])? $request_params['receiver_group_id'] : 'none') == $group->id)
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
                                   value="{{ old('ticket.consumer_name') }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_name') }}</span>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="consumer_company">@lang('site::ticket.consumer_company')</label>
                            <input type="text"
                                           name="ticket[consumer_company]"
                                           id="consumer_company"
                                           maxlength="100"
                                           class="form-control{{ $errors->has('ticket.consumer_company') ? ' is-invalid' : '' }}"
                                           placeholder=""
                                           value="{{ old('ticket.consumer_company') }}">
                            <span class="invalid-feedback">{{ $errors->first('ticket.consumer_company') }}</span>
                            <input type="hidden" id="consumer_company_id" name="ticket[consumer_company_id]" value="{{ old('ticket.consumer_company_id') }}">
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
                                   value="{{ old('ticket.consumer_email') }}">
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
                                   value="{{ old('ticket.consumer_phone', $request_params['consumer_phone']) }}">
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
                                    <input class="form-control" type="text" name="search_address" id="search_address" value="" autocomplete="off">
                                
                                
                            <div class="ml-3 mt-5" id="search_address_wrapper"></div>
                        </div>
                        
                        <div class="col-6 required">

                                    <label class="control-label" for="region_id">Регион<br /><span class="text-success text-small">Установится автоматически после выбора города</span></label>
                                    <select class="form-control{{  $errors->has('ticket.region_id') ? ' is-invalid' : '' }}"
                                            name="ticket[region_id]"
                                            required 
                                            id="region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('ticket.region_id') == $region->id)
                                                    selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('ticket.region_id') }}</span>
                        </div>
                       
                        
                    </div>
                     
                     <input type="hidden" id="locality" name="ticket[locality]" value="{{ old('ticket.locality') }}">
                     
            
            
            
                <div class="form-row required">
                            <div class="col mb-3">
                                <label class="control-label" for="text">@lang('site::ticket.text')</label>
                                <textarea required
                                      name="ticket[text]"
                                      id="text"
                                      class="form-control{{ $errors->has('ticket.text') ? ' is-invalid' : '' }}"
                                      >{{ old('ticket.text') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('ticket.text') }}</span>
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
            <div class="form-row">
                <div class="col text-right">
                    <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
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
                
                theme_id.on('change', function (e) {
                    receiver_id.val($('#theme_id option:selected').data("receiver"));
                    receiver_id.trigger('change.select2');
                    $('#text').val($('#theme_id option:selected').data("text"));
                
                });
                
                receiver_id.select2({
                    theme: "bootstrap4",
                    placeholder: '@lang('site::messages.select_from_list')',
                    selectOnClose: true,
                    
                });
                
                
                
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
                                                        console.log($(this)[0].getAttribute('data-key'));
                                                        console.log(list[$(this)[0].getAttribute('data-key')].alldata);
                                                        document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                        //document.getElementById('city_hidden').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                        //document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                                        
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
                
                
                
                
                
                

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush