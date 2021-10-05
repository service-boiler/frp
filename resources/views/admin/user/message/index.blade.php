@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">@lang('site::user.users')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.show', $user) }}">{{$user->name}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::message.messages')</li>
        </ol>
        @alert()@endalert()

        @include('site::message.create', ['messagable' => $user])
         <div class="card mt-2 mb-4">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::message.messages')</span>
                        {{--<div class="card-header-elements ml-auto">--}}
                        {{--<a href="#" class="btn btn-sm btn-light">--}}
                        {{--<i class="fa fa-pencil"></i>--}}
                        {{--</a>--}}
                        {{--</div>--}}
                    </h6>
                    <div class="card-body flex-grow-1 position-relative overflow-hidden">
                        {{--<h5 class="card-title">@lang('site::message.messages')</h5>--}}
                        @if($user->messages->isNotEmpty())
                            <div class="row no-gutters h-100">
                                <div class="d-flex col flex-column">
                                    <div class="flex-grow-1 position-relative">

                                        <!-- Remove `.chat-scroll` and add `.flex-grow-1` if you don't need scroll -->
                                        <div class="chat-messages p-4 ps">

                                            @foreach($user->messages->sortByDesc('created_at') as $message)
                                                <div class="@if($message->user_id == auth()->user()->getAuthIdentifier()) chat-message-right @else chat-message-left @endif mb-4">
                                                    <div>
                                                        <img src="{{$message->user->logo}}"
                                                             style="width: 40px!important;"
                                                             class="rounded-circle" alt="">
                                                        <div class="text-muted small text-nowrap mt-2">{{ $message->created_at->format('d.m.Y H:i') }}</div>
                                                    </div>
                                                    <div class="flex-shrink-1 bg-lighter rounded py-2 px-3 @if($message->user_id == auth()->user()->getAuthIdentifier()) mr-3 @else ml-3 @endif">
                                                        <div class="mb-1"><b>{{$message->user->name}}</b></div>
                                                        <span class="text-big">{!! nl2br($message->text) !!}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <!-- / .chat-messages -->
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                    </div>
                </div>
    </div>
@endsection
