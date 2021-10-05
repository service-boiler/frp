@extends('layouts.app')
@section('title')@lang('site::user.login')@lang('site::messages.title_separator')@endsection

@section('content')

    <div class="container main-wo-head">
        <div class="row">
            <div class="col-12 text-center">
                <h1>Вход для сотрудников и менеджеров.</h1>
            </div>
        </div>

        <div class="row pb-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        @alert()@endalert
                        @if(session('success') && session('success') != 'E-mail адрес подтвержден!') 
                        <span style="font-size: 1em; line-height: 1; text-align: center; color: red; font-weight: 600;">Обязательно подтвердите адрес электронной почты!<br />
                        Ссылка отправлена Вам на указанный адрес. Если Вы не получили письмо, проверьте папку "Спам".
                        </span>
                        
                        @else
                        <form method="POST" autocomplete="off" action="{{ route('login') }}">
                            @csrf
                        <div class="row mb-0"><div class="col">
                            <div class="form-group mb-0 required {{ $errors->has('login') ? 'has-error':'' }}">
                                <label class="control-label" for="login">@lang('site::user.phone') / @lang('site::user.email')</label> 
                                <input class="mb-0 form-control {{ $errors->has('login') ? 'is-invalid':''}}"
                                       name="login"
                                       id="login"
                                       type="text"
                                       
                                       aria-describedby="phoneHelp" value="{{ old('login') }}"
                                       placeholder="@lang('site::user.placeholder.login')">
                                <span class="invalid-feedback">{{ $errors->first('login') }}</span>
                                <span class="text-success small">@lang('site::user.help.phone_login').</span>
                                <span class="text-success small">@lang('site::user.help.phone_add_login')</span>
                            </div>
                        </div>
                       
                        
                        </div>
                        <div class="row mt-0"><div class="col">
                        
                        </div></div>
                            <div class="form-group required {{ $errors->has('password') ? 'has-error':'' }}">
                                <label class="control-label" for="password">@lang('site::user.password')</label>
                                <input name="password"
                                       id="password"
                                       class="form-control {{ $errors->has('password') ? 'is-invalid':''}}"
                                       required
                                       type="password"
                                       placeholder="@lang('site::user.placeholder.password')">

                                <span class="invalid-feedback">{{ $errors->first('password') }}</span>
                            </div>
                            <div class="form-group required">
                                <div class="custom-control custom-checkbox">
                                    <input name="remember" type="checkbox"
                                           {{ old('remember') ? 'checked':'' }}
                                           class="custom-control-input"
                                           id="remember">
                                    <label class="custom-control-label" for="remember">@lang('site::user.remember')</label>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
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
                                            <button  data-toggle="tooltip" data-placement="top" title="@lang('site::messages.refresh')" type="button" class="btn btn-outline-secondary" id="captcha-refresh">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>
                            <div class="row">
                            <div class="col-6">
                            <div class="form-group account-btn text-site m-t-10">
                                <div class="col-xs-12">
                                    <button class="btn btn-ms" type="submit">@lang('site::user.sign_in')</button>
                                </div>
                            </div>
                            </div>
                            
                            @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby']))
                                <div class="col-6">
                                
                                    <div class="text-right">
                                        <a  class="btn btn-green mb-2"  href="{{ route('register_esb') }}"><b>@lang('site::register.help.esb_btn')</b></a>
                                    </div>
                                </div>
                            @endif
                            </div>
                        </form>
                        
                        <div class="text-site">
                            <a class="d-block" href="{{route('password.request')}}">@lang('site::user.forgot')</a>

                        </div>
                        @endif
                    </div>
                    
                    
                </div>
                @if(in_array(env('MIRROR_CONFIG'),['sfru','sfby']))
                <div class="card">
                    <div class="card-body">
                        <div class="row pt-0">
                        
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <a  class="btn btn-ms mb-2"  href="{{ route('register_fl') }}"><b>@lang('site::register.help.fl_btn')</b></a>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <a  class="btn btn-ms mb-2"  href="{{ route('register_fls') }}"><b>@lang('site::register.help.fls_btn')</b></a>
                                </div>
                            </div>
                           
                            
                            <div class="col">
                            
                                <div class="text-center">
                                    <a  class="btn btn-ms mb-2"  href="{{ route('register') }}"><b>@lang('site::register.help.user_btn')</b></a>
                                </div>
                            </div>
                        
                        
                        </div>
                    </div>

                    
                </div>
            @endif
        </div>
    </div>
@endsection

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
