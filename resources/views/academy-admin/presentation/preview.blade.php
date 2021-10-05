@extends('site::layouts.slide')

@section('content')
<div class="main-container">

   
       
      
	   

         @alert()@endalert
                <div class="slide-image" style="height: 90vh;">
                            @if(!empty($presentation->slides[$slide_num]->image))
                            <img style="height: 100%; width: auto;" src="{{$presentation->slides[$slide_num]->image->src()}}" alt="">
                            @else
                            <h2>Изображение не загружено на сервер</h2>
                            @endif
                </div>
               <div class="row mt-3">
                        <div class="col text-center">
                           {{$presentation->slides[$slide_num]->name}}
                            
                        </div>
               </div><div class="row mt-2">
                        <div class="col text-center">
                           {!!$presentation->slides[$slide_num]->text!!}
                            
                        </div>
               </div>
          
    
    <div class="slide-nav">
        <div class="row mt-1 ml-1 mr-1">
            <div class="col-3 text-left text-top">
                @if($slide_num-1>=0)
                    <a href="{{route('academy-admin.presentations.show',$presentation)}}"> 
                        <image style="height: 20px;" src="/images/sl1/stop.png"> 
                    </a>
                     <a href="{{route('academy-admin.presentations.preview',['presentation'=>$presentation, 'slide_num'=>$slide_num-1])}}"> <image style="height: 20px; margin-left: 30px;" src="/images/sl1/back.png"> </a>
                @else
                    <a href="{{route('academy-admin.presentations.show',$presentation)}}"> 
                        <image data-toggle="tooltip" data-original-title="Выйти из презентации" style="height: 20px;" src="/images/sl1/stop.png"> 
                    </a>
                @endif
            </div>
            
            <div class="col-6 text-center text-top">
            @if(!empty($presentation->slides[$slide_num]->sound))
                <audio autoplay="autoplay" controls id="sound">
                        <source src="{{$presentation->slides[$slide_num]->sound->src()}}"  type="audio/mpeg">
                </audio>
            @endif
            </div>
            
            <div class="col-3 text-right text-top">
                @if(!empty($presentation->slides[$slide_num+1]))
                    <a href="{{route('academy-admin.presentations.preview',['presentation'=>$presentation, 'slide_num'=>$slide_num+1])}}"> <image style="height: 20px;" src="/images/sl1/next.png"> </a>
                @else
                    <a href="{{route('academy-admin.presentations.show',$presentation)}}"> <image data-toggle="tooltip" data-original-title="Выйти из презентации" style="height: 20px;" src="/images/sl1/stop.png"> </a>
                @endif
            
            </div>
        </div>
    </div>
    
</div>
      

@endsection
