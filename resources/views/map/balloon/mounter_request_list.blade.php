@extends('site::map.balloon_list')
@section('link')
    <a href="{{route('mounters.create', $address_id)}}" class="card-link btn btn-success">
        @lang('site::messages.leave')
        @lang('site::mounter.mounter')
    </a>
@endsection