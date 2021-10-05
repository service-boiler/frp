@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.review.reviews')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::admin.review.icon')"></i> @lang('site::admin.review.reviews')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a href="{{ route('admin') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $reviews])@endpagination
        {{$reviews->render()}}
        @foreach($reviews as $review)
            <div class="card my-2" id="review-{{$review->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <span class="badge text-normal badge-pill badge-{{ $review->status->color }} mr-3 ml-0">
                            <i class="fa fa-{{ $review->status->icon }}"></i> {{ $review->status->name }}
                        </span>
                        <a href="{{route('admin.reviews.show', $review)}}" class="mr-3 ml-0">
                            @lang('site::admin.review.review') № {{$review->id}} 
                            @for($i = 1; $i <= $review->rate; $i++)
                            <i class="fa fa-star fa-rate"></i>
                            @endfor
                        </a>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$review->created_at->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::messages.updated_at')</dt>
                            <dd class="col-12">{{$review->updated_at->format('d.m.Y')}}</dd>
                        </dl>
                    </div>
                   
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.review.reviewable')</dt>
                            <dd class="col-12">{{optional($review->reviewable)->name}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.review.user_id')</dt>
                            <dd class="col-12">@if(!empty($review->user)){{$review->user->name}}@else @lang('site::admin.review.no_ident') @endif</dd>

                            <dt class="col-12">@lang('site::admin.review.ip')</dt>
                            <dd class="col-12">{{$review->ip}}</dd>

                        </dl>
                    </div>
                    <div class="col-xl-5 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.review.message')</dt>
                            
                            <dd class="col-12">{{$review->message}}</dd>

                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$reviews->render()}}
    </div>
@endsection
