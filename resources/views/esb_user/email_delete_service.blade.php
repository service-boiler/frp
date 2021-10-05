@extends('layouts.email')

@section('title')
    @lang('site::user.esb_user.service_delete_subject')
@endsection

@section('h1')
    @lang('site::user.esb_user.service_delete_subject')
@endsection

@section('body')
    <p>
        {{$esbUser->name_filtred}}
    </p>
    <p>
       Пользователь нажал кнопку "удалить" в списке своих АСЦ. Ваш АСЦ перемещен у него в архив.
    </p>




@endsection