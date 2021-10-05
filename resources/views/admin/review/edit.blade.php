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
            <li class="breadcrumb-item">
                <a href="{{ route('admin.reviews.show', $review) }}">№ {{$review->id}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::admin.review.review')</h1>

        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <a href="{{ route('admin.reviews.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
        </div>
        <div class="row justify-content-center mb-5">
            <div class="col">

                <div class="card mt-2 mb-2">
                    <div class="card-body">
                        <form id="form-content"
                              method="POST"
                              enctype="multipart/form-data"
                              action="{{ route('admin.reviews.update', $review) }}">

                            @csrf
                            @method('PUT')

                            <div class="form-row required">
                                <label class="control-label"
                                       for="status_id">@lang('site::admin.review.status_id')</label>
                                <select class="form-control{{  $errors->has('review.status_id') ? ' is-invalid' : '' }}"
                                        required
                                        name="review[status_id]"
                                        id="status_id">
                                    @if($review_statuses->count() == 0 || $review_statuses->count() > 1)
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                    @endif
                                    @foreach($review_statuses as $review_status)
                                        <option
                                                @if(old('review.status_id', $review->status_id) == $review_status->id) selected
                                                @endif
                                                value="{{ $review_status->id }}">{{ $review_status->name }}</option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('review.status_id') }}</span>
                            </div>
                            <div class="form-row required">
                                <label class="control-label"
                                       for="rate">@lang('site::admin.review.rate')</label>
                                <select class="form-control{{  $errors->has('review.rate') ? ' is-invalid' : '' }}"
                                        required
                                        name="review[rate]"
                                        id="rate">
                                    
                                    @for($rate=1; $rate<=5; $rate++)
                                        <option
                                                @if(old('review.rate', $review->rate) == $rate) selected
                                                @endif
                                                value="{{ $rate }}">{{ $rate }}</option>
                                    @endfor
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('review.rate') }}</span>
                            </div>

                            <div class="form-row required">
                                <label class="control-label"
                                       for="created_at">@lang('site::admin.review.created_at')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_created_at"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="review[created_at]"
                                           id="created_at"
                                           maxlength="10"
                                           required
                                           placeholder="@lang('site::admin.review.placeholder.created_at')"
                                           data-target="#datetimepicker_created_at"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('review.created_at') ? ' is-invalid' : '' }}"
                                           value="{{ old('review.created_at', $review->created_at->format('d.m.Y')) }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_created_at"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('review.created_at') }}</span>
                            </div>

                            <div class="form-row mt-2 required">
                                <label class="control-label" for="name">@lang('site::admin.review.name')</label>
                                <input required
                                       type="text"
                                       id="name"
                                       name="review[name]"
                                       class="form-control{{ $errors->has('review.name') ? ' is-invalid' : '' }}"
                                       value="{{ old('review.name', $review->name) }}"
                                       placeholder="@lang('site::admin.review.placeholder.name')">
                                <span class="invalid-feedback">{{ $errors->first('review.name') }}</span>
                            </div>
                            <div class="form-row mt-2 required">
                                 <label class="control-label"
                                               for="name">Отзыв</label>
                                        <textarea required
                                               rows="5"
                                               name="review[message]"
                                               id="message"
                                               maxlength="1255"
                                               class="form-control"
                                              >
                                               {{ old('review.message', $review->message) }}
                                        </textarea>
                            </div>

                           
                        </form>
                        <hr/>
                        <div class="form-row">
                            <div class="col text-right">
                                <button form="form-content" type="submit"
                                        class="btn btn-ms mb-1">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary mb-1">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
