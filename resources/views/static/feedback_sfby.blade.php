@extends('layouts.app')
@section('title')@lang('site::feedback.contacts')@lang('site::messages.title_separator')@endsection
@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?apikey=0218c2ca-609f-4289-a09f-e6e3b4738691&lang=ru_RU" type="text/javascript"></script>
@endpush

@push('scripts')
<script>
    let myMap;

    ymaps.ready(init);

    function init() {
        myMap = new ymaps.Map('contact-map', {
            center: [53.74, 27.35],
            zoom: 10,
            controls: ['zoomControl', 'typeSelector', 'fullscreenControl']
        }, {
            searchControlProvider: 'yandex#search'
        });

        ymaps.geocode('Беларусь, Минская область, Дзержинский район, Фаниполь, Заводская улица, 45', {results: 1}).then(function (res) {

            console.log(res.geoObjects.get(0).geometry.getCoordinates());

            let firstGeoObject = res.geoObjects.get(0);
            let c = firstGeoObject.geometry.getCoordinates();

            let myPlacemark = new ymaps.Placemark(c, {
                    balloonContent: '<p><b>Представительство Ferroli в РБ</b></p>' + '<p>г.Фаниполь, ул.Заводская, 45</p>',
                    iconContent: 'FERROLI'
                }, {
                    preset: 'islands#redStretchyIcon',
                }
            );
            myMap.geoObjects.add(myPlacemark);

            myMap.setBounds(myMap.geoObjects.getBounds(), {checkZoomRange:true}).then(function(){ if(myMap.getZoom() > 9) myMap.setZoom(9);});
        });
    }
</script>
@endpush
@section('header')
    @include('site::header.front',[
        'h1' => 'Контакты',
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::feedback.contacts')]
        ]
    ])
