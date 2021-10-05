@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="header-title mb-4 mt-3">
            @lang('site::user.confirm_phone')
        </h1>

        @alert()@endalert

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <form id="reg-form" method="POST" action="{{ route('register_success',$user) }}">
                            @csrf
                            <div class="form-row mb-3">
                            <div class="col">
                            На Ваш номер телефона {{$user->phone}} было отправлено СМС-сообщение с проверочным кодом.
                            </div>
                            </div>
                            <div class="form-row required">
                            <label class="control-label" for="phone_verify_code">@lang('site::user.phone_code')</label>
                                <div class="col-sm-2 mb-1">
                                    
                                    <input type="text"
                                           name="phone_verify_code"
                                           required
                                           id="phone_verify_code"
                                           minlength="6"
                                           maxlength="10"
                                           class="form-control{{ $errors->has('phone_verify_code') ? ' is-invalid' : '' }}"
                                           value="{{ old('phone_verify_code') }}">
                                        <span class="invalid-feedback">
                                <strong>{{ $errors->first('phone_verify_code') }}</strong>
                                
                            </span>

                                </div>
                            
                                <div class="col-sm-4 text-left">
                                    <button type="submit"  form="reg-form" class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <button type="submit" name="resend_sms" form="resend_sms" value="1" class="btn btn-secondary mb-1" >
                                        <i class="fa fa-check"></i>
                                        <span>Отправить смс повторно</span>
                                    </button>
                                   
                                </div>
                            </div>            
                    <div class="form-row required">
                        <div class="col">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="accept2" required value="1" class="custom-control-input"
                                       id="accept2" @if(old('accept2')==1) checked @endif>
                                <label class="custom-control-label" for="accept2"><span
                                            style="color:red;margin-right: 2px;">*</span>@lang('site::register.accept_sms')
                                </label>
                            </div>
                        </div>
                    </div>
                        </form>
                         <form id="resend_sms" method="POST" action="{{ route('register_success',$user) }}">
                            @csrf
                        </form>
                         
                    </div>
                </div>
                
                <div class="card mt-3 d-none">
                    <div class="card-body">
                    
                    
                        <form id="phone-update-form" method="POST" action="{{ route('register_phone_update',$user) }}">
                            @csrf
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="phone_contact_number">@lang('site::phone.number') <strong>@lang('site::phone.mobile')</strong></label>
                                    <div class="form-row">
                                    <div class="col-sm-3 mb-1">
                                    <input required
                                           type="tel"
                                           name="phone" form="phone-update-form"
                                           id="phone"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask search_phone form-control{{ $errors->has('phone') ? ' is-invalid' : (old('phone') ? ' is-valid' : '') }}"
                                           placeholder="@lang('site::phone.placeholder.number')"
                                           value="{{ old('phone') }}">
                                    <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                                    <span id="phone-exists" class="text-danger d-none">Номер телефона уже зарегистрирован на сайте. Проверьте правильность введенного номера или войдите на сайт по этому номеру.</span>
                                    <span id="phone-no-mobile" class="text-danger d-none">Номер телефона указан неверно, либо это не мобильный номер.</span>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" name="phone_update" form="phone-update-form" value="1" class="btn btn-secondary" >
                                            <i class="fa fa-check"></i>
                                            <span>Отправить смс</span>
                                        </button>
                                    </div>
                                    </div>
                                    <small id="emailHelp" class="form-text text-success">
                                        Для входа в личный кабинет будет использоваться номер Вашего телефона
                                    </small>
                                </div>
                    
                                    
                                
                            </div>
                        </form>
                    
                    
                    </div>
                </div>
                                         <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="align-text-bottom text-left text-success">
                                                    <b>Изменить номер телефона</b>
                                            </a>
                
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
                
            
        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush
