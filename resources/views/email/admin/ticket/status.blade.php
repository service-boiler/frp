@extends('layouts.email')

@section('title')
    'Изменен статус тикета'
@endsection
@section('h1')
    <a class="btn btn-ms" href="{{ route('admin.tickets.show', $ticket) }}">
            <b>{{ route('admin.tickets.show', $ticket) }}</b></a>
@endsection

@section('body')
    <p><b>@lang('site::ticket.ticket') №</b> {{$ticket->id }}</p>
    <p><b>@lang('site::ticket.status_id')</b> {{$ticket->id }}</p>
    <p><b>Суть обращения:</b> {{$ticket->text}}</p>
    <p><b>Полследний комментарий: </b><br /> @foreach($ticket->messages()->orderByDesc('created_at')->take(2)->get() as $message) {{$message->text}} <br />@endforeach</p>
    <p><b>@lang('site::ticket.created_by'):</b> {{$ticket->createdBy->name}}</p>
    <p><b>@lang('site::ticket.receiver_id'):</b> {{$ticket->receiver->name}}</p>
    <p><b>@lang('site::messages.created_at'):</b> {{$ticket->created_at->format('d.m.Y H:i')}}</p>
    <p><b>@lang('site::ticket.type_id'):</b> {{$ticket->type->name}}</p>
    <p><b>@lang('site::ticket.theme_id'):</b> {{$ticket->theme->name}}</p>
    <p><b>@lang('site::ticket.contacts'):</b> {{$ticket->consumer_name}} {{$ticket->consumer_email}} {{$ticket->consumer_phone}}</p>
    
@endsection