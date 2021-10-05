@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy_ferroli.programs.index') }}">@lang('site::academy.program.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $program->name }}</li>
        </ol>
      
         @alert()@endalert
        
        <h1>Программа обучения: {!!$program->name!!}</h1>
        <div class="card mb-5">
            <div class="card-body">
           
                    <dl class="row">
                     
                     <dt class="col-sm-3 text-left text-sm-right"></dt>
                    <dd class="col-sm-9">
                    @if($programUserStatus == 'completed') 
                     Программа обучения успешно пройдена. Сертификат доступен для скачивания. Номер сертификата {{ $certificate->id }}
                     <a class="btn btn-success" href="{{route('certificates.show', $certificate->id)}}" target="_blank">
                                        <i class="fa fa-download"></i>@lang('site::certificate.button.download_pdf')
                                        </a>
                     
                    @elseif($programUserStatus == 'completed_no_relation')
                    Для получения сертификата необходимо, чтобы Ваша компания подтвердила в своем личном кабинете, что Вы ее сотрудник. Также Ваша компания должна иметь статус Авторизованного сервисного центра.
                    <br />Вам необходимо отправить заявку на прикрепление к своей организации. После Вы сможете получить новый сертификат.
                    <br /><a href="{{route('user_relations.index')}}" class="btn btn-primary">Связи пользователя</a>
                    @else
                      {{$programUserStatus}}
                    @endif                    
                    </dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.theme'):</dt>
                    <dd class="col-sm-9">@if(!empty($program->theme)){!!$program->theme->name!!}@endif</dd>
                                                            
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.annotation'):</dt>
                    <dd class="col-sm-9">{!!$program->annotation!!}</dd>
                    <!--
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.bonuses'):</dt>
                    <dd class="col-sm-9">{!!$program->bonuses!!}</dd>
                    -->
                    <dt class="col-sm-3 text-left text-sm-right mt-4"></dt>
                    <dd class="col-sm-9 mt-4">
                          
                            @foreach($stages as $stage)
                                <div class="card mb-4">
                                <div class="card-body">
                                        <div class="row">
                                        <div class="col-sm-9">
                                        {{$stage->name}}   
                                        <p>{{$stage->annotation}}</p>
                                        <p>
                                        @if($stage->presentations()->exists()) 
                                        Презентаций: {{$stage->presentations()->count()}}&nbsp;&nbsp;&nbsp;&nbsp;@endif
                                        @if($stage->videos()->exists()) 
                                        Видеоуроков: {{$stage->videos()->count()}}&nbsp;&nbsp;&nbsp;&nbsp;@endif
                                        @if($stage->tests()->exists()) 
                                        Тестов: {{$stage->tests()->count()}}&nbsp;&nbsp;&nbsp;&nbsp;@endif
                                        @if(!empty($stage->userStage($user)->first()) && $stage->userStage($user)->first()->completed == 1)
                                        <span class="text-success">Тестирование пройдено</span>
                                        @endif
                                        </p>
                                        </div>
                                        <div class="col-sm-3 text-right">
                                        
                                        
                                        
                                        <a class="btn btn-ms" href="{{ route('academy_ferroli.programs.stage',['program' => $program, 'stage' => $stage]) }}"><i class="fa fa-external-link"></i> Пройти урок</a>
                                        </div>
                                </div>    
                                </div>    
                                </div>    
                                
                            @endforeach
                        
                    
                    </dd>
                  
                    
                       
                </dl>
                
                
                </div>
            </div>
        </div>
    </div>
@endsection
