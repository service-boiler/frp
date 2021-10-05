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
       <form id="form" method="POST" action="{{ route('academy_ferroli.programs.sendtest',['program'=>$program, 'stage' => $stage, 'test'=>$test]) }}">
       @csrf
                     @method('POST')
        @foreach($rand as $numOnRand => $numQuestion)
            <div class="card mb-3"><div class="card-body">
                <div class="row">
                    <div class="col mb-2">
                    <label class="control-label custom-control-inline"
                                                   for="qestion-{{$test->questions[$numQuestion]->id}}">
                                                   <h5>{{$test->questions[$numQuestion]->text}}</h5></label>
                    <div class="row">
                    @foreach($test->questions[$numQuestion]->answers as $answer)
                    <div class="col-sm-12 mb-2 ml-4 custom-control custom-radio">
                        <input class="custom-control-input" type="radio" required name="answers[{{$test->questions[$numQuestion]->id}}]" id="answers-{{$test->questions[$numQuestion]->id}}-{{$answer->id}}" value="{{$answer->id}}">
                    <label class="custom-control-label" for="answers-{{$test->questions[$numQuestion]->id}}-{{$answer->id}}">{{$answer->text}} 
                    @if($answer->is_correct && in_array($user->id,['1','581','900','909','914','695']) ) ( <strong class="rng"><i class="fa fa-check"></i> </strong>правильный ответ, только для отладки у избранных пользователей)@endif</label>
                    </div>
                    @endforeach
                    </div>
                </div></div>
                
            </div></div>
        @endforeach
        <div class="card mb-5"><div class="card-body">
        <button type="submit" class="d-block d-sm-inline-block btn btn-ms">Отправить ответы</button>
        </div></div>
        </form>       
        </div>
    </div>
@endsection
