@extends('layouts.app')
@section('title')@lang('site::product.products_lp_page_title')@lang('site::messages.title_separator')@endsection
@section('header')

@endsection
@section('content')
<section class="lp-mp pt-0">
    <div class="container pl-sm-5 pl-lg-4 pl-xl-3">
        <div class="row lp-market-products-head">
            <div class="lp-market-products-left-head d-block">
                <h3 class="mb-3">Как заказать оборудование?</h3>
                <img class="d-none d-md-block" src="/images/lp/wbuy123.svg">
                <a href="#btns">
                <img class="d-sm-block d-md-none" style="width: 320px;" src="/images/lp/wbuy123m2.svg">
                </a>
            </div>


            <div class="lp-market-products-right-head d-none d-md-inline-block">
                <div class="d-flex">
                    <div class="lp-mp-hr-2">
                        <h3>ИЩЕТЕ <br />ГДЕ КУПИТЬ?</h3>
                    </div>
                    <div class="lp-mp-hr-3">
                        <img src="/images/lp/worker1.svg">
                    </div>
                </div>
                <div class="lp-mp-hr-4 d-block">
                    Обратитесь к официальному дилеру Ferroli
                </div>
                <div class="d-block">
                    <a href="{{route('where-to-buy')}}" class="btn btn-ms lp-mp-hr-btn">Выбрать дилера!</a>
                </div>
            </div>
        </div>
        <div class="row mt-md-5" id="btns">
            <h3 class="mb-3 ml-3 ml-md-0">ПОДБЕРИТЕ КОТЕЛ ПО ПАРАМЕТРАМ:</h3>
        </div>
        <div class="row pr-2 pl-3 pl-sm-0">
            <div class="col-12 col-md-6 pr-4 pr-md-5">
                <div class="row">
                    <div class="col-12 p-0 mt-3 h5 vals" id="fuel">Тип отопительного котла <div class="d-lg-none d-block"></div>(вид топлива):</div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn w-100 lp-mp-btn btn-lp-check-in-active"
                           data-filtername="fuel" data-filterval="Газ"
                           id="gas" data-uncheck="elektro"> Газовый </a>
                    </div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn w-100 lp-mp-btn btn-lp-check-in-active"
                           data-filtername="fuel" data-filterval="Электричество"
                           id="elektro" data-uncheck="gas"> Электрический </a>
                        <div class="err text-danger text-center d-none" id="err-el"></div>
                    </div>
                    <div class="col-12 p-0 m-0 text-center"><small class="rng">Обязательный параметр</small></div>
                </div>
            </div>
            <div class="col-12 col-md-6  pr-4 pr-md-5">
                <div class="row" id="circles">
                    <div class="col-12 p-0 mt-3 h5 vals">Количество контуров в котле:</div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="circles" data-filterval="2"
                           id="twocircle" data-uncheck="onecircle"> Отопление и горячая вода <br />(двухконтурный) </a>
                    </div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="circles" data-filterval="1"
                           id="onecircle" data-uncheck="twocircle"> Только отопление <br />(одноконтурный) <div class="d-lg-none d-block"><br/></div> </a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-6  pr-4 pr-md-5 mt-md-5">
                <div class="row vals" id="mount">
                    <div class="col-12 p-0 mt-3 h5">Тип размещения котла:</div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="mount" data-filterval="Настенный"
                           id="wall" data-uncheck="floor" >
                            Настенный</a>
                    </div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="mount" data-filterval="Напольный"
                           id="floor" data-uncheck="wall" >
                            Напольный </a>
                    </div>
                </div>
            </div>

        <div class="col-12 col-md-6  pr-4 pr-md-5 mt-md-5">
            <div class="row  vals" id="combust">
                <div class="col-12 p-0 mt-3 h5">Дымоход(камера сгорания)</div>
                <div class="col-6 p-0">
                    <a href="javascript:void('')" class="btn lp-mp-btn hg4 w-100 btn-lp-check-in-active"
                       data-filtername="combust" data-filterval="Закр."
                       id="turbo" data-uncheck="atmo">
                        Через стену <br />(закрытая камера сгорания) <div class="d-xl-none d-block"><br/></div> </a>
                </div>
                <div class="col-6 p-0">
                    <a href="javascript:void('')" class="btn lp-mp-btn hg4 w-100 btn-lp-check-in-active"
                       data-filtername="combust" data-filterval="Откр."
                       id="atmo" data-uncheck="turbo">
                        В шахту дымоудаления <br />(открытая камера сгорания) </a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6  pr-4 pr-md-5 mt-md-5">
            <div class="row vals" id="pwr">
                <div class="col-6 p-0 mt-3 h5">Мощность котла</div>
                <div class="col-6 p-0 mt-3 text-right rng">
                        <a href="javascript:void('')" id="single-pwr-btn" class="power-field">Точное значение</a>
                        <a href="javascript:void('')" id="range-pwr-btn" class="power-field d-none">Диапазон значений</a>
                </div>
                <div id="powerSlider" class="col-12 mt-2 power-slider"></div>
                <fieldset class="col-12 lp-mp-input lp-mp-input-wide power-field d-none">
                    <input  type="number" name="power" value="" id="power-input"><span>кВт</span>
                </fieldset>
                <div class="col-6 p-0 mt-3 pl-3 power-slider">от <span  id="power-lower"></span></div>
                <div class="col-6 p-0 mt-3 pr-3 text-right power-slider">до <span  id="power-upper"></span></div>
                <div class="col-12 p-0 mt-3 text-center"><small class="rng">Обязательный параметр</small></div>

            </div>
        </div>


        <div class="col-12 col-md-6  pr-4 pr-md-5 mt-5">
            <div class="row  vals" id="price">
                <div class="col-6 p-0 mt-3 h5">Стоимость</div>
                <div class="col-6 p-0 mt-3 text-right rng">
                    <a href="javascript:void('')" id="single-price-btn" class="price-field">Точное значение</a>
                    <a href="javascript:void('')" id="range-price-btn" class="price-field d-none">Линейка</a>
                </div>
                <fieldset class="col-6 lp-mp-input price-field d-none " >
                    <label for="price_min">От:</label> <input  type="number" name="price_min" id="price_min"
                                                              value=""><span>р.</span>
                </fieldset>
                <fieldset class="col-6 lp-mp-input price-field d-none" >
                    <label for="price_max">До:</label> <input  type="number" name="price_max" id="price_max"
                                                              value=""><span>р.</span>
                </fieldset>
                <div id="priceSlider" class="col-12 mt-2 price-field"></div>
                <div class="col-6 p-0 mt-3 pl-3 price-field">от <span  id="price-lower"></span></div>
                <div class="col-6 p-0 mt-3 pr-3 price-field text-right">до <span  id="price-upper"></span></div>
            </div>
        </div>

            <div class="col-12 col-md-6  pr-4 pr-md-5 mt-5">
                <div class="row  vals" id="area">
                    <div class="col-12 p-0 mt-3 h5">Площадь отопления</div>
                    <fieldset class="col-6 lp-mp-input" >
                        <label for="area_min">От:</label> <input  type="number" name="area_min" id="area_min"
                                                               value=""><span>м²</span>
                    </fieldset>
                    <fieldset class="col-6 lp-mp-input" >
                        <label for="area_min">До:</label> <input  type="number" name="area_max" id="area_max"
                                                                  value=""><span>м²</span>
                    </fieldset>
                </div>
            </div>

            <div class="col-12 col-md-6  pr-4 pr-md-5 mt-md-5 vals d-none" id="waterclosets">
                <div class="row">
                    <div class="col-12 p-0 mt-3 h5">Количество точек водоразбора (санузлов):</div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="wccount" data-filterval="1"
                           id="smallwc" data-uncheck="bigwc"> от 1 до 3 санузлов </a>
                    </div>
                    <div class="col-6 p-0">
                        <a href="javascript:void('')" class="btn lp-mp-btn w-100 btn-lp-check-in-active"
                           data-filtername="wccount" data-filterval="3"
                           id="bigwc" data-uncheck="smallwc"> 3 и более санузлов</a>
                    </div>
                </div>
            </div>

            <div class="row d-none mt-5 mt-md-3" id="boilers">

                <div class="col-12 text-center mt-3 text-ferroli h5 d-none" id="onewc">Для комфортного горячего водоснабжения рекомендуем приобрести бойлер косвенного нагрева</div>
                <div class="col-12 text-center mt-3 text-ferroli h5 d-none" id="manywc">Для помещений с 3 и более точками водоразбора для комфортного горячего водоснабжения рекомендуем
                    приобрести бойлер косвенного нагрева</div>
                <div class="col-12 text-center font-weight-bold h5">Объем бойлера:</div>
                <div class="col-12 text-center">
                    <a href="javascript:void('')" class="btn mb-2 lp-mp-btn-vol btn-lp-check-in-active" data-vol-min="0" data-vol-max="100">100 литров</a>
                    <a href="javascript:void('')" class="btn mb-2 lp-mp-btn-vol btn-lp-check-in-active" data-vol-min="0" data-vol-max="150">150 литров</a>
                    <a href="javascript:void('')" class="btn mb-2 lp-mp-btn-vol btn-lp-check-in-active" data-vol-min="160" data-vol-max="200" id="minboiler">200 литров</a>
                    <a href="javascript:void('')" class="btn mb-2 lp-mp-btn-vol btn-lp-check-in-active" data-vol-min="220" data-vol-max="300">300 литров</a>
                    <a href="javascript:void('')" class="btn mb-2 lp-mp-btn-vol btn-lp-check-in-active" data-vol-min="220" data-vol-max="400">400 литров</a>
                    <a href="javascript:void('')" class="btn mb-2 btn-lp-check-in-active" id="remove_boiler">Бойлер не нужен</a>
                </div>

            </div>
            <div class="col-12 col-md-6  pr-4 pr-md-5 mt-md-5">
                <div class="row">
                    <div class="col-12 pl-0 pt-5 mb-4" id="find">
                        <a href="javascript:void('')" class="btn btn-lp-wide lp-mp-btn-find btn-ms mb-2">Подобрать котел</a>
                        <a href="javascript:void('')" class="btn btn-lp-wide lp-mp-btn-clear btn-secondary mb-2 ml-md-3">Удалить параметры поиска</a>
                    </div>
                </div>
            </div>



        </div>
        
    </div>
</section>
<div class="container">


    <div class="row" id="product_list">

    </div>
    <div class="row" id="boiler_list">

    </div>

    <div class="row" id="product_list_end">
        <div class="col-6 pr-4 mb-5 d-none d-md-inline-block">
            <div class="lp-market-products-card d-inline-block w-100 lp-mp">
                <div class="d-flex">
                    <div class="lp-mp-card-2">
                        <h3>ИЩЕТЕ ГДЕ ОБСЛУЖИВАТЬ<br>ВАШ КОТЕЛ?</h3>
                    </div>
                    <div class="lp-mp-card-3">
                        <img src="/images/lp/fix1.svg">
                    </div>
                </div>
                <div class="lp-mp-hr-4 d-block">
                    В официальном сервисном<br/> центре Ferroli
                </div>
                <div class="d-block">
                    <a href="{{route('service-centers')}}" class="btn btn-ms lp-mp-card-btn">Выбрать сервисный центр</a>
                </div>
            </div>
        </div>

        <div class="col-6 pr-5 pl-0 mb-5 d-none error-card" id="errors-all">

        </div>
        <div class="text-center d-none" id="wait_request">

            <img src="/images/l3.gif" style="max-height: 150px; margin: 70px;">

        </div>


    </div>


</div>


    <div class="container p-0 pb-1">
        <div class="title-block hide-on-mobile p-0 mb-3">

                <div class="img-block p-0">
                    <a href="{{route('catalogs.index')}}"><img src="images/lp/lp1-catalog.jpg" alt=""></a>


            </div>
        </div>
        <div class="d-sm-none text-center">
            {{--
            <div class="img-block p-0 mb-3">
                <a  style="width: 100%;" href="{{route('catalogs.index')}}"><img style="width: 100%;" src="images/lp/catalog-m-footer.jpg" alt=""></a>


            </div>
            --}}
            <a href="{{route('cart')}}" class="btn btn-ms mb-3 d-block"><i class="fa fa-2x ti-shopping-cart-full"></i></a>
            <a href="{{route('catalogs.index')}}" class="btn btn-ms mb-3 d-block">ПОЛНЫЙ КАТАЛОГ ОБОРУДОВАНИЯ</a>
            <a href="{{route('where-to-buy')}}" class="btn btn-ms mb-3 d-block">ДИЛЕРЫ И МАГАЗИНЫ</a>
            <a href="{{route('service-centers')}}" class="btn btn-ms mb-3 d-block">СЕРВИСНЫЕ ЦЕНТРЫ</a>
            <a href="{{route('feedback')}}" class="btn btn-ms mb-3 d-block">ОБРАТНАЯ СВЯЗЬ</a>

        </div>

    </div>

