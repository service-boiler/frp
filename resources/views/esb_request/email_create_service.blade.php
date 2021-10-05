@extends('layouts.email')

@section('title')
    @lang('site::user.esb_request.email_create_subject')
@endsection

@section('h1')
    @lang('site::user.esb_request.email_create_subject')
@endsection

@section('body')
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('esb-requests.index') }}">
            @lang('site::messages.open') @lang('site::user.esb_request.esb_requests') {{ route('esb-requests.index') }}</a>
    </p>
    <p>{{$esbRequest->service->public_name}}</p>
    <p>{{$esbRequest->phone ? $esbRequest->phone : $esbRequest->esbUser->phone}}</p>
    <p>{{$esbRequest->contact_name}}</p>
    <p>{{$esbRequest->esbUserProduct ? $esbRequest->esbUserProduct->address_filtred : null}}</p>
    <p>{{$esbRequest->comments}}</p>
@endsection