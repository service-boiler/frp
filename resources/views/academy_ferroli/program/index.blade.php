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
            <li class="breadcrumb-item active">@lang('site::academy.program.index')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::academy.program.index')</h1>

        @alert()@endalert

       
        
            
        @foreach($programs as $program)

            <div class="card mb-2">
            <div class="card-body">
            <div class="row">
                <div class="col">
                <h5 class="card-title"><a href="{{route('academy_ferroli.programs.show', $program)}}">{{ $program->name }}</a></h5>
                </div>
            </div>
            <div class="row">
                <div class="col">
              {{ $program->annotation }}</h5>
                </div>
            </div>
            </div>
            </div>
        @endforeach
            
			
		
    </div>
@endsection
