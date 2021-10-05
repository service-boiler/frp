@extends('layouts.app')
@section('title') Тикеты. Заявки, обращения. @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::ticket.tickets')</li>
        </ol>

        @alert()@endalert()
        <div class="p-2 mb-3">
        <a href="{{route('admin.tickets.create')}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1  btn btn-ms p-2">
                    <i class="fa fa-pencil"></i>
                    <span>Новый тикет</span>
                </a>
                <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{route('admin.ticket-themes.index')}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1  btn btn-green p-2">
                    <span>@lang('site::ticket.theme.index')</span>
                </a>
</div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $tickets])@endpagination
        {{$tickets->render()}}

        
        @foreach($tickets as $ticket)
            <div class="card my-4" id="ticket-{{$ticket->id}}">

                <div class="card-header with-elements">

                    <div class="card-header-elements">
                        <a href="{{route('admin.tickets.show', $ticket)}}" class="mr-3 text-big">
                            № {{$ticket->id}}
                        </a>
                        @lang('site::ticket.created_by'):
                        
                            @if($ticket->createdBy->image->fileExists)
                                <img id="user-logo" src="{{$ticket->createdBy->logo}}"
                                     style="width:25px!important;height: 25px"
                                     class="rounded-circle mr-2">
                            @endif
                            {{$ticket->createdBy->name}}
                        

                    </div>

                    <div class="card-header-elements ml-md-auto">
                          @lang('site::ticket.receiver_id'):
                        
                            @if($ticket->receiver->image->fileExists)
                                <img id="user-logo" src="{{$ticket->receiver->logo}}"
                                     style="width:25px!important;height: 25px"
                                     class="rounded-circle mr-2">
                            @endif
                            {{$ticket->receiver->name}}&nbsp;
                        
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $ticket->status->color }}">
                            <i class="fa fa-{{ $ticket->status->icon }}"></i> {{ $ticket->status->name }}
                        </span>
                        @if( $ticket->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $ticket->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$ticket->created_at->format('d.m.Y H:i')}}</dd>
                            @if(in_array($ticket->status_id,['4','5']))
                            <dt class="col-12">@lang('site::ticket.closed_at')</dt>
                            <dd class="col-12">{{$ticket->closed_at ? $ticket->closed_at->format('d.m.Y H:i') : ''}}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::ticket.type_id')</dt>
                            <dd class="col-12">{{$ticket->type->name}}</dd>
                           
                            <dt class="col-12">@lang('site::ticket.theme_id')</dt>
                            <dd class="col-12">{{$ticket->theme->name}}</dd>
                           
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        {{$ticket->text}}
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dt class="col-12">@lang('site::ticket.contacts')</dt>
                            <dd class="col-12">{{$ticket->consumer_name}} {{$ticket->consumer_email}} {{$ticket->consumer_phone}} {{$ticket->consumer_company}}</dd>
                    </div>
                </div>
            </div>
        @endforeach
        {{$tickets->render()}}
    </div>
@endsection
