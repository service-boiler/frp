@extends('layouts.app')
@section('title') @lang('site::ticket.ticket') @lang('site::messages.title_separator')@endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.tickets.index') }}">@lang('site::ticket.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::ticket.ticket') #{{$ticket->id}}</li>
    </ol>
  
    @alert()@endalert
    <div class=" border p-2 mb-3">
        
            <form id="ticket_status"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.tickets.status', $ticket) }}">
               
                <a href="{{ route('admin.tickets.index') }}" class="d-block d-sm-inline btn btn-secondary p-2 mr-3  mr-sm-3 ">
                    <i class="fa fa-reply"></i>
                    <span>@lang('site::messages.back')</span>
                </a>
    @if($statuses->isNotEmpty())
        
            
                    @csrf
                    @method('PUT')
                
              
                @foreach($statuses as $key => $status)
                    <button type="submit"
                            name="status_id"
                            value="{{$status->id}}"
                            form="ticket_status"
                            style="background-color: {{$status->color}};color:white"
                            class="d-block d-sm-inline mr-0 mr-sm-1  mb-2 mb-sm-2  btn mt-2">
                        <i class="fa fa-{{$status->icon}}"></i>
                        <span>{{$status->button}}</span>
                    </button>
                @endforeach
            
            
        
    @endif <!--{{--$statuses->isNotEmpty()--}} -->
    </form>
    </div>
    
<div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0">

                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.status_id')</dt>
                    <dd class="col-sm-9">
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $ticket->status->color }}">
                            <i class="fa fa-{{ $ticket->status->icon }}"></i> {{ $ticket->status->name }}
                        </span></dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.created_by')</dt>
                    <dd class="col-sm-9">{{$ticket->createdBy->name}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.receiver_id')</dt>
                    <dd class="col-sm-9">{{$ticket->receiver->name}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::messages.created_at')</dt>
                    <dd class="col-sm-9">{{$ticket->created_at->format('d.m.Y H:i')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.closed_at')</dt>
                    <dd class="col-sm-9">{{!empty($ticket->closed_at) ? $ticket->closed_at->format('d.m.Y H:i') : 'нет'}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.type_id')</dt>
                    <dd class="col-sm-9">{{$ticket->type->name}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.theme_id')</dt>
                    <dd class="col-sm-9">{{$ticket->theme->name}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.contacts')</dt>
                    <dd class="col-sm-9">{{$ticket->consumer_name}} {{$ticket->consumer_email}} {{$ticket->consumer_phone}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.consumer_company')</dt>
                    <dd class="col-sm-9">{{$ticket->consumer_company}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.locality')</dt>
                    <dd class="col-sm-9">{{$ticket->locality}}</dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.region')</dt>
                    <dd class="col-sm-9">{{!empty($ticket->region) ? $ticket->region->name: ''}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::ticket.text')</dt>
                    <dd class="col-sm-9">{{$ticket->text}}</dd>
                    
                </dl>   
            </div>
        </div>


            <h3 id="end"></h3>   
                            @include('site::message.comment', ['commentBox' => $commentBox])

</div>
@endsection

@push('scripts')
<script>

 try {
            window.addEventListener('load', function () {

                let receiver_type_id = $('#receiver_type_id');
                let receiver_id = $('#list_receiver_id');
                 
                receiver_type_id.on('change', function (e) {
                
                if(receiver_type_id.val()=='group') {
                        console.log(receiver_type_id.val());
                    $('#group-receiver_user').addClass('d-none');
                    $('#group-receiver_group').removeClass('d-none');
                    }
                if(receiver_type_id.val()=='user') {
                    $('#group-receiver_user').removeClass('d-none');
                    $('#group-receiver_group').addClass('d-none');
                    }
                });
                
                receiver_id.select2({
                    theme: "bootstrap4",
                    placeholder: '@lang('site::messages.select_from_list')',
                    selectOnClose: true,
                    
                });

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush