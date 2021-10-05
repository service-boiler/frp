@extends('layouts.email')

@section('title')
    @lang('site::admin.review.email_title')
@endsection

@section('h1')
    @lang('site::admin.review.email_h1')
@endsection

@section('body')
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('admin.reviews.show', $review) }}">
            &#128194; @lang('site::messages.open') @lang('site::admin.review.review') № {{$review->id }}</a>
    </p>
    <p>
    {{$review->reviewable->name }}
    </p>
    <p>
    {{$review->message }}
    </p>
@endsection