@extends('layouts.app')

@section('title'){{$webinar->name}}@lang('site::messages.title_separator')@endsection

@section('content')
    <div class="container">
    
          
        <div class="row my-5">
        
            <div class="col-md-12 news-content">
                <div class="card-body">
                        <h1>{{$webinar->name}}</h1>
                
                
                    <dl class="row">

                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.name')</dt>
                        <dd class="col-sm-8">{{ $webinar->name }}</dd>
                                                                           
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.theme')</dt>
                        <dd class="col-sm-8">{{$webinar->theme->name}}</dd>
                        @auth()
                        <dt class="col-sm-4 text-left text-sm-right">Баллы:</dt>
                        <dd class="col-sm-8">@if(!empty($webinar->promocode)) {{ $webinar->promocode->bonuses }} @lang('site::admin.bonus_val')
                                            @elseif(!empty($webinar->theme->promocode))
                                            {{ $webinar->theme->promocode->bonuses }} @lang('site::admin.bonus_val')
                                            @endif
                                             </dd> 
                                            
                        @endauth()
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.webinar.datetime')</dt>
                        <dd class="col-sm-8"> {{ $webinar->datetime ? $webinar->datetime->format('d.m.Y H:i') : 'дата не указана'  }}</dd>
                        
                        
                        <dt class="col-sm-4 text-left text-sm-right">
                        @include('site::admin.image.card', ['image' => $webinar->image])
                        </dt>
                        <dd class="col-sm-8">
                        <div class="bg-lightest font-italic pb-2 text-big">{!! $webinar->annotation !!}</div>
                        <br />
                        {!! $webinar->description !!}
                        </dd>
                        @auth()
                        <dt class="col-sm-4 text-left text-sm-right mt-2"><a href="{{ route('webinars.enter',$webinar) }}" class="d-block d-sm-inline-block btn btn-green mb-4">
                                    <i class="fa fa-hand-o-right"></i> 
                                    <span>@lang('site::admin.webinar.enter')</span>
                            </a></dt>
                        <dd class="col-sm-8 mt-2"> 
                            @if(Auth()->user()->type_id==3)
                                <button type="submit" form="webinar-register-form-{{$webinar->id}}" 
                                    
                                    class="btn btn-ms d-block d-sm-inline  mb-4">
                                    <i class="fa fa-check-square-o"></i> <span class="d-none d-sm-inline-block">@lang('site::admin.webinar.register')</span>
                                </button>
                            @endif
                            <a href="{{ route('webinars.index') }}" class="d-block d-sm-inline-block btn btn-outline-ferroli mb-4 ml-1 mr-0">
                                    <span>Показать другие вебинары</span>
                            </a>
                            @if(Auth()->user()->type_id!=3)
                            <br />Для учета посещений Вами вебинаров Вы должны войти на сайт как физическое лицо.
                            @endif
                            <form id="webinar-register-form-{{$webinar->id}}"
                                    action="{{route('webinars.register', $webinar)}}"
                                    method="POST">
                                 @csrf
                                 @method('POST')
                                 
                            </form>
                        </dd>
                        @else()
                        <!--
                        <dt class="col-sm-4 text-left text-sm-right mt-2">
                        </dt>
                        <dd class="col-sm-8 mt-2">
                        Зарегистрированным пользователям за участие в вебинарах начисляются баллы, войдите под своим логином/паролем, или зарегистрируйтесь
                        
                        </dd>
                        -->
                        <dt class="col-sm-4 text-left text-sm-right mt-2">
                        </dt>
                        <dd class="col-sm-8 mt-2">
                         <a href="{{ route('login') }}" class="d-block d-sm-inline-block btn btn-ms mb-4 ml-1 mr-0">
                                    <span>Войти</span>
                            </a>
                             <a href="{{ route('register_flm') }}" class="d-block d-sm-inline-block btn btn-ms mb-4 ml-1 mr-0">
                                    <span>Зарегистрировать личный кабинет</span>
                            </a>
                            
                            <!-- <a href="javascript:void(0);" data-form-action="{{route('events.webinars.public_join_form',$webinar)}}" data-label="Для входа в вебинар необходимо представиться" 
                                class="dynamic-modal-form-card d-block d-sm-inline-block btn btn-outline-ferroli mb-4 ml-1 mr-0">
                             Открыть вебинар без регистрации
                                </a>
                            -->
                             <a href="{{$webinar->link_service}}"
                                class="d-block d-sm-inline-block btn btn-outline-ferroli mb-4 ml-1 mr-0">
                             Открыть вебинар без регистрации
                                </a>
                        </dd>
                        @endauth()
                        
                        
                        
                    </dl>
                </div>
                
                
            </div>
      
        </div>
    </div>
@endsection
