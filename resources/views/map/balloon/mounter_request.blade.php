@extends('site::map.balloon_card')
@section('link')
    <a href="{{route('mounters.create', $id)}}" class="card-link btn btn-success">
        @lang('site::messages.leave')
        @lang('site::mounter.mounter')
    </a>
@endsection