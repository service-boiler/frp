@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.index') }}"> Клиенты</a>
            </li>
            <li class="breadcrumb-item active">Создать клиента</li>
        </ol>

        @alert()@endalert()

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <form id="user-form" method="POST"
                      action="{{ route('admin.esb-clients.store') }}">

                    @csrf
                    @method('POST')
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="form-row required">
                                <div class="col-sm-6 mb-3">
                                    <label class="control-label"
                                           for="user_type">Тип пользователя</label>
                                    <select class="form-control
                                            {{$errors->has('user.type_id') ? ' is-invalid' : ''}}"
                                            required
                                            name="user[type_id]"
                                            id="user_type">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($userTypes as $userType)
                                            <option   @if(old('user.type_id') == $userType->id || (!old('user.type_id') && $userType->id == 4))
                                                      selected
                                                      @endif
                                                      value="{{ $userType->id }}">{{ $userType->name }}</option>

                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user.region_id') }}</strong>
                                    </span>
                                </div>

                                <div class="col-sm-2 mb-3">
                                    <label class="control-label d-block"
                                           for="user_active">@lang('site::user.active')</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[active]"
                                               required
                                               checked
                                               id="user_active_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="user_active_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.active') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[active]"
                                               required
                                               id="user_active_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="user_active_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>

                                <div class="col-sm-2 mb-3">
                                    <label class="control-label d-block"
                                           for="user_verified">@lang('site::user.verified')</label>
                                    <div class="custom-control custom-radio  custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.verified') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[verified]"
                                               required
                                               @if(old('user.verified') == 1) checked @endif
                                               id="user_verified_1"
                                               value="1">
                                        <label class="custom-control-label"
                                               for="user_verified_1">@lang('site::messages.yes')</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input class="custom-control-input
                                                    {{$errors->has('user.verified') ? ' is-invalid' : ''}}"
                                               type="radio"
                                               name="user[verified]"
                                               required
                                               @if(old('user.verified') == 0) checked @endif
                                               id="user_verified_0"
                                               value="0">
                                        <label class="custom-control-label"
                                               for="user_verified_0">@lang('site::messages.no')</label>
                                    </div>
                                </div>
                            </div>

                                <div class="row mb-5 @if(!in_array(old('user_type'),['1,5'])) d-none @endif" id="contragent">
                                        <div class="col-12 mb-1">
                                            <h5 class="d-inline" id="company_info">@lang('site::contragent.header.legal') </h5>
                                            <small class="text-success d-inline">Введите часть ИНН и выберите из списка</small>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row"><div class="col">
                                                    <label class="control-label"
                                                           for="contragent_inn">@lang('site::contragent.inn')</label>
                                                    <input type="text"
                                                           name="contragent[inn]"
                                                           id="contragent_inn"
                                                           maxlength="12"
                                                           class="search-inn form-control{{ $errors->has('contragent.inn') ? ' is-invalid' : '' }}"
                                                           value="{{ old('contragent.inn') }}">

                                                    <span class="invalid-feedback"><strong>{!! $errors->first('contragent.inn') !!}</strong></span>
                                                </div>
                                            </div>

                                            <div class="row"><div class="col">
                                                    <div class="ml-3 mt-1 search_wrapper" id="contragent_inn_wrapper"></div>
                                                </div></div>
                                        </div>

                                        <div class="col-sm-3">
                                            <label class="control-label"
                                                   for="contragent_kpp">@lang('site::contragent.kpp')</label>
                                            <input type="text"
                                                   name="contragent[kpp]"
                                                   id="contragent_kpp"
                                                   maxlength="9"
                                                   class="form-control{{ $errors->has('contragent.kpp') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::contragent.placeholder.kpp')"
                                                   value="{{ old('contragent.kpp') }}">
                                            <span class="invalid-feedback"><strong>{{ $errors->first('contragent.kpp') }}</strong></span>
                                        </div>


                                        <div class="col-sm-3">
                                            <label class="control-label"
                                                   for="contragent_ogrn">@lang('site::contragent.ogrn')</label>
                                            <input type="text"
                                                   name="contragent[ogrn]"
                                                   id="contragent_ogrn"
                                                   maxlength="15"
                                                   class="form-control{{ $errors->has('contragent.ogrn') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::contragent.placeholder.ogrn')"
                                                   value="{{ old('contragent.ogrn') }}">
                                            <span class="invalid-feedback"><strong>{{ $errors->first('contragent.ogrn') }}</strong></span>
                                        </div>

                                        <div class="col-sm-3">
                                            <label class="control-label"
                                                   for="contragent_okpo">@lang('site::contragent.okpo')</label>
                                            <input type="text"
                                                   name="contragent[okpo]"
                                                   id="contragent_okpo"
                                                   maxlength="10"
                                                   class="form-control{{ $errors->has('contragent.okpo') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::contragent.placeholder.okpo')"
                                                   value="{{ old('contragent.okpo') }}">
                                            <span class="invalid-feedback"><strong>{{ $errors->first('contragent.okpo') }}</strong></span>
                                        </div>
                                    <div class="col-12">
                                        <label class="control-label" for="legal_address">Юридический адресс</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   name="legal_address[full]"
                                                   id="legal_address"
                                                   class="form-control{{ $errors->has('adddress.full') ? ' is-invalid' : '' }}"
                                                   value="{{old('legal_address.full')}}">
                                        </div>
                                        <input type="hidden" id="legal_address_region_id" name="legal_address[region_id]" value="{{old('address.region_id')}}">
                                        <input type="hidden" id="legal_address_locality" name="legal_address[locality]"  value="{{old('address.locality')}}">
                                        <input type="hidden" id="legal_address_street" name="legal_address[street]" value="{{old('address.street')}}">
                                        <input type="hidden" id="legal_address_building" name="legal_address[building]" value="{{old('address.building')}}">
                                        <input type="hidden" id="legal_address_apartment" name="legal_address[apartment]" value="{{old('address.apartment')}}">
                                        <input type="hidden" id="legal_address_postal" name="legal_address[postal]" value="{{old('address.postal')}}">
                                        <input type="hidden" id="contragent_type_id" name="contragent[type_id]" value="{{old('contragent.type_id')}}">
                                        <span class="invalid-feedback">{{ $errors->first('address.full') }}</span>


                                    </div>
                                



                                </div>

                            <div class="form-row form-group required">
                                <div class="col-sm-6">
                                    <label class="control-label" for="name">Имя / Наименование</label>
                                    <div class="input-group">
                                    <input type="text"
                                           name="user[name]"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('user.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::user.placeholder.name')"
                                           value="{{ old('user.name') }}">
                                    <div class="input-group-append copy-name">
                                        <div class="input-group-text">
                                            <i class="fa fa-copy"></i>&nbsp; <i class="fa fa-arrow-right"></i>
                                        </div>
                                    </div>
                                    </div>
                                    <span class="invalid-feedback">{{ $errors->first('user.name') }}</span>

                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="name_for_site">Название для сайта</label>
                                    <input type="text"
                                           name="user[name_for_site]"
                                           id="name_for_site"
                                           required
                                           class="form-control{{ $errors->has('user.name_for_site') ? ' is-invalid' : '' }}"
                                           placeholder="Например, название бренда. Должно быть узнаваемым"
                                           value="{{ old('user.name_for_site') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.name_for_site') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-3 form-group required">
                                    <label class="control-label" for="phone">Основной телефон</label>
                                    <input type="tel"
                                           name="user[phone]"
                                           id="phone"
                                           required
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern_mobile')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('user.phone') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.phone') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.phone') }}</span>
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="email"
                                           name="user[email]"
                                           id="email"
                                           class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.email') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.email') }}</span>
                                </div>
                                <div class="col-sm-2 form-group required">
                                    <label class="control-label" for="password">Пароль</label>
                                    <input type="text"
                                           name="user[password]"
                                           id="password"
                                           class="form-control{{ $errors->has('user.email') ? ' is-invalid' : '' }}"
                                           value="{{ old('user.password') }}">
                                    <span class="invalid-feedback">{{ $errors->first('user.password') }}</span>
                                </div>
                                <div class="col-sm-4 mb-3 form-group required">
                                    <label class="control-label"
                                           for="user_region_id">Основной регион пользователя</label>
                                    <select class="form-control
                                            {{$errors->has('user.region_id') ? ' is-invalid' : ''}}"
                                            required
                                            name="user[region_id]"
                                            id="user_region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($regions as $region)
                                            <option   @if(old('user.region_id') == $region->id)
                                                            selected
                                                            @endif
                                                            value="{{ $region->id }}">{{ $region->name }}</option>

                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user.region_id') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <div class="col-12">
                                    <label class="control-label" for="search_address">Адрес установки оборудования</label>
                                    <div class="input-group">
                                        <input type="text"
                                               name="address[full]"
                                               id="search_address"
                                               required
                                               class="form-control{{ $errors->has('address.full') ? ' is-invalid' : '' }}"
                                               placeholder="Начните ввод и выберите. Например, Москва Красная 1"
                                               value="{{ old('address.full') }}" autocomplete="off">
                                    </div>
                                    <span class="text-success text-small" id="part_address"></span>
                                    <input type="hidden" id="address_region_id" name="address[region_id]" value="{{old('address.region_id')}}">
                                    <input type="hidden" id="address_locality" name="address[locality]"  value="{{old('address.locality')}}">
                                    <input type="hidden" id="address_street" name="address[street]" value="{{old('address.street')}}">
                                    <input type="hidden" id="address_building" name="address[building]" value="{{old('address.building')}}">
                                    <input type="hidden" id="address_apartment" name="address[apartment]" value="{{old('address.apartment')}}">
                                    <input type="hidden" id="address_postal" name="address[postal]" value="{{old('address.postal')}}">
                                    <span class="invalid-feedback">{{ $errors->first('address.full') }}</span>
                                        <div class="row ml-3 mt-3" id="search_address_wrapper"></div>

                                    </div>
                                </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Оборудование</h5>
                            <div class="form-row">
                                <div class="col-12">
                                    <div class="row"><div class="col-sm-6">
                                            <label class="control-label"
                                                   for="product_name">Модель котла</label>
                                            <input type="text"
                                                   name="product[name]"
                                                   id="product_name"
                                                   maxlength="255"
                                                   class="search-equipment form-control{{ $errors->has('product.name') ? ' is-invalid' : '' }}"
                                                   placeholder="Например, Divabel F24"
                                                   value="{{ old('product.name') }}">

                                            <span class="invalid-feedback"><strong>{!! $errors->first('product.name') !!}</strong></span>
                                        </div>
                                        <div class="col-sm-6 text-muted mt-sm-5" id="product_name_part"></div>
                                    </div>

                                    <div class="row"><div class="col">
                                            <div class="ml-3 mt-1 search_wrapper" id="product_name_wrapper"></div>
                                        </div></div>
                                    <input type="hidden" id="product_id" name="product[id]" value="{{old('product.id')}}">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                            <label class="control-label"
                                                   for="product_name">Дополнительная краткая информация по оборудованию</label>
                                            <textarea
                                                   name="product[comment]"
                                                   id="product_comment"
                                                   maxlength="1055"
                                                   class="form-control"
                                                  >{{ old('product.comment') }}</textarea>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">

                            <div class="form-row">
                                <div class="col-sm-6 mb-3">
                                    <h5 class="card-title">@lang('rbac::role.roles')</h5>
                                    @foreach($roles->all() as $role)

                                        <div class="custom-control custom-checkbox"
                                             style="@if($role->name == 'админ') display:none;@endif">
                                            <input name="roles[]"
                                                   value="{{ $role->id }}"
                                                   type="checkbox"
                                                   @if((old('roles') && in_array($role->id,old('roles'))) || (!old('roles') && $role->name=='end_user'))
                                                           checked
                                                   @endif
                                                   class="custom-control-input" id="role-{{ $role->id }}">
                                            <label class="custom-control-label"
                                                   for="role-{{ $role->id }}">{{ $role->title }}</label>
                                        </div>
                                    @endforeach
                                </div>

                            </div>



                            <div class="form-row">
                                <div class="col text-right">
                                    <button type="submit" class="btn btn-ms">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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

