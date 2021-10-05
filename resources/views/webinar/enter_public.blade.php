@extends('layouts.app')
@push('styles')
<script src="https://pruffme.com/engine/api/js/library.js"></script>
@endpush
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
                <a href="{{ route('webinars.index') }}">@lang('site::admin.webinar.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $webinar->name }}</li>
        </ol>
       
    </div>
    
<script language="javascript" type="text/javascript">
   var api = pruffmeLab({});

   function createParticipant()
   {
      api.loginWebinarViewer(
         "{{$webinar->id_service}}",
         [
            
           {
               "name": "Имя и Фамилия",
               "value": "{{$participant->name}} {{$participant->company ? $participant->company : ''}} (Unregistred)",
               "type": 1
            },           {
               "name": "Компания",
               "value": " {{$participant->company ? $participant->company : 'не указана'}}",
               "type": 0
            },           {
               "name": "E-mail",
               "value": "{{$participant->email ? $participant->email : 'не указан'}}",
               "type": 0
            }, 
            {
               "name": "Телефон",
               "value": "{{$participant->phone ? $participant->phone : 'не указано'}}",
               "type": 0
            },
            {
               "name": "Город",
               "value": "{{$participant->city ? $participant->city : 'не указано'}}",
               "type": 0
            },
            {
               "name": "Специализация (продажи, монтаж, сервис)",
               "value": "{{$participant->vocation ? $participant->vocation : 'не указано'}}",
               "type": 0
            },

         ],
         function(result){
            window.location = "{{ route('events.webinars.view',$webinar) }}"
         },
         function(error){
            alert("ERROR: "+JSON.stringify(error,null,4));
         });
   }
   window.onload = createParticipant();
</script>
@endsection
