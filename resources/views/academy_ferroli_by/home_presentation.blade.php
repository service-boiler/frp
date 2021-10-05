@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home_academy') }}">@lang('site::home.academy')</a>
            </li>
            
            <li class="breadcrumb-item active">{{$presentation->name}}</li>
        </ol>
      
         @alert()@endalert
        
        <div class="card mb-5">
            <div class="card-body">
                  <div class="row">
                 @foreach($presentation->slides as $slide_num=>$slide)
              
                        <div class="col-sm-2 mb-4">
                        @if(!empty($slide->image))
                        
                        <a href="">
                        <image style="width:100%" src="{{$slide->image->src()}}">
                        </a>
                        <div style="min-height: 4em;">
                        <a href="{{ route('home_academy_slide',['presentation'=>$presentation, 'slide_num'=>$slide_num]) }}">
                                                {{$slide->name}}
                        </a>
                        </div>
                            @endif
                        </div>
                
                @endforeach
                
                
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