@push('scripts')
    <script defer>

        try {
            window.addEventListener('load', function () {


                suggest_count = 0;
                input_initial_value = '';
                suggest_selected = 0;

                $('html').click(function(){
                    $('#search_wrapper').hide();
                });


                $(document)
                    .on('click','.copy-name', (function(event){
                        $('#name_for_site').val($('#name').val());
                        }))
                    .on('change','#user_type', (function(event){
                        if($(this).val()==1 || $(this).val()==5 ){
                            $('#contragent').removeClass('d-none');
                        } else {
                            $('#contragent').addClass('d-none');
                        }

                        }))
                    .on('keyup', '.search-inn', (function(I){

                            var field_name = $(this)[0].dataset.fieldName;
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

                                        input_initial_value = $(this).val();
                                        $.get("/api/dadata/inn", { "str":$(this).val() },function(data){
                                            var list = JSON.parse(data);

                                            suggest_count = list.length;
                                            if(suggest_count > 0){
                                                $('#contragent_inn_wrapper').html("").show();
                                                for(var i in list){
                                                    if(list[i] != ''){

                                                        $('#contragent_inn_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].inn+'">'+list[i].inn+' '+list[i].name+'</div>');
                                                        $('#contragent_inn_wrapper').find('#result_id-'+list[i].inn).click(function() {
                                                            console.log(list[$(this)[0].getAttribute('data-key')].alldata);
                                                            document.getElementById('name').value = list[$(this)[0].getAttribute('data-key')].alldata.opf.short + ' ' + list[$(this)[0].getAttribute('data-key')].alldata.name.full;
                                                            if(list[$(this)[0].getAttribute('data-key')].alldata.opf.code==50102){
                                                                document.getElementById('contragent_type_id').value = 2;
                                                            } else {
                                                                document.getElementById('contragent_type_id').value = 1;
                                                            }

                                                            document.getElementById('contragent_inn').value = list[$(this)[0].getAttribute('data-key')].alldata.inn;
                                                            document.getElementById('contragent_ogrn').value = list[$(this)[0].getAttribute('data-key')].alldata.ogrn;
                                                            if(list[$(this)[0].getAttribute('data-key')].alldata.okpo)
                                                            {document.getElementById('contragent_okpo').value = list[$(this)[0].getAttribute('data-key')].alldata.okpo;}
                                                            if(list[$(this)[0].getAttribute('data-key')].alldata.kpp)
                                                            {document.getElementById('contragent_kpp').value = list[$(this)[0].getAttribute('data-key')].alldata.kpp;}
                                                            
                                                            let address = list[$(this)[0].getAttribute('data-key')].alldata.address.data;

                                                            if(address.region_iso_code)
                                                            {document.getElementById('legal_address_region_id').value = address.region_iso_code;
                                                            }
                                                            if(address.source)
                                                            {document.getElementById('legal_address').value = address.source;
                                                            }

                                                            if(address.city_with_type)
                                                            {document.getElementById('legal_address_locality').value = address.city_with_type;
                                                                }

                                                            if(address.street_with_type)
                                                            {document.getElementById('legal_address_street').value = address.street_with_type;
                                                                }

                                                            if(address.house)
                                                            {document.getElementById('legal_address_building').value = address.house_type + ' ' +address.house;
                                                                }

                                                            if(address.block)
                                                            {document.getElementById('legal_address_building').value = document.getElementById('legal_address_building').value + ' ' +address.block_type + ' ' +address.block;
                                                               }

                                                            if(address.flat)
                                                            {document.getElementById('legal_address_apartment').value = address.flat_type + ' ' +address.flat;}

                                                            if(address.postal_code)
                                                            {document.getElementById('legal_address_postal').value = address.postal_code;}

                                                            $('#contragent_inn_wrapper').fadeOut(2350).html('');
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


                    .on('keyup', '.search-equipment', (function(I){

                            var field_name = $(this)[0].dataset.fieldName;
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

                                        input_initial_value = $(this).val();
                                        $.get("/api/products/eq-search", { "filter[search_part]":$(this).val() },function(data){
                                            var list = JSON.parse(data)['data'];

                                            suggest_count = list.length;
                                            if(suggest_count > 0){
                                                $('#product_name_wrapper').html("").show();
                                                for(var i in list){
                                                    if(list[i] != ''){
                                                        console.log(list[i]);
                                                        $('#product_name_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+' '+list[i].sku+'</div>');

                                                        $('#product_name_wrapper').find('#result_id-'+list[i].id).click(function() {

                                                            $('#product_name').val(list[i].name);
                                                            $('#product_name_part').html(list[i].id);
                                                            $('#product_id').val(list[i].id);


                                                            $('#product_name_wrapper').fadeOut(2350).html('');
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
                                    $('#search_wrapper').hide();
                                    return false;
                                    break;

                            }
                        })
                    );

                $('html').on('click', '.search_wrapper', (function(){
                    $(this).hide();
                }));

                // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой

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
                                $.get("/api/dadata/address", { "str":$(this).val() },function(data){
                                    var list = JSON.parse(data);

                                    suggest_count = list.length;
                                    if(suggest_count > 0){
                                        $("#search_address_wrapper").html("").show();
                                        for(var i in list){
                                            if(list[i] != ''){
                                                $('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');

                                                $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {

                                                    if(list[$(this)[0].getAttribute('data-key')].alldata.city){
                                                        $('#part_address').html('Регион: '+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type +
                                                            ", город: "+ list[$(this)[0].getAttribute('data-key')].alldata.city_with_type +
                                                            ", улица: "+ list[$(this)[0].getAttribute('data-key')].alldata.street_with_type +
                                                            ", "+ list[$(this)[0].getAttribute('data-key')].alldata.house_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.house +
                                                            ", "+ list[$(this)[0].getAttribute('data-key')].alldata.flat_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.flat);
                                                        $('#address_locality').val(list[$(this)[0].getAttribute('data-key')].alldata.city_with_type);

                                                    } else {
                                                        $('#part_address').html('Регион: '+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type +
                                                            ", город: "+ list[$(this)[0].getAttribute('data-key')].alldata.area_with_type +
                                                            ", улица: "+ list[$(this)[0].getAttribute('data-key')].alldata.street_with_type +
                                                            ", "+ list[$(this)[0].getAttribute('data-key')].alldata.house_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.house +
                                                            ", "+ list[$(this)[0].getAttribute('data-key')].alldata.flat_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.flat);
                                                        $('#address_locality').val(list[$(this)[0].getAttribute('data-key')].alldata.area_with_type);

                                                    }
                                                   // document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                                    $('#address_region_id').val(list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code);
                                                    $('#user_region_id').val(list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code);

                                                    $('#address_building').val(list[$(this)[0].getAttribute('data-key')].alldata.house_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.house);
                                                    $('#address_apartment').val(list[$(this)[0].getAttribute('data-key')].alldata.flat_type + ': ' + list[$(this)[0].getAttribute('data-key')].alldata.flat);
                                                    $('#address_street').val(list[$(this)[0].getAttribute('data-key')].alldata.street);
                                                    $('#address_postal').val(list[$(this)[0].getAttribute('data-key')].alldata.postal_code);
                                                    $('#search_address').val($(this).text());

                                                    // прячем слой подсказки
                                                    $('#search_address_wrapper').fadeOut(2350).html('');
                                                    console.log(list[$(this)[0].getAttribute('data-key')].alldata);
                                                });
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                            break;
                    }
                });

                $('#search_address').click(function(event){
                    if(suggest_count)
                        $('#search_address_wrapper').show();
                    event.stopPropagation();
                });


                $(document)
                    .on('keyup', '.search-bik', (function(I){

                            var field_name = $(this)[0].dataset.fieldName;
                            switch(I.keyCode) {
                                // игнорируем нажатия
                                case 13:  // enter
                                case 27:  // escape
                                case 38:  // стрелка вверх
                                case 40:  // стрелка вниз
                                    break;

                                default:
                                    $(this).attr('autocomplete','off');
                                    $('#contragent_bik_wrapper').hide();
                                    if($(this).val().length>6){

                                        input_initial_value = $(this).val();
                                        $.get("/api/dadata/bank", { "str":$(this).val() },function(data){
                                            var list = JSON.parse(data);

                                            suggest_count = list.length;
                                            if(suggest_count > 0){
                                                $('#contragent_bik_wrapper').html("").show();
                                                for(var i in list){
                                                    if(list[i] != ''){

                                                        $('#contragent_bik_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].bic+'">'+list[i].bic+' '+list[i].name+'</div>');
                                                        $('#contragent_bik_wrapper').find('#result_id-'+list[i].bic).click(function() {


                                                            document.getElementById('contragent_ks').value = list[$(this)[0].getAttribute('data-key')].alldata.correspondent_account;
                                                            document.getElementById('contragent_bik').value = list[$(this)[0].getAttribute('data-key')].alldata.bic;
                                                            document.getElementById('contragent_bank').value = list[$(this)[0].getAttribute('data-key')].name;

                                                            $('#contragent_bik_wrapper').fadeOut(2350).html('');
                                                        });
                                                    }
                                                }
                                            }
                                        }, 'html');
                                    }
                                    break;
                            }
                        })
                    );




            });


        } catch (e) {
            console.log(e);
        }
    </script>
@endpush
