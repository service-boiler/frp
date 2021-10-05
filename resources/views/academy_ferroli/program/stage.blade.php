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
            <li class="breadcrumb-item active">{{ $program->name }} - {{ $stage->name }}</li>
        </ol>
      <div class="card mb-2"><div class="card-body">
                <div class="row"><div class="col mb-2">
                В этом уроке: <a href="#presentations">презентаций - {{$stage->presentations->count()}}</a>, <a href="#videos">видеоуроков - {{$stage->videos->count()}}</a>.
                </div></div>
        </div></div>
         @alert()@endalert
                 @foreach($stage->tests as $test)
            <div class="card mb-5"><div class="card-body">
                <div class="row"><div class="col mb-2">
                    <h5>Тестирование: {!!$test->name!!}</h5>
                </div></div>
                
                <div class="row"><div class="col mb-2">
                   
                   @if($user->hasRole('service_fl') || $user->hasRole('asc') || $user->hasRole('админ') || $program->id == 1 || $program->id == 3 )
                   
                       @if($parentStageStatus == 'completed' || $parentStageStatus == 'norequired')
                       <a class="btn btn-ms" href="{{route('academy_ferroli.programs.test',['program'=>$program, 'stage' => $stage, 'test'=>$test])}}">Пройти тестирование</a>
                       @else
                       <p class="mb-2">Для прохождения этого теста требуется успешное прохождение предыдущего этапа  - 
                       <a href="{{route('academy_ferroli.programs.stage',['program'=>$program, 'stage' => $stage->parentStage->id])}}">{{$stage->parentStage->name}}</a>
                       </p>
                       <button class="btn btn-ms disabled"  disabled>Пройти тестирование</button>
                       <a href="{{route('academy_ferroli.programs.stage',['program'=>$program, 'stage' => $stage->parentStage->id])}}"" class="d-block d-sm-inline-block btn btn-primary">
                            <i class="fa fa-external-link"></i>
                            <span>Пройти {{$stage->parentStage->name}}</span>
                        </a>
                       @endif
                    @else
                    <p class="mb-2">Прохождение тестирования доступно пользователям, имеющим статус сервисного инженера. <br />
                    Получить статус сервисного инженера Вы можете отправив заявку в разделе <a href="{{route('user_relations.index')}}">Связи пользователя</a></p>
                    @endif
                </div></div>
              
            </div></div>
        @endforeach
        <a name="presentations"></a>
        @foreach($stage->presentations as $presentation)
            <div class="card mb-5"><div class="card-body">
                <div class="row"><div class="col mb-2">
                    <h5>Презентация: {!!$presentation->name!!}</h5>
                </div>
                </div>
                
                <div class="row">
                 @foreach($presentation->slides as $slide_num=>$slide)
                @if($slide_num==6) 
                <div class="row d-none">
                @endif
                        <div class="col-sm-2 mb-4">
                      @if(!empty($slide->image))
                        
                        <a href="{{ route('academy_ferroli.programs.slide',['program'=>$program, 'stage' => $stage, 'presentation'=>$presentation, 'slide_num'=>$slide_num]) }}">
                        <image style="width:100%" src="{{$slide->image->src()}}">
                        </a>
                        <div style="min-height: 4em;">
                        <a href="{{ route('academy_ferroli.programs.slide',['program'=>$program, 'stage' => $stage, 'presentation'=>$presentation, 'slide_num'=>$slide_num]) }}">
                                                {{$slide->name}}
                        </a>
                        </div>
                        @endif   
                      
                        </div>
                        
                            
                
                @endforeach
                </div>
                
                    <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('fixed-height-450');$(this).toggleClass('d-none')" 
                            class="ml-3 align-text-bottom text-left text-success">
                                    <b>Показать другие слайды презентации</b>
                            </a>
                </div>
                
                <div class="row"><div class="col">
                {!!$presentation->text!!}
                </div></div>
            </div></div>
        @endforeach
<p class="pb-5"><a name="videos"></a></p>
        @foreach($stage->videos as $video)
            <div class="card mb-5"><div class="card-body">
                      
              <h4 class="mt-0">{{$video->name}}</h4>
                <div class="form-row"><div class="col mb-3">
                               {{$video->annotation}}
                </div></div>
                
                <div class="form-row"><div class="col mb-3">
                               <iframe width="1024" height="576" src="{{$video->link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div></div>   
                
            </div></div>  
        @endforeach      
        </div>
    </div>
@endsection