@endsection
@section('content')
    <section class="p-0">
        <div class="contact-map" id="contact-map"></div>
    </section>

    <section>
        <div class="container"><a name="form"></a>
            @alert()@endalert
            <div class="row">
                <div class="col-sm-12 col-lg-12 mb-5 h6">
                <p class="mb-2">Вы можете заказать оборудование Ferroli у официального дилера на нашем сайте <a href="https://market.ferroli.by">market.ferroli.by</a> 
                     </p>
                <p class="mb-2">Обращаем ваше внимание, что подбор оборудования и его приобретение осуществляется через официальных дилеров.
                <br />Список официальных дилеров Вы можете найти в разделе <a href="{{route('where-to-buy')}}">"Где купить?"</a></p>
                
                <p>Ремонт оборудования и приобретение запасных частей осуществляется через сервисные центры, список которых Вы можете найти разделе <a href="{{ route('service-centers') }}">АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a></p>

                </div>
            </div>
            <div class="row">
                <div class="col-sm-6 col-lg-5 mb-5">
                    <p class="mt-4">ИЗАО «ФерролиБел»</p>
                    <p>Минская обл., Дзержинский р-н, г.Фаниполь, ул.Заводская, 45</p>
                    <hr/>
                    <div class="row">
                        <div class="col-sm-6 col-lg-5">
                        
                        Тел. +375 (17) 169-79-49<br> 
                        Тел. +375 (17) 169-79-59<br>
                        Email: service@ferroli.by<br>
                        
                        </div>
                        <div class="col-sm-6 col-lg-5">
                            <img src="/images/sez.jpg" alt="Свободная Экономическая Зона Минск" width="120" height="90">
                        </div>
                        
                    </div>
                    <hr/>
                    <div class="row">
                    <div class="col mt-2">
                    <img src="/images/factory-bel.jpg" alt="Свободная Экономическая Зона Минск" width="100%">
                    <br /> Завод Ferroli в г. Фаниполь
                    </div>
                    </div>
                    
                </div>
                
                <div class="d-none d-lg-block col-lg-2"></div>
                <div class="col-sm-6 col-lg-5">
                    <h4>@lang('site::feedback.feedback')</h4>
                    @if ($errors->any())
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                    <form action="{{route('message')}}#form" method="POST" autocomplete="off">
                        @csrf
                        <div class="form-group required">
                                <label class="control-label"
                                       for="name">Ваше имя</label>
                            <div class="input-group">
                                <input name="name" type="text" placeholder="@lang('site::feedback.name')"
                                       required
                                       class="mb-0 form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name') }}">
                                <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                            </div>
                        </div>
                        <div class="form-group required">
                                <label class="control-label"
                                       for="email">Адрес электронной почты</label>
                            <div class="input-group">
                                <input name="email" type="email" placeholder="@lang('site::feedback.email')"
                                       required
                                       class="mb-0 form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       value="{{ old('email') }}">
                                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            </div>
                        </div>
                        <div class="form-group required ">
                                <label class="control-label"
                                       for="phone">Телефон</label>
                                <div class="input-group">
                                    <input required
                                           type="tel"
                                           oninput="mask_phones()"
                                           id="phone"
                                           name="phone"
                                           class="phone-mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           value="{{ old('phone') }}"
                                           >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                        </div>
                        
                        <div class="form-group required">
                        

                                <label class="control-label" for="theme">Тема обращения</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('theme') ? ' is-invalid' : '' }}"
                                        name="theme"
                                        required 
                                        id="theme">
                                    <option value="">- Выберите тему - </option>
                                    <option @if(old('theme')=='Технические вопросы') selected @endif value="Технические вопросы" data-code="1" 
                                        data-message="Пожалуйста, обратитесть в авторизованный сервсный центр. Cписок Вы можете найти разделе <a href='/services'>АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a>">Технические вопросы</option>
                                    <option @if(old('theme')=='Коммерческие вопросы') selected @endif value="Коммерческие вопросы" data-code="2" >Коммерческие вопросы</option>
                                    <option @if(old('theme')=='Ремонт оборудования') selected @endif value="Ремонт оборудования" data-code="1" data-message="Пожалуйста, обратитесть в авторизованный сервсный центр. Cписок Вы можете найти разделе <a href='/services'>АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a>">Ремонт оборудования </option>
                                    <option @if(old('theme')=='Иное') selected @endif value="Иное" data-code="3" >Иное </option>


                                </select>
                                <span class="invalid-feedback">{{ $errors->first('theme') }}</span>
                                <span class="invalid-feedback" id="results"></span>
                                
                            </div>
                        </div>
                        
                        <div class="form-group required">
                        

                                <label class="control-label" for="theme">Текст обращения</label>
                            <div class="input-group">
                            <textarea name="message" placeholder="@lang('site::feedback.message')" rows="4"
                                      required
                                      class="mb-0 form-control {{ $errors->has('message') ? 'is-invalid' : '' }}">
                                      {{ old('message') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('message') }}</span>
                            </div>
                        </div>
                        
                        <div class="form-row required">
                            <div class="col">
                                <label class="control-label"
                                       for="captcha">@lang('site::register.captcha')</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text"
                                               name="captcha"
                                               required
                                               id="captcha"
                                               class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::register.placeholder.captcha')"
                                               value="">
                                        <span class="invalid-feedback">{{ $errors->first('captcha') }}</span>
                                    </div>
                                    <div class="col-md-6 captcha">
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
                        <p class="text-muted text-small">@lang('site::feedback.require')</p>
                        <div class="control-group form-group">
                            <button type="submit" class="btn btn-ms"><i
                                        class="fa fa-send"></i> @lang('site::messages.send')</button>
                        </div>
                        <div class="row"><div class="col">Нажимая кнопку отправить Вы соглашаетесь с нашей <a href="/up/c-politic.pdf">политикой конфиденциальности</a></div></div>

                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script>
try {
        window.addEventListener('load', function () {
                $('#theme').change(function(){
            var $selected = $(this).find(":selected");
            if ($selected.data("code")==1) {
          $('#results').addClass('d-block');
          $('#results').html($selected.data("message"));
          } else {
          $('#results').removeClass('d-block')
          }
        });
        
        });
    }catch (e) {
        console.log(e);
    }
</script>
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