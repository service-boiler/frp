@extends('site::layouts.slide')

@section('content')
<div class="main-container">
         @alert()@endalert
                <div class="slide-image" style="height: 90vh;">
                    @if(!empty($slide->image))
                        <img style="height: 100%; width: auto;" src="{{$slide->image->src()}}" alt="">
                    @else
                        <h2>Изображение не загружено на сервер</h2>
                    @endif
                </div>

                <div class="row mt-3">
                        <div class="col text-center">
                           {{$slide->name}}
                        </div>
               </div>
               <div class="row mt-2">
                        <div class="col text-center">
                           {!!$slide->text!!}
                        </div>
               </div>
     
    <div class="slide-nav">
        <div class="row mt-1 ml-1 mr-1">
            <div class="col-3 text-left text-top">
                
                    <a href="{{route('academy-admin.presentations.show',$slide->presentation)}}"> 
                        <image style="height: 20px;" src="/images/sl1/stop.png"> 
                    </a>
                
            </div>
            
            <div class="col-6 text-center text-top">
            @if(!empty($slide->sound))
                <audio autoplay="autoplay" controls id="sound">
                        <source src="{{$slide->sound->src()}}"  type="audio/mpeg">
                </audio>
            @endif
            </div>
            
        </div>
    </div>
    
</div>
      

@endsection
