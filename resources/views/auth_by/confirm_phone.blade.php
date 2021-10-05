@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="header-title mb-4 mt-3">
            Восстановление пароля
        </h1>

        @alert()@endalert

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <div class="card">

                    <div class="card-body">
                        <form id="reg-form" method="POST" action="{{ route('password.reset_sms',$user) }}">
                            @csrf
                            <div class="form-row mb-3">
                            <div class="col">
                            На Ваш номер телефона {{$user->phone}} было отправлено СМС-сообщение с проверочным кодом.
                            <br />Введите новый пароль и код подтверждения из СМС.
                            </div>
                            </div>
                            
                            
                            <div class="form-row required">
                        <div class="col-sm-4">
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
                        <div class="col-sm-4">
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
                            <div class="col-sm-4 mb-1">
                            <label class="control-label" for="phone_verify_code">@lang('site::user.phone_code')</label>
                                
                                    
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
                            </div>
                            <div class="form-row">
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
                    
                        </form>
                         <form id="resend_sms" method="POST" action="{{ route('password.resend_sms',$user) }}">
                            @csrf
                        </form>
                         
                    </div>
                </div>
                
                    
            </div>
        </div>
    </div>
@endsection
