@extends('layouts.app')
@section('title')@lang('site::feedback.feedback_client')@lang('site::messages.title_separator')@endsection

@section('header')
    @include('site::header.front',[
        'h1' => 'Обратная связь',
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::feedback.feedback_client')]
        ]
    ])
@endsection
@section('content')

    <section>
        <div class="container"><a name="form"></a>
            @alert()@endalert
           
            <div class="row">
            <div class="col-sm-6 col-lg-5">
                    <h4>@lang('site::feedback.feedback_client')</h4>
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
                                       value="{{ old('name',$client ? $client->name : null) }}">
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
                                       value="{{ old('email',$client ? $client->email : null) }}">
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
                                           value="{{ old('phone',$client ? $client->phone : null) }}"
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
                                  
                                        
                                    
                                    <option @if(old('theme')=='6') selected @endif value="6" data-code="2" >Оптовые продажи и партнерство</option>
                                    <option @if(old('theme')=='9') selected @endif value="9" data-code="2" >Купить котел и другое оборудование</option>
                                    <option @if(old('theme')=='7') selected @endif value="7" data-code="1" 
                                        data-message="Зап.части реализуются только через сервисные центры!<br />Cписок Вы можете найти разделе <br /><a href='/services'>АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a>
                                        <br />Пожалуйста, обратитесть в авторизованный сервсный центр. ">Купить запасные части</option>
                                    <option @if(old('theme')=='8') selected @endif value="8" data-code="1" data-message="Пожалуйста, обратитесть в авторизованный сервсный центр. Cписок Вы можете найти разделе <a href='/services'>АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a>">
                                        Сервисные центры, ремонт оборудования </option>
                                    
                                    <option @if(old('theme')=='5') selected @endif value="5" data-code="1" 
                                        data-message="Пожалуйста, обратитесть в авторизованный сервсный центр. <br />Cписок Вы можете найти разделе <br />
                                        <a href='/services'>АВТОРИЗОВАННЫЕ СЕРВИСНЫЕ ЦЕНТРЫ.</a>">Технические вопросы</option>
                                        
                                    <option @if(old('theme')=='10') selected @endif value="6" data-code="3" >Консультация по работе и подбору оборудования </option>
                                    @if(!empty($service))<option @if(old('theme',$theme)=='12') selected @endif value="12" data-code="3" >Отзыв о сервисном центре</option>@endif
                                    <option @if(old('theme')=='11') selected @endif value="11" data-code="3" >Иное </option>


                                </select>
                                <span class="invalid-feedback">{{ $errors->first('theme') }}</span>
                                <span class="invalid-feedback text-big" id="results"></span>
                                
                            </div>
                        </div>
                        @if(!empty($service))
                            <div class="form-group required">
                                <input type="hidden" name="service_id" value="{{$service->id}}">
                                    <label class="control-label" for="theme">Сервисный центр:</label>
                                <div class="input-group ml-3">
                                    {{$service->public_name}}
                                    
                                </div>
                            </div>
                        
                            <div class="form-group required">
                                <div class="input-group">
                                    <div class="form-row required">
                                        <div class="col">
                                           
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="custom-control-input
                                                    {{$errors->has('claim_type') ? ' is-invalid' : ''}}"
                                                       type="radio"
                                                       name="claim_type"
                                                       required
                                                       @if(old('claim_type') == 1) checked
                                                       @endif
                                                       id="claim_type_1"
                                                       value="1">
                                                <label class="custom-control-label"
                                                       for="claim_type_1">Жалоба, претензия, срочное обращение</label>
                                            </div>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input class="custom-control-input
                                                    {{$errors->has('claim_type') ? ' is-invalid' : ''}}"
                                                       type="radio"
                                                       name="claim_type"
                                                       required
                                                       @if(old('claim_type') == 0) checked
                                                       @endif
                                                       id="claim_type_0"
                                                       value="0">
                                                <label class="custom-control-label"
                                                       for="claim_type_0">Отзыв о работе, благодарность.</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
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
            <div class="row">
                <div class="col-sm-6 col-lg-6 mb-5">
                     
                    <p class="mb-5"></p>
                    <p>ООО «ФерролиРус»</p>
                    <p>141009, Московская обл, г Мытищи, Ярославское шоссе, влд 1 стр 1</p>
                    <hr/>
                    <p>Почтовый адрес: @lang('site::feedback.post_address')</p>
                    <hr/>
                   
                </div>
                <div class="d-none d-lg-block col-lg-1"></div>
                
            </div>
            @alert()@endalert
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