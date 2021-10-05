@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.reviews.index') }}">@lang('site::admin.review.reviews')</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $review->id }}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::admin.review.review') № {{ $review->id }}</h1>
        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.reviews.edit', $review) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::admin.review.review')</span>
            </a>
            <a href="{{ route('admin.reviews.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            
            @if($review_statuses->isNotEmpty())
                @foreach($review_statuses as $review_status)
                    <button type="submit"
                            form="review-status-edit-form"
                            name="review[status_id]"
                            value="{{$review_status->id}}"
                            class="btn badge-{{$review_status->color}} d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0">
                        <i class="fa fa-{{$review_status->icon}}"></i>
                        <span>{{$review_status->button}}</span>
                    </button>
                @endforeach
            @endif
            
        </div>
        <form id="review-status-edit-form"
              action="{{route('admin.reviews.update', $review)}}"
              method="POST">
            @csrf
            @method('PUT')
            
        </form>
        <div class="card mb-4">
            <div class="card-body">

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.created_at')</dt>
                    <dd class="col-sm-8">{{ $review->created_at->format('d.m.Y H:i') }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.status_id')</dt>
                    <dd class="col-sm-8">
                        <span class="badge text-normal badge-{{$review->status->color}}">
                            <i class="fa fa-{{$review->status->icon}}"></i>
                            {{ $review->status->name }}
                        </span>
                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.rate')</dt>
                    <dd class="col-sm-8">@for($i = 1; $i <= $review->rate; $i++)
                            <i class="fa fa-star fa-rate"></i>
                            @endfor</dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.reviewable')</dt>
                    <dd class="col-sm-8">{{optional($review->reviewable)->name}}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.name')</dt>
                    <dd class="col-sm-8">@if(!empty($review->name)){{$review->name}} @endif</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.email')</dt>
                    <dd class="col-sm-8">@if(!empty($review->email)){{$review->email}} @endif</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.phone')</dt>
                    <dd class="col-sm-8">@if(!empty($review->phone)){{$review->phone}} @endif</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.user_id')</dt>
                    <dd class="col-sm-8">@if(!empty($review->user)){{$review->user->name}} @endif @lang('site::admin.review.user_id_help')</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.ip')</dt>
                    <dd class="col-sm-8">@if(!empty($review->ip)){{$review->ip}} @endif</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::admin.review.message')</dt>
                    <dd class="col-sm-8">@if(!empty($review->message)){{$review->message}} @endif</dd>
                    
                    

                </dl>
            </div>
        </div>
    </div>
@endsection
