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
            
            <li class="breadcrumb-item active">{{$video->name}}</li>
        </ol>
      
         @alert()@endalert
        
        <div class="card mb-5">
            <div class="card-body">
                  <div class="row">
                 
                 
                 <div class="col mb-3">
                               {{$video->annotation}}
                </div></div>
                
                <div class="form-row"><div class="col mb-3">
                               <iframe width="1024" height="576" src="{{$video->link}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div></div>   
                
           
                
                
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
