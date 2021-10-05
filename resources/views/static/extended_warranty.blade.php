@extends('layouts.app')
@section('title')Дополнительная гарантия @lang('site::messages.title_separator')@endsection
@section('content')
<section class="pt-0">
        <div class="container p-0 mt-md-3">
            <img src="/images/lp/ew/banner1.jpg">
        </div>
        <div class="container mt-5 d-none d-md-block">
            <img src="/images/lp/ew/why123.svg">
        </div>
        <div class="container text-left mt-3 mt-md-5 d-block d-md-none">
            <img style="width: 100%;" src="/images/lp/ew/why123m.svg">
        </div>
        <div class="container text-left mt-5 lp-mp">
            <h3>ЗАРЕГИСТРИРУЙТЕ СВОЙ КОТЕЛ И ПОЛУЧИТЕ</h3>
            @if(env('COUNTRY')=='RU')
                <h3 class="rng">ДО 3-Х ЛЕТ ДОПОЛНИТЕЛЬНОЙ ГАРАНТИИ!</h3>
            @else
                <h3 class="rng">ДО 2-Х ЛЕТ ДОПОЛНИТЕЛЬНОЙ ГАРАНТИИ!</h3>
            @endif
        </div>

    <div class="container lp-mp">

    <div class="row pt-3 pb-5 text-left">
        <div class="col">
            <form id="register-form" method="POST" action="{{ route('register_esb') }}">
                @csrf
                <h5 class="mt-4 mb-2" id="sc_info">@lang('site::user.contact')</h5>
                <div class="form-row required">
                    <div class="col-md-4">
                        <input type="hidden" name="contact[type_id]" value="1">
                        <label class="control-label"
                               for="last_name">@lang('site::user.last_name')</label>
                        <input type="text"
                               name="last_name"
                               id="last_name"
                               required
                               class="form-control form-control-lg
                                    {{ $errors->has('last_name')
                                    ? ' is-invalid'
                                    : (old('last_name') ? ' is-valid' : '') }}"
                               value="{{ old('last_name') }}">
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"
                               for="last_name">@lang('site::user.first_name')</label>
                        <input type="text"
                               name="first_name"
                               id="first_name"
                               required
                               class="form-control form-control-lg
                                    {{ $errors->has('first_name')
                                    ? ' is-invalid'
                                    : (old('first_name') ? ' is-valid' : '') }}"
                               value="{{ old('first_name') }}">
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                    </div>
                    <div class="col-md-4">
                        <label class="control-label"
                               for="last_name">@lang('site::user.middle_name')</label>
                        <input type="text"
                               name="middle_name"
                               id="middle_name"
                               required
                               class="form-control form-control-lg
                                    {{ $errors->has('middle_name')
                                    ? ' is-invalid'
                                    : (old('middle_name') ? ' is-valid' : '') }}"
                               value="{{ old('middle_name') }}">
                        <span class="invalid-feedback">
                                        <strong>{{ $errors->first('middle_name') }}</strong>
                                    </span>
                    </div>
                </div>


                <div class="row mt-1 mt-md-3">
                    <div class="col-md-2 d-none d-md-inline-block">
                        <div class="form-row required">
                            <div class="col">

                                <label class="control-label"
                                       for="phone_country_id">@lang('site::phone.country_id')</label>
                                <select class="form-control{{  $errors->has('phone_country_id') ? ' is-invalid' : (old('phone_country_id') ? ' is-valid' : '') }}"
                                        required
                                        name="phone_country_id"
                                        id="phone_country_id">
                                    @foreach($countries as $country)
                                        <option
                                                @if(old('phone_country_id') == $country->id) selected
                                                @endif
                                                value="{{ $country->id }}">{{ $country->name }}
                                            ({{ $country->phone }})
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">
                                        <strong>{{ $errors->first('phone_country_id') }}</strong>
                                    </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-row required">
                            <div class="col">
                                <label class="control-label"
                                       for="phone_contact_number">Номер <strong>мобильного</strong> телефона</label>
                                <input required
                                       type="tel"
                                       name="phone"
                                       id="phone"
                                       oninput="mask_phones()"
                                       pattern="{{config('site.phone.pattern_mobile')}}"
                                       maxlength="{{config('site.phone.maxlength')}}"
                                       title="{{config('site.phone.format')}}"
                                       data-mask="{{config('site.phone.mask')}}"
                                       class="phone-mask search_phone form-control{{ $errors->has('phone') ? ' is-invalid' : (old('phone') ? ' is-valid' : '') }}"
                                       placeholder="@lang('site::phone.placeholder.number_'.env('COUNTRY'))"
                                       value="{{ old('phone') }}">
                                <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                                <span id="phone-exists" class="text-danger d-none">Номер телефона уже зарегистрирован на сайте. Проверьте правильность введенного номера или войдите на сайт по этому номеру.</span>
                                <span id="phone-no-mobile" class="text-danger d-none">Номер телефона указан неверно, либо это не мобильный номер.</span>
                                <small id="emailHelp" class="form-text text-success">
                                    Для входа в личный кабинет будет использоваться номер Вашего телефона
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



                        <h5 class="mt-4 mt-md-5 mb-2">Адрес установки оборудования</h5>
                        <div class="form-row required">
                            <div class="col-md-2">
                                <label class="control-label"
                                       for="address_country_id">@lang('site::address.country_id')</label>
                                <select class="country-select form-control{{  $errors->has('address.sc.country_id') ? ' is-invalid' : '' }}"
                                        name="address_country_id"
                                        required
                                        data-regions="#address_sc_region_id"
                                        data-empty="@lang('site::messages.select_from_list')"
                                        id="address_country_id">
                                    @foreach($countries as $country)
                                        <option
                                                @if(old('address_country_id') == $country->id) selected
                                                @endif
                                                value="{{ $country->id }}">{{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('address_country_id') }}</span>
                            </div>

                            <div class="col-md-3 required">

                                <label class="control-label"
                                       for="address_sc_region_id">@lang('site::address.region_id')</label>
                                <select class="form-control{{  $errors->has('address.sc.region_id') ? ' is-invalid' : '' }}"
                                        name="region_id"
                                        required
                                        id="address_sc_region_id">
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                    @foreach($address_sc_regions as $region)
                                        <option
                                                @if(old('region_id',$region_id_from_ip) == $region->id) selected
                                                @endif
                                                value="{{ $region->id }}">{{ $region->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('region_id') }}</span>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label"
                                       for="address_sc_locality">@lang('site::address.locality')</label>
                                <input type="text"
                                       name="locality"
                                       id="address_sc_locality"
                                       required autocomplete="off"
                                       class="search_address form-control{{ $errors->has('locality') ? ' is-invalid' : '' }}"
                                       placeholder="@lang('site::address.placeholder.locality')"
                                       value="{{ old('locality', $locality_from_ip) }}">

                                <div class="ml-3 mt-5 search_wrapper" id="search_address_wrapper"></div>
                                <span class="invalid-feedback">{{ $errors->first('address.sc.locality') }}</span>
                            </div>

                            <div class="col-sm-3">
                                <label class="control-label"
                                       for="address_sc_street">@lang('site::address.street')</label>
                                <input type="text"
                                       name="street"
                                       id="address_sc_street"
                                       required autocomplete="off"
                                       class="form-control{{ $errors->has('street') ? ' is-invalid' : '' }}"
                                       placeholder="@lang('site::address.placeholder.street')"
                                       value="{{ old('street') }}">

                                <div class="ml-3 mt-5 search_wrapper" id="search_address_wrapper"></div>
                                <span class="invalid-feedback">{{ $errors->first('address.sc.street') }}</span>
                            </div>

                            <div class="col-sm-1">
                                <label class="control-label"
                                       for="address_sc_street">@lang('site::address.building_sm')</label>
                                <input type="text"
                                       name="building"
                                       id="address_sc_building"
                                       required autocomplete="off"
                                       class="form-control{{ $errors->has('building') ? ' is-invalid' : '' }}"
                                       value="{{ old('building') }}">

                                <div class="ml-3 mt-5 search_wrapper" id="search_address_wrapper"></div>
                                <span class="invalid-feedback">{{ $errors->first('address.sc.building') }}</span>
                            </div>
                        </div>
                <h6 class="mt-3"><a target="_blank" href="/easc"><i class="fa fa-external-link"></i>
                        Модели котлов, участвующие в программе дополнительной гарантии</a></h6>



                <h5 class="mt-4 mt-md-5 mb-2">Данные для входа</h5>
                <div class="form-row required">

                    <div class="col-sm-3">
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
                    <div class="col-sm-3">
                        <label class="control-label"
                               for="password-confirmation">@lang('site::user.password_confirmation')</label>
                        <input id="password-confirmation"
                               type="password"
                               required
                               class="form-control"
                               placeholder="@lang('site::user.placeholder.password_confirmation')"
                               name="password_confirmation">
                    </div>

                    <div class="col-sm-6">
                        <label class="control-label"
                               for="captcha">@lang('site::register.captcha')</label>
                        <div class="row">
                            <div class="col-6 pr-1">
                                <input type="text"
                                       name="captcha"
                                       required
                                       id="captcha"
                                       class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                       placeholder="@lang('site::register.placeholder.captcha')"
                                       value="">
                                <span class="invalid-feedback">{{ $errors->first('captcha') }}</span>
                            </div>
                            <div class="col-6 pl-0 captcha">
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
                            <input type="checkbox" name="accept2" required value="1" class="custom-control-input"
                                   id="accept2" @if(old('accept2')==1) checked @endif>
                            <label class="custom-control-label" for="accept2"><span
                                        style="color:red;margin-right: 2px;">*</span>Я ознакомлен и согласен с <a href="/up/esb-pd.pdf"><u>политикой сбора, хранения, обработкой и передачи персональных данных.</u></a>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-row text-left mt-5 mb-3">

                    <div class="col-sm-2 text-center">
                        <button class="btn btn-ms w-100" type="submit">Зарегистрироваться</button>
                        <a class="d-block mt-1 gray" href="{{route('login')}}"><u>@lang('site::user.already')</u></a>
                    </div>
                </div>
            </form>
        </div>

    </div>
    </div>
    @if(env('COUNTRY')=='RU')
    <div class="container text-left lp-mp lp-mp-card-12">
        <h3>Программа 5-летней гарантии FERROLI</h3>
        <p class="mt-3">
        Программа 5-летней гарантии FERROLI позволяет вам получить возможность продлить стандартный гарантийный срок
        Вашего оборудования (2 года) на три дополнительных года*. Зарегистрировавшись в программе, вы получите не
        только дополнительный гарантийный срок, но и личный кабинет с возможностью выбора лучших авторизованных
        сервисных центров для обслуживания вашего оборудования. Ваш личный кабинет позволит вам работать напрямую
        с сервисными центрами FERROLI: отправлять заявки на монтаж, ввод в эксплуатацию, техническое обслуживание
        и ремонт вашего котла. Вы сможете получать напоминания о приближающихся датах ежегодного обслуживания,
        сформировать и хранить полную историю обслуживания котла.</p>
        <p class="rng mt-3">Желаем Вам самых приятных эмоций от использования нашего оборудования. Искренне Ваши, Команда FERROLI GROUP.</p>
        <p class="mt-3 gray"><small>(<span class="rng">*</span>) Полный текст условий дополнительной гарантии вы найдете в личном кабинете. <br />
            Дополнительная ограниченная гарантия на котельное оборудование марки FERROLI предоставляется ООО «Ферроли Рус» приобретенное не ранее 01.01.2021.
            Дополнительная ограниченная гарантия предоставляется в соответствие с положениями, указанными в электронном гарантийном талоне и исключительно
            на территории Российской Федерации на оборудование, участвующее в программе
            (см. список на <a class="gray" href="https://market.ferroli.ru/easc"><u>https://market.ferroli.ru/easc</u></a>) и
            поставляемое через ООО «Ферроли Рус» и ИЗАО «ФЕРРОЛИБЕЛ». Предложение не является публичной офертой и не связано с определением
            гарантии по Закону «О защите прав потребителей». Данная ограниченная гарантия носит поощрительный характер и действует исключительно
            при регистрации оборудования в личном кабинете пользователя и соблюдении условий ее предоставления.</small></p>
    </div>
        @elseif(env('COUNTRY')=='BY')
    <div class="container text-left lp-mp lp-mp-card-12">
        <h3>Программа дополниельной гарантии FERROLI</h3>
        <p class="mt-3">
            Программа дополнительной гарантии FERROLI позволяет вам получить возможность продлить стандартный гарантийный срок
            Вашего оборудования (2 года или 5 лет) на два дополнительных года*. Зарегистрировавшись в программе, вы получите не
            только дополнительный гарантийный срок, но и личный кабинет с возможностью выбора лучших авторизованных
            сервисных центров для обслуживания вашего оборудования. Ваш личный кабинет позволит вам работать напрямую
            с сервисными центрами FERROLI: отправлять заявки на монтаж, ввод в эксплуатацию, техническое обслуживание
            и ремонт вашего котла. Вы сможете получать напоминания о приближающихся датах ежегодного обслуживания,
            сформировать и хранить полную историю обслуживания котла.</p>
        <p class="rng mt-3">Желаем Вам самых приятных эмоций от использования нашего оборудования. Искренне Ваши, Команда FERROLI GROUP.</p>
        <p class="mt-3 gray"><small>(<span class="rng">*</span>) Полный текст условий дополнительной гарантии вы найдете в личном кабинете. <br />
                Дополнительная ограниченная гарантия на котельное оборудование марки FERROLI предоставляется ИЗАО «ФерролиБел» приобретенное не ранее 01.01.2021.
                Дополнительная ограниченная гарантия предоставляется в соответствие с положениями, указанными в электронном гарантийном талоне и исключительно
                на территории Российской Федерации на оборудование, участвующее в программе
                (см. список на <a class="gray" href="https://market.ferroli.by/easc"><u>https://market.ferroli.by/easc</u></a>) и
                поставляемое через ООО «Ферроли Рус» и ИЗАО «ФЕРРОЛИБЕЛ». Предложение не является публичной офертой и не связано с определением
                гарантии по Закону «О защите прав потребителей». Данная ограниченная гарантия носит поощрительный характер и действует исключительно
        @endif





</section>
   {{--
   <!-- Advantages -->
   <section class="advantages">
     <div class="title-block title-block--gray">
        <div class="container">
          <h2>Как получить дополнительную гарантию?</h2>
        </div>
     </div>
  
     <div class="orange-line" style="margin-top: 20px;">
       <div class="card-container">
         <div class="row justify-content-center">
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-2.svg" alt="">
               <p>
                 Регитрируете оборудование
               </p>
             </div>
           </div>
  
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-2.svg" alt="">
               <p>
                 Следуете инструкциям на сайте
               </p>
             </div>
           </div>
  
           <div class="card-col">
             <div class="card advant-card">
               <img src="images/adv-pic-2.svg" alt="">
               <p>
                 Пользуетесь дополнительной гарантией от Ferroli.
               </p>
             </div>
           </div>

  
         </div>
       </div>
   </section>
--}}
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
                                                $('#phone-exists').removeClass('d-none');
                                            } else {
                                                $('#phone-exists').addClass('d-none');
                                            }
                                            if(list['error']=='no_mobile'){
                                                $('#phone-no-mobile').removeClass('d-none');
                                            } else {
                                                $('#phone-no-mobile').addClass('d-none');
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








                suggest_count = 0;
                input_initial_value = '';
                suggest_selected = 0;
                $(document)
                    .on('keyup', '.search_address', (function(I){

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
                                        $.get("/api/dadata/address", { "str":$(this).val() , "tobound":"settlement"},function(data){
                                            var list = JSON.parse(data);

                                            suggest_count = list.length;
                                            if(suggest_count > 0){
                                                $('#search_address_wrapper').html("").show();
                                                for(var i in list){
                                                    if(list[i] != ''){
                                                        $('#search_address_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                                        $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {

                                                            if(list[$(this)[0].getAttribute('data-key')].alldata.city){
                                                                document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                                $('#search_address').val(list[$(this)[0].getAttribute('data-key')].alldata.city+' ('+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type+')');
                                                            } else {
                                                                document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.area_with_type;
                                                                $('#search_address').val(list[$(this)[0].getAttribute('data-key')].alldata.area_with_type+' ('+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type+')');
                                                            }
                                                            //document.getElementById('address_sc_locality').value = list[$(this)[0].getAttribute('data-key')].name;
                                                            //document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                                            //$('#search_address').val($(this).text());


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
                        selected.push(id+'aaaaaa');
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