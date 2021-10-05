@extends('site::admin.mailing.create')

@section('mailing-breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('ferroli-user.events.index') }}">@lang('site::event.events')</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('ferroli-user.events.show', $event) }}">{{ $event->title }}</a>
    </li>
@endsection

