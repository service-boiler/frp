@extends('layouts.email')

@section('title')
    'Заявка в тикет-системе не обработана!'
@endsection
@section('h1')
    <a class="btn btn-ms" href="{{ route('admin.tickets.show', $ticket) }}">
            <b>{{ route('admin.tickets.show', $ticket) }}</b></a>
@endsection

@section('body')
    <p><b>Заявка в тикет-системе не обработана. 
        @if(in_array($ticket->status->id,[1,2])) Возьмите в работу! @endif
        @if(in_array($ticket->status->id,[3])) Статус в работе, отметки о закрытии нет. @endif</p>
    <p><b>Тикет №</b> {{$ticket->id }} ({{$ticket->status->name }})</p>
    <p><b>Ответственный:</b> {{$ticket->receiver->name }}</p>
    <p><b>Статус заявки:</b> {{$ticket->status->name }}</p>
    
    <p><b>@lang('site::ticket.type_id'):</b> {{$ticket->type->name}}</p>
                    
                    <p><b>@lang('site::ticket.theme_id'):</b> {{$ticket->theme->name}}</p>
                    
                    <p><b>@lang('site::ticket.contacts'):</b> {{$ticket->consumer_name}} {{$ticket->consumer_email}} {{$ticket->consumer_phone}}</p>
                    
                    <p><b>@lang('site::ticket.consumer_company'):</b> {{$ticket->consumer_company}}</p>
                    
                    <p><b>@lang('site::ticket.locality'):</b> {{$ticket->locality}}</p>
                    <p><b>@lang('site::ticket.region'):</b> {{!empty($ticket->region) ? $ticket->region->name: ''}}</p>
                    
                    <p><b>@lang('site::ticket.text'):</b> {{$ticket->text}}</p>
    
    <p><b>Полследний комментарий: </b><br /> @foreach($ticket->messages()->orderByDesc('created_at')->take(2)->get() as $message) {{$message->text}} <br />@endforeach</p>
    
@endsection