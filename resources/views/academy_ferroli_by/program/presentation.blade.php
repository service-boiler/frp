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
            <li class="breadcrumb-item active">{{ $program->name }} - {{ $stage->name }} - {{$presentation->name}}</li>
        </ol>
      
         @alert()@endalert
        
        <div class="card mb-5">
            <div class="card-body">
                  <div class="row">
                @foreach($presentation->slides as $slide)
                
                        <div class="col-sm-2 mb-4"><a href="{{ route('academy_ferroli.programs.slide',['program'=>$program, 'stage' => $stage, 'presentation'=>$presentation, 'slide'=>$slide]) }}">{{$slide->name}}
                            <image src="{{$slide->image->src()}}"></a>
                            
                        </div>
                
                @endforeach
                
                
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
