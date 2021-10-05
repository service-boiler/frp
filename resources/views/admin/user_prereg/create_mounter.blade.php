@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.user_prereg.mounters') }}">Предварительные регистрации монтажников</a>
            </li>
            <li class="breadcrumb-item active">Создание</li>
        </ol>

        @alert()@endalert()
        <div class="card mb-5">
            <div class="card-body" id="summernote">

                <form id="form" method="POST" action="{{ route('ferroli-user.user_prereg.store') }}">
                    @csrf
                    <div class="form-row required mt-3">
                        <div class="col-3">
                            <label class="control-label" for="lastname">Фамилия</label>
                            <input type="text"
                                   name="prereg[lastname]"
                                   id="lastname"
                                   required
                                   class="form-control{{ $errors->has('prereg.lastname') ? ' is-invalid' : '' }}"
                                   value="{{ old('prereg.lastname') }}">
                            <span class="invalid-feedback">{{ $errors->first('prereg.lastname') }}</span>
                        </div>
                    
                        <div class="col-3">
                            <label class="control-label" for="firstname">Имя</label>
                            <input type="text"
                                   name="prereg[firstname]"
                                   id="firstname"
                                   required
                                   class="form-control{{ $errors->has('prereg.firstname') ? ' is-invalid' : '' }}"
                                   value="{{ old('prereg.firstname') }}">
                            <span class="invalid-feedback">{{ $errors->first('prereg.firstname') }}</span>
                        </div>
                    
                        <div class="col-3">
                            <label class="control-label" for="middlename">Отчество</label>
                            <input type="text"
                                   name="prereg[middlename]"
                                   id="middlename"
                                   required
                                   class="form-control{{ $errors->has('prereg.middlename') ? ' is-invalid' : '' }}"
                                   value="{{ old('prereg.middlename') }}">
                            <span class="invalid-feedback">{{ $errors->first('prereg.middlename') }}</span>
                        </div>
                        <div class="col-3 required">

                                    <label class="control-label" for="role_id">Роль пользователя</label>
                                    <select class="form-control{{  $errors->has('prereg.role_id') ? ' is-invalid' : '' }}"
                                            name="prereg[role_id]"
                                            required
                                            id="role_id">
                                            <option selected value="14">Монтажник ФЛ </option>
                                            <option value="15">Сервис ФЛ </option>
                                            <option value="16">Продавец ФЛ </option>
                                     
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('prereg.role_id') }}</span>
                        </div>
                    </div>
                    <div class="form-row mt-3">
                        <div class="col-3 required">
                            <label class="control-label" for="lastname">email</label>
                            <input type="email"
                                   name="prereg[email]"
                                   id="email"
                                   required
                                   class="form-control{{ $errors->has('prereg.email') ? ' is-invalid' : '' }}"
                                   value="{{ old('prereg.email') }}">
                            <span class="invalid-feedback">{{ $errors->first('prereg.email') }}</span>
                        </div>
                        <div class="col-3">
                            <label class="control-label"
                                   for="phone">Телефон</label>
                            <input type="tel"
                                   name="prereg[phone]"
                                   id="phone"
                                   oninput="mask_phones()"
                                   pattern="{{config('site.phone.pattern')}}"
                                   maxlength="{{config('site.phone.maxlength')}}"
                                   title="{{config('site.phone.format')}}"
                                   data-mask="{{config('site.phone.mask')}}"
                                   class="phone-mask form-control{{ $errors->has('prereg.phone') ? ' is-invalid' : '' }}"
                                   value="{{ old('prereg.phone') }}">
                            <span class="invalid-feedback">{{ $errors->first('prereg.phone') }}</span>
                        </div>
                        <div class="col-6 ">
                            <label class="control-label"
                                               for="users_search">Компания</span></label>
                                    <input class="form-control" type="text" name="users_search" id="users_search" value="" autocomplete="off">
                                
                                
                            <div class="ml-3 mt-5" id="users_search_wrapper"></div>
                        </div>
                        
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
                                    <select class="form-control{{  $errors->has('prereg.region_id') ? ' is-invalid' : '' }}"
                                            name="prereg[region_id]"
                                            required 
                                            id="region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('prereg.region_id') == $region->id)
                                                    selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('prereg.region_id') }}</span>
                        </div>
                       
                        
                    </div>
                     
                     <input type="hidden" id="locality" name="prereg[locality]" value="{{ old('prereg.locality') }}">
                     <input type="hidden" id="parent_user_id" name="prereg[parent_user_id]" value="{{ old('prereg.parent_user_id') }}">
                     
                </form>
                <hr/>
                <div class="form-row">
                 
                    @foreach($errors->all() as $error)
                    <span class="text-danger">{{$error}}</span>
                    @endforeach
                    <div class="col text-right">
                        <button form="form" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('ferroli-user.user_prereg.mounters') }}" class="btn btn-secondary mb-1">
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
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
     
     
     
             
            $("#users_search").keyup(function(I){
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
                            $.get("/api/users", { "filter[search_user]":$(this).val() },function(data){
                                var list = JSON.parse(data);
                                
                                suggest_count = list.length;
                                
                                if(suggest_count > 0){
                                    $("#users_search_wrapper").html("").show();
                                    for(var i in list){
                                        if(list[i] != ''){
                                            $('#users_search_wrapper').append('<div class="user_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                            $('#users_search_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                console.log(list[$(this)[0].getAttribute('data-key')]);
                                                document.getElementById('parent_user_id').value = list[$(this)[0].getAttribute('data-key')].id;
                                                $('#users_search').val($(this).text());
                                                
                                                // прячем слой подсказки
                                                $('#users_search_wrapper').fadeOut(2350).html('');
                                            });
                                        }
                                    }
                                }
                            }, 'html');
                        }
                    break;
                }
            });

            
            $("#users_search").keydown(function(I){
                switch(I.keyCode) {
                    case 27: // escape
                        $('#users_search_wrapper').hide();
                        return false;
                    break;
                    
                }
            });

             $('html').click(function(){
                $('#users_search_wrapper').hide();
            }); 
            
            // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
            $('#users_search').click(function(event){
                if(suggest_count)
                    $('#users_search_wrapper').show();
                event.stopPropagation();
            });
     
     
     
     
     
     
     
     
     
     
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
     

         /*    let region = $('#address_sc_region_id'),
                users_search = $('#users_search'),
                users = $('#users'),
                selected = [];
            
            users_search.select2({
                theme: "bootstrap4",
                ajax: {
                    url: '/api/users',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_user]': params.term,
                            'filter[region_id]': region.val(),
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data,
                        };
                    }
                },
                
                minimumInputLength: 3,
                templateResult: function (user) {
                    if (user.loading) return "...";
                    let markup = user.name + ' (' + user.locality + ', ' + user.region_name + ')';
                    return markup;
                },
                templateSelection: function (user) {
                    if (user.id !== "") {
                        return user.name + ' (' + user.locality + ', ' + user.region_name + ')';
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
                     
                    users_search.addClass('is-invalid');
                }
            }); */


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush