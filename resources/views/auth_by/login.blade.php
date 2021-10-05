@extends('layouts.app')
@section('title')@lang('site::user.login')@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => __('site::user.login'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::user.login')]
        ]
    ])
@endsection

@section('content')

    <div class="container">
        <div class="row pt-5 pb-5">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-body">
                        @alert()@endalert
                        <form method="POST" autocomplete="off" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group required {{ $errors->has('email') ? 'has-error':'' }}">
                                <label class="control-label" for="email">@lang('site::user.email')</label>
                                <input class="form-control {{ $errors->has('email') ? 'is-invalid':''}}"
                                       name="email"
                                       id="email"
                                       type="email"
                                       required autofocus
                                       aria-describedby="emailHelp" value="{{ old('email') }}"
                                       placeholder="@lang('site::user.placeholder.email')">
                                <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                            </div>
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
                            <div class="form-group account-btn text-site m-t-10">
                                <div class="col-xs-12">
                                    <button class="btn btn-ms" type="submit">@lang('site::user.sign_in')</button>
                                </div>
                            </div>
                        </form>
                        <div class="text-site">
                            <a class="d-block" href="{{route('password.request')}}">@lang('site::user.forgot')</a>
                            <a class="d-block" href="{{route('register')}}">@lang('site::user.sign_up')</a>
                        </div>
                    </div>
                </div>

            </div>
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