@endsection
{{--
@push('styles')
    <link href="/css/temp.css?v=12317" rel="stylesheet">

@endpush
--}}

@push('scripts')
<script src="/js/nouislider.min.js"></script>
<script src="/js/wNumb.min.js"></script>
<script>
        try {
            window.addEventListener('load', function () {

                var filters = new Map();
                $(document)
                        .on('click', '.lp-mp-btn', (function(I){
                            
                            $(this).addClass('btn-lp-check-active');
                            $(this).removeClass('btn-lp-check-in-active');
                            $('#'+$(this)[0].dataset.uncheck).addClass('btn-lp-check-in-active');
                            $('#'+$(this)[0].dataset.uncheck).removeClass('btn-lp-check-active');
                            filters.set($(this)[0].dataset.filtername,$(this)[0].dataset.filterval);


                            switch($(this)[0].id) {
                                case 'elektro':
                                    hide_block($('#mount')[0]);
                                    hide_block($('#combust')[0]);
                                    hide_block($('#circles')[0]);
                                    filters.delete('mount');
                                    filters.delete('combust');
                                    filters.delete('circles');

                                    break;
                                case 'gas':
                                    act_gas();
                                    break;
                                    
                                case 'floor':

                                    $('#mount').attr('data-filterval','Напольный');
                                    break;
                                    
                                case 'wall':
                                    $('#mount').attr('data-filterval','Настенный');
                                    break;

                                case 'bigwc':
                                    $('#boilers').removeClass('d-none');
                                    $('#manywc').removeClass('d-none');
                                    $('#onewc').addClass('d-none');
                                    $('#minboiler').removeClass('btn-lp-check-in-active').addClass('btn-lp-check-active');
                                    filters.set('boiler_vol_min',$('#minboiler')[0].dataset.volMin);
                                    break;

                                case 'smallwc':
                                    $('#boilers').addClass('d-none');
                                    $('#manywc').addClass('d-none');
                                    $('#onewc').removeClass('d-none');
                                    break;

                                case 'twocircle':
                                    $('#circles').attr('data-filterval','2');
                                    $('#waterclosets').removeClass('d-none');
                                    $('#boilers').addClass('d-none');
                                    break;
                                    
                                case 'onecircle':
                                    $('#circles').attr('data-filterval','1');
                                    $('#waterclosets').addClass('d-none');
                                    $('#boilers').removeClass('d-none');
                                    $('#manywc').addClass('d-none');
                                    $('#onewc').removeClass('d-none');
                                    break;
                                
                                case 'turbo':

                                    $('#combust').attr('data-filterval','закр.');
                                    break;
                                    
                                case 'atmo':

                                    $('#combust').attr('data-filterval','откр.');
                                    break;
                            }

                            get_errors();
                            console.log(filters);
                               
                        }))


                        .on('click', '.lp-mp-btn-find', (function(I){
                            get_errors();

                        }))
                        .on('click', '#single-pwr-btn', (function(I){
                            $('.power-slider').toggleClass('d-none');
                            $('.power-field').toggleClass('d-none');

                        }))
                        .on('click', '#range-pwr-btn', (function(I){
                            $('.power-slider').toggleClass('d-none');
                            $('.power-field').toggleClass('d-none');

                        }))
                        .on('click', '#single-price-btn', (function(I){
                            $('.price-field').toggleClass('d-none');

                        }))
                        .on('click', '#range-price-btn', (function(I){
                            $('.price-field').toggleClass('d-none');

                        }))

                        .on('click', '.lp-mp-btn-vol', (function(I){
                            console.log($(this)[0].dataset.volMin);
                            $('.lp-mp-btn-vol').removeClass('btn-lp-check-active').addClass('btn-lp-check-in-active');
                            $(this).removeClass('btn-lp-check-in-active').addClass('btn-lp-check-active');
                            filters.set('boiler_vol_min',$(this)[0].dataset.volMin);
                            filters.set('boiler_vol_max',$(this)[0].dataset.volMax);
                            get_errors();

                        }))

                        .on('click', '#remove_boiler', (function(I){
                            $('#boilers').addClass('d-none');
                            filters.delete('boiler_vol_min');
                            filters.delete('boiler_vol_max');
                            get_errors();

                        }))
                       
                let get_products = function () {

                    let product_list=$('#product_list');
                    let boiler_list=$('#boiler_list');
                    if(filters.get('pwr_min') && filters.get('fuel')) {
                        let params='';

                        product_list.html('');
                        boiler_list.html('');
                        $('#wait_request').removeClass('d-none');

                        for (let key of filters.keys()) {
                            params=params + 'filter[' + key + ']=' + filters.get(key) + '&'
                        }

                            axios
                                .get("/api/products-lp?" + params)
                                .then((response) => {
                                    product_list.html(response.data);
                                    $('#wait_request').addClass('d-none');

                                })
                                .catch((error) => {
                                    this.status = 'Error:' + error;
                                });

                            if(filters.get('boiler_vol_min')) {
                                axios
                                    .get("/api/products-lp-boilers?filter[is_boiler]=1&filter[boiler_vol_min]=" + filters.get('boiler_vol_min')+"&filter[boiler_vol_max]=" + filters.get('boiler_vol_max'))
                                    .then((response) => {
                                        boiler_list.append(response.data);
                                    })
                                    .catch((error) => {
                                        this.status = 'Error:' + error;
                                    });
                            }
                        }
                }

                let get_errors = function () {
                    $('.err').addClass('d-none');
                    $('#errors-all').addClass('d-none');
                    $errors='';

                    if(!filters.get('pwr_min') && !filters.get('fuel')) {
                        $errors='Обязательно нужно выбрать: <br /> тип топлива  <br />мощность котла или площадь отопления';
                        $('#errors-all').removeClass('d-none').html($errors);

                    } else {
                        if(!filters.get('pwr_min')) {
                            filters.set('pwr_min', 7);
                            filters.set('pwr_max', 100);
                        }
                        if(!filters.get('fuel')) {
                            filters.set('fuel', 'Газ');
                            act_gas();
                        }


                    }


                    if(filters.get('circles')=='2' && filters.get('fuel')=='Электричество' ) {
                            $errors='Нет электрических двухконтурных котлов, - только одноконтурные ';
                            $('#err-el').removeClass('d-none').html($errors);
                            $('#errors-all').removeClass('d-none').html($errors);
                             $('#waterclosets').addClass('d-none');
                             $('#onecircle').addClass('btn-lp-check-active');
                             $('#twocircle').removeClass('btn-lp-check-active');
                             $('#twocircle').addClass('btn-lp-check-in-active');
                             $('#onecircle').removeClass('btn-lp-check-in-active');
                             $('#circles').attr('data-filterval','2');

                    }

                    if($('#pwr')[0].dataset.powerMin>25 && $('#fuel')[0].dataset.filterval=='Электричество' ) {
                            $errors='Нет электрических котлов мощнее 24 кВт';
                            $('#err-el').removeClass('d-none').html($errors);
                            $('#errors-all').removeClass('d-none').html($errors);
                        }
                    if($('#pwr')[0].dataset.powerMin>40 && $('#mount')[0].dataset.filterval=='Настенный'  && $('#typeheat')[0].dataset.filterval=='Конвекционный' ) {
                            $errors='Нет настенных традиционных котлов мощнее 40 кВт, - только конденсационные';
                            $('#err-el').removeClass('d-none').html($errors);
                            $('#errors-all').removeClass('d-none').html($errors);
                        }
                    if($('#pwr')[0].dataset.powerMax && $('#pwr')[0].dataset.powerMax<30 && $('#mount')[0].dataset.filterval=='Напольный' && $('#circles')[0].dataset.filterval=='2' ) {
                            $errors='Нет напольных двухконтурных котлов до 30 кВт. Есть Pegasus DK со встроенным бойлером мощностью от 32 кВт';
                            $('#err-circles').removeClass('d-none').html($errors);
                            $('#err-mount').removeClass('d-none').html($errors);
                            $('#errors-all').removeClass('d-none').html($errors);
                        }
                    if(($('#combust')[0].dataset.filterval=='закр.' || $('#combust')[0].dataset.filterval=='откр.') && $('#fuel')[0].dataset.filterval=='Электричество' ) {
                            $errors='У электрического котла нет камеры сгорания. Предлагаем Вам газовые.';
                            $('#err-el').removeClass('d-none').html($errors);
                            $('#errors-all').removeClass('d-none').html($errors);
                            act_gas();
                        }

                        get_products();


                };
                
                
                let hide_block = function (block) {
                    block.setAttribute('data-filterval','');
                    if(block.getElementsByClassName('btn-lp-check-active')[0]) {
                        block.getElementsByClassName('btn-lp-check-active')[0].classList.add('btn-lp-check-in-active');
                        block.getElementsByClassName('btn-lp-check-active')[0].classList.remove('btn-lp-check-active');
                    }
                        block.style.opacity = 0.3
                            block.addEventListener('transitionend', () => {
                                  //block.hidden = true
                                }, {once: true})
                };
                       
                let unhide_block = function (block) {
                   
                    block.style.opacity = 1;
                    block.hidden = false;
                    
                };   
                
                let act_gas = function () {

                    filters.set('fuel','Газ')
                    unhide_block($('#mount')[0]);
                    unhide_block($('#circles')[0]);
                    unhide_block($('#combust')[0]);
                    $('#elektro').addClass('btn-lp-check-in-active').removeClass('btn-lp-check-active');
                    $('#gas').addClass('btn-lp-check-active').removeClass('btn-lp-check-in-active');
                    console.log(filters);
                    
                };


                $(document).ready(function() {

                    var powerSlider = document.getElementById('powerSlider');
                    var priceSlider = document.getElementById('priceSlider');
@if(in_array(env('MIRROR_CONFIG'),['sfru','marketru']))
                    filters.set('price_min',20000);
                    filters.set('price_max',300000);
@elseif(in_array(env('MIRROR_CONFIG'),['marketby','sfby']))
                    filters.set('price_min',500);
                    filters.set('price_max',5000);

@endif
                    var FormatPower=wNumb({
                        decimals: 0, suffix: ' кВт'
                    });
                    var FormatPrice=wNumb({
                        decimals: 0, thousand: ' ',suffix: ' p.'
                    });

                    noUiSlider.create(powerSlider, {
                        start: [7, 100],
                        step: 1,
                        connect: true,
                        behaviour: 'tap',
                        tooltips: false,
                        format: wNumb({
                            decimals: 0, suffix: ' кВт'
                        }),
                        range: {
                            'min': [6],
                            '5%': [7],
                            '80%': [100, 20],
                            'max': [500]
                        }
                    });
@if(in_array(env('MIRROR_CONFIG'),['sfru','marketru']))
                    noUiSlider.create(priceSlider, {
                        start: [20000, 300000],
                        step: 2000,
                        connect: true,
                        behaviour: 'tap',
                        tooltips: false,
                        format: wNumb({
                            decimals: 0, thousand: ' ',suffix: ' p.'
                        }),
                        range: {
                            'min': [18000],
                            '7%': [20000,1000],
                            '50%': [40000,2000],
                            '80%': [300000, 50000],
                            'max': [1000000]
                        }
                    });
@elseif(in_array(env('MIRROR_CONFIG'),['marketby','sfby']))
noUiSlider.create(priceSlider, {
    start: [500, 5000],
    step: 20,
    connect: true,
    behaviour: 'tap',
    tooltips: false,
    format: wNumb({
        decimals: 0, thousand: ' ',suffix: ' p.'
    }),
    range: {
        'min': [200],
        '7%': [700,20],
        '50%': [1000,100],
        '80%': [3000, 500],
        'max': [20000]
    }
});
@endif

                    priceSlider.noUiSlider.on('change', function (values, handle, unencoded, isTap, positions) {
                        filters.set('price_min',FormatPrice.from(values[0]));
                        filters.set('price_max',FormatPrice.from(values[1]));
                        $('#price_min').val(filters.get('price_min'));
                        $('#price_max').val(filters.get('price_max'));
                        get_errors();


                    });

                    priceSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
                        $('#price-lower').html(values[0]);
                        $('#price-upper').html(values[1]);

                    });


                    powerSlider.noUiSlider.on('change', function (values, handle, unencoded, isTap, positions) {
                        filters.set('pwr_min',FormatPower.from(values[0]));
                        filters.set('pwr_max',FormatPower.from(values[1]));
                        $('#area_min').val(FormatPower.from(values[0])*10);
                        $('#area_max').val(FormatPower.from(values[1])*10);

                        $('#power-input').val(filters.get('pwr_min'));
                        get_errors();


                    });

                    powerSlider.noUiSlider.on('update', function (values, handle, unencoded, isTap, positions) {
                        $('#power-lower').html(values[0]);
                        $('#power-upper').html(values[1]);

                    });

                    $('#price_min').on('change', function () {
                        filters.set('price_min',$(this).val());
                        priceSlider.noUiSlider.set([$(this).val(), null]);
                        if(filters.get('price_min')>filters.get('price_max')){
                            filters.set('price_max',filters.get('price_min'));
                            priceSlider.noUiSlider.set([null,$(this).val()]);
                            $('#price_max').val(filters.get('price_min'));
                        }


                    });

                    $('#price_max').on('change', function () {
                        filters.set('price_max',$(this).val());
                        priceSlider.noUiSlider.set([null,$(this).val()]);
                        if(filters.get('price_min')<filters.get('price_max')){
                            filters.set('price_min',filters.get('price_max'));
                            priceSlider.noUiSlider.set([$(this).val(),null]);
                            $('#price_min').val(filters.get('price_max'));
                        }
                    });

                    $('#power-input').on('change', function () {
                        filters.set('pwr_max',$(this).val());
                        filters.set('pwr_min',$(this).val());
                        powerSlider.noUiSlider.set([$(this).val(),$(this).val()]);

                    });

                    $('#area_min').on('change', function () {
                        filters.set('pwr_min',$(this).val()/10);
                        powerSlider.noUiSlider.set([$(this).val()/10, null]);
                        if(Number($(this).val())>1000){
                            powerSlider.noUiSlider.set([$(this).val()/10, 500]);

                        }

                    });


                    $('#area_max').on('change', function () {
                        filters.set('pwr_max',$(this).val()/10);
                        powerSlider.noUiSlider.set([null, $(this).val()/10]);

                        if(Number($(this).val())<Number($('#area_min').val())){
                            powerSlider.noUiSlider.set([$(this).val()/10-10,$(this).val()/10]);
                            $('#area_min').val($(this).val()-100)

                        }

                    });
            $(document)
                .on('click', '.lp-mp-btn-clear', (function(I){
                        $('.lp-mp-btn').addClass('btn-lp-check-in-active').removeClass('btn-lp-check-active');
                        $('#boilers').addClass('d-none');
                        $('#waterclosets').addClass('d-none');
                        $('#product_list').html('');
                        $('#boiler_list').html('');
                        powerSlider.noUiSlider.set([7, 100]);
                        priceSlider.noUiSlider.set([20000, 300000]);
                        $('#area_min').val('');
                        $('#area_max').val('');
                        filters.clear();


                    }));






                });
    
    });



        } catch (e) {
        console.log(e);
    }

</script>
@endpush