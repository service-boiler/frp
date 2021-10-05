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
               "value": "{{$name}} @if(!empty($parent))({{$parent->name}})@endif",
               "type": 1
            },   
            {
               "name": "Компания",
               "value": " @if(!empty($parent)){{$parent->name}} @elseif(in_array($user->type_id,['1','2'])) $user->name @endif",
               "type": 0
            },          
            {
               "name": "E-mail",
               "value": "{{$user->email}}",
               "type": 0
            }, 
            {
               "name": "Телефон",
               "value": "{{$user->phone}}",
               "type": 0
            },
            {
               "name": "Город",
               "value": "@if(!empty($user->region)){{$user->region->name}}@else регион не указан @endif",
               "type": 0
            },
            {
               "name": "Специализация (продажи, монтаж, сервис)",
               "value": "Продажи",
               "type": 0
            },

         ],
         function(result){
         
            window.location = "{{ route('webinars.view',$webinar) }}"
         },
         function(error){
            alert("ERROR: "+JSON.stringify(error,null,4));
         });
   }
   window.onload = createParticipant();
</script>
@endsection
