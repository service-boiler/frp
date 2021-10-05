@extends('layouts.app')
@section('title')@lang('site::register.title')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::register.title'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::register.title')]
        ]
    ])
@endsection

@section('content')
    <div class="container">
        @if ($errors->any())
            <h4 class="alert-heading">@lang('site::register.help.mail')</h4>
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row pt-0">
        <div class="col-9">
		<a href="@lang('site::register.rules_href')" target="_blank" class="btn btn-ms mb-2">@lang('site::register.rules')</a>
		<a  class="btn btn-ms mb-2"  href="{{ route('register_fls') }}"><b>@lang('site::register.help.fls_btn')</b></a>
		<a  class="btn btn-ms mb-2"  href="{{ route('register') }}"><b>@lang('site::register.help.user_btn')</b></a>
        </div>
        <div class="col-md-3 text-sm-right">
        <img src="/images/masterplus-logo.png" alt="master-plus" style="max-width: 150px;">
        </div>
        </div>
        <div class="row pt-3 pb-5">
            <div class="col">
                <form id="register-form" method="POST" action="{{ route('register_fl') }}">
                    @csrf
					<h4 class="mt-4 mb-2" id="sc_info">@lang('site::user.contact')</h4>
                    <div class="form-row required">
                        <div class="col">
						<input type="hidden" name="contact[type_id]" value="1">
						    <input type="text"
                                   name="name"
                                   id="name"
                                   required
                                   class="form-control form-control-lg
                                    {{ $errors->has('name')
                                    ? ' is-invalid'
                                    : (old('name') ? ' is-valid' : '') }}"
                                   placeholder="@lang('site::user.placeholder.name_fl')"
                                   value="{{ old('name') }}">
                            <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-row required">
                                <div class="col">

                                    <label class="control-label"
                                           for="phone_contact_country_id">@lang('site::phone.country_id')</label>
                                    <select class="form-control{{  $errors->has('contact.phone.country_id') ? ' is-invalid' : (old('contact.phone.country_id') ? ' is-valid' : '') }}"
                                            required
                                            name="contact[phone][country_id]"
                                            id="phone_contact_country_id">
                                        @foreach($countries as $country)
                                            <option
                                                    @if(old('contact.phone.country_id') == $country->id) selected
                                                    @endif
                                                    value="{{ $country->id }}">{{ $country->name }}
                                                ({{ $country->phone }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact.phone.country_id') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="phone_contact_number">@lang('site::phone.number') <strong>@lang('site::phone.mobile')</strong></label>
                                    <input required
                                           type="tel"
                                           name="contact[phone][number]"
                                           id="phone_contact_number"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask search_phone form-control{{ $errors->has('contact.phone.number') ? ' is-invalid' : (old('contact.phone.number') ? ' is-valid' : '') }}"
                                           placeholder="@lang('site::phone.placeholder.number')"
                                           value="{{ old('contact.phone.number') }}">
                                    <span class="invalid-feedback">{{ $errors->first('contact.phone.number') }}</span>
                                    <span id="phone-exists" class="text-danger d-none">Номер телефона уже зарегистрирован на сайте. Проверьте правильность введенного номера или войдите на сайт по этому номеру.</span>
                                    <small id="emailHelp" class="form-text text-success">
                                        Для входа в личный кабинет будет использоваться телефон или email
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label" for="email">@lang('site::user.email')</label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="search_email form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::user.placeholder.email')"
                                           value="{{ old('email') }}">
                                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                    <span id="email-exists" class="text-danger d-none">E-mail уже зарегистрирован на сайте. Проверьте правильность или войдите на сайт по этому e-mail.</span>
                                    
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">

                            <h4 class="mt-4 mb-2">@lang('site::address.header.state')</h4>
                            <div class="form-row required">
                                    <div class="col">
                                        <input type="hidden"
                                               name="address[sc][type_id]"
                                               value="2">
                                        <label class="control-label"
                                               for="address_sc_country_id">@lang('site::address.country_id')</label>
                                        <select class="country-select form-control{{  $errors->has('address.sc.country_id') ? ' is-invalid' : '' }}"
                                                name="address[sc][country_id]"
                                                required
                                                data-regions="#address_sc_region_id"
                                                data-empty="@lang('site::messages.select_from_list')"
                                                id="address_sc_country_id">
                                            @foreach($countries as $country)
                                                <option
                                                        @if(old('address.sc.country_id') == $country->id) selected
                                                        @endif
                                                        value="{{ $country->id }}">{{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('address.sc.country_id') }}</span>
                                    </div>
                                    
                                    <div class="col required">

                                        <label class="control-label"
                                               for="address_sc_region_id">@lang('site::address.region_id')</label>
                                        <select class="form-control{{  $errors->has('address.sc.region_id') ? ' is-invalid' : '' }}"
                                                name="address[sc][region_id]"
                                                required
                                                id="address_sc_region_id">
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                            @foreach($address_sc_regions as $region)
                                                <option
                                                        @if(old('address.sc.region_id', $region_id_from_ip) == $region->id) selected
                                                        @endif
                                                        value="{{ $region->id }}">{{ $region->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('address.sc.region_id') }}</span>
                                    </div>
                                    <div class="col">
                                        <label class="control-label"
                                               for="address_sc_locality">@lang('site::address.locality')</label>
                                        <input type="text"
                                               name="address[sc][locality]"
                                               id="address_sc_locality"
                                               required
                                               class="form-control{{ $errors->has('address.sc.locality') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::address.placeholder.locality')"
                                               value="{{ old('address.sc.locality', $locality_from_ip) }}">
                                        <span class="invalid-feedback">{{ $errors->first('address.sc.locality') }}</span>
                                    </div>
                            </div>
                               
                        </div>
						
                    </div>
                    
                    
                    <div class="row">
                        <div class="col">
                        <h4 class="mt-4 mb-2">@lang('site::user.position_fl')</h4>
                        <span id="usersHelp" class="d-block form-text text-success">
                            Введите название или ИНН Вашей компании и выберите вариант из выпадающего списка.
                        </span>
                                    <fieldset id="users-search-fieldset"
                                              style="display: block; padding-left: 5px;">
                                        <div class="form-row">
                                            <select class="form-control" id="users_search"  name="contact[user_id]">
                                                <option></option>
                                            </select>
                                            
                                        </div>
                                    </fieldset>
                        </div>
                    </div>
                    
                    
                    <div class="row required">
                                        <div class="col-sm-6">
                                           <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::user.roles.help')"><i class="fa fa-info-circle" aria-hidden="true"></i></span> <label class="control-label custom-control-inline col-md-5"
                                                   for="role">@lang('site::user.roles.role')</label>
                                            
                                            <div class="custom-control custom-radio custom-control">
                                                <input class="custom-control-input
                                                    {{$errors->has('contact.role') ? ' is-invalid' : ''}}"
                                                       type="radio"
                                                       name="contact[role]"
                                                       required
                                                       @if(old('contact.role') == 15) checked
                                                       @endif
                                                       id="role_15"
                                                       value="15">
                                                <label class="custom-control-label"
                                                       for="role_15">@lang('site::user.roles.service')</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control">
                                                <input class="custom-control-input
                                                    {{$errors->has('contact.role') ? ' is-invalid' : ''}}"
                                                       type="radio"
                                                       name="contact[role]"
                                                       required
                                                       @if(old('contact.role') == 14) checked
                                                       @endif
                                                       id="role_14"
                                                       value="14">
                                                <label class="custom-control-label"
                                                       for="role_14">@lang('site::user.roles.montage')</label>
                                            </div>
                                            
                                        </div>
					
                                    </div>
                    
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-row">
                                <div class="col">
                                <span id="usersHelp" class="d-block form-text text-success">
                                    Если Вы не смогли найти Вашу компанию, укажите ее название и город в строке ниже.
                                </span>
                                    <input type="text" name="contact[position]" id="contact_position"
                                           class="form-control{{ $errors->has('contact.position') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::user.placeholder.position_fl')"
                                           value="{{ old('contact.position') }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contact.position') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-2" id="company_info">@lang('site::user.header.info')</h4>

                    <div class="form-row required">
                        
                    </div>
                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label" for="password">@lang('site::user.password')</label>
                            <input type="password"
                                   name="password"
                                   required
                                   id="password"
                                   minlength="6"
                                   maxlength="20"
                                   class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.password')"
                                   value="{{ old('password') }}">
                            <span class="invalid-feedback">{{ $errors->first('password') }}</span>

                        </div>
                    </div>

                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label"
                                   for="password-confirmation">@lang('site::user.password_confirmation')</label>
                            <input id="password-confirmation"
                                   type="password"
                                   required
                                   class="form-control"
                                   placeholder="@lang('site::user.placeholder.password_confirmation')"
                                   name="password_confirmation">
                        </div>
                    </div>


                    <div class="form-row required">
                        <div class="col">
                            <label class="control-label"
                                   for="captcha">@lang('site::register.captcha')</label>
                            <div class="row">
                                <div class="col-md-3">
                                    <input type="text"
                                           name="captcha"
                                           required
                                           id="captcha"
                                           class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::register.placeholder.captcha')"
                                           value="">
                                    <span class="invalid-feedback">{{ $errors->first('captcha') }}</span>
                                </div>
                                <div class="col-md-9 captcha">
                                    <span>{!! captcha_img('flat') !!}</span>
                                    <button data-toggle="tooltip" data-placement="top"
                                            title="@lang('site::messages.refresh')" type="button"
                                            class="btn btn-outline-secondary" id="captcha-refresh">
                                        <i class="fa fa-refresh"></i>
                                    </button>
                                </div>

                            </div>

                        </div>
                    </div>

                    <hr class="mt-4 mb-2"/>
                    <div class="form-row required">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="accept" required value="1" class="custom-control-input"
                                       id="accept">
                                <label class="custom-control-label" for="accept"><span
                                            style="color:red;margin-right: 2px;">*</span>@lang('site::register.accept_fl_cb')
                                </label>
                            </div>
                        </div>
                    </div>
					<div class="form-row text-center mt-5 mb-3">
					<div class="col">
					@lang('site::register.accept_fl')
					</div>
                    </div>
                    <div class="form-row text-center mt-5 mb-3">
					
                        <div class="col">
                            <button class="btn btn-ms" type="submit">@lang('site::user.sign_up')</button>
                        </div>
                    </div>
                </form>
                <div class="text-center">
                    <a class="d-block" href="{{route('login')}}">@lang('site::user.already')</a>
                </div>
                <div class="text-center mb-3">
                    <h5>@lang('site::register.help.mail')</h5>
                </div>
            </div>
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
                .on('keyup', '.search_phone', (function(I){
                    $('#phone-exists').addClass('d-none');
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            $(this).attr('autocomplete','off');
                        
                            if($(this).val().length>14){

                                input_initial_value = $(this).val();
                                $.get("/api/phone-exists", { "phone":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    if(list['exists']){
                                    console.log(list);
                                    $('#phone-exists').removeClass('d-none');
                                    } else {
                                    $('#phone-exists').addClass('d-none');
                                    }
                                }, 'html');
                            }
                        break;
                    }
                })
                )
                .on('keyup', '.search_email', (function(I){
                    $('#email-exists').addClass('d-none');
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            $(this).attr('autocomplete','off');
                        
                            if($(this).val().length>9){

                                input_initial_value = $(this).val();
                                $.get("/api/email-exists", { "email":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    if(list['exists']){
                                    console.log(list);
                                    $('#email-exists').removeClass('d-none');
                                    } else {
                                    $('#email-exists').addClass('d-none');
                                    }
                                }, 'html');
                            }
                        break;
                    }
                })
                )

                
            
            
            
            
            
            
            
            
            
            let region = $('#address_sc_region_id'),
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
                } else {
                    users_search.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush




@push('scripts')
<script defer>
    try {
        document.querySelector('#captcha-refresh').addEventListener('click', function () {
            fetch('/captcha/flat')
                .then(response => {
                    response.blob().then(blobResponse => {
                        const urlCreator = window.URL || window.webkitURL;
                        document.querySelector('.captcha span img').src = urlCreator.createObjectURL(blobResponse);
                    });
                });
        });
        
    } catch (e) {
        console.log(e);
    }
</script>
@endpush
