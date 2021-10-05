@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy_ferroli.programs.index') }}">@lang('site::academy.program.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy_ferroli.programs.show', $program) }}">{{ $program->name }}</a>
            </li>
            <li class="breadcrumb-item active">{{ $program->name }} - {{ $stage->name }} - {{ $test->name }}</li>
        </ol>
      
         @alert()@endalert
         <h1>Результаты прохождения теста</h1>
         <div class="card mb-3"><div class="card-body">
            <a href="{{route('academy_ferroli.programs.show',$program)}}" class="d-block d-sm-inline-block btn btn-secondary mb-4">
                        <i class="fa fa-reply"></i>
                        <span>К содержанию программы обучения</span>
                    </a>
            @if($stageComplete == 1)
            <h3>Поздравляем! Этап обучения пройден.</h3>
                    @if($programUserStatus == 'completed') 
                     Программа обучения успешно пройдена. Сертификат доступен для скачивания. 
                     
                    @elseif($programUserStatus == 'completed_no_relation')
                    Для получения сертификата необходимо, чтобы Ваша компания подтвердила в своем личном кабинете, что Вы ее сотрудник. Также Ваша компания должна иметь статус Авторизованного сервисного центра.
                    <br />Вам необходимо отправить заявку на прикрепление к своей организации. После Вы сможете получить новый сертификат.
                    <br /><a href="{{route('user_relations.index')}}" class="btn btn-primary">Связи пользователя</a>
                    @else
                      {{$programUserStatus}}
                    @endif  
            @else
            <h3>В тесте слишком много ошибок.</h3>
            <a href="{{route('academy_ferroli.programs.test',['program'=>$program, 'stage' => $stage, 'test'=>$test])}}"" class="d-block d-sm-inline-block btn btn-primary">
                        <i class="fa fa-external-link"></i>
                        <span>Пройти тест еще раз</span>
                    </a>
            @endif
            </div></div>
                  
        @foreach($userStage->userStageQuestions as $userQuestion)
            <div class="card mb-3"><div class="card-body">
                <div class="row"><div class="col mb-2 @if($userQuestion->is_correct) text-success @else text-danger @endif">
                 
                   <h5>{{$userQuestion->question->text}}</h5>
                   {{$userQuestion->answer->text}} @if($userQuestion->is_correct) (верный ответ) @else
                    (неверный ответ) 
                   @endif
                    </div>
                </div></div>
                
            </div>
        @endforeach
            
            
    </div>
   
@endsection
