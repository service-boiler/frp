@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.show', $address->user) }}">{{$address->user->name}}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.addresses.show', $address) }}">{{$address->name}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::image.images')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::image.images')</h1>
        @alert()@endalert
        <div class="row border p-3 mb-2">
            <div class="col-xl-1 col-sm-3"><a href="{{ route('admin.addresses.show', $address) }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::address.help.back')</span>
            </a></div>
				<div class="col-xl-11 col-sm-12">@lang('site::address.help.images')</div>
        </div>
        <div class="card mb-4 ">
            <div class="card-body">
				<div class="row">
				<div class="col-md-9">

                            <div id="images"
                                 class="form-row my-2 sort-list"
                                 data-target="{{route('addresses.images.sort')}}">
                                @foreach($images as $image)
                                    @include('site::address.image.edit')
                                @endforeach
                            </div>
            </div>
				</div>
                <form method="POST" id="form-content"
                      action="{{ route('addresses.images.store', $address) }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-row mt-2">
                                <div class="col">
                                    <label class="control-label" class="control-label"
                                           for="image_id">@lang('site::image.add_images')</label>
                                    <form method="POST" enctype="multipart/form-data"
                                          action="{{route('addresses.images.store',$address)}}">
                                        @csrf
                                        <input type="hidden"
                                               name="storage"
                                               value="addresses"/>
                                        <input class="d-inline-block form-control-file{{ $errors->has('path') ? ' is-invalid' : '' }}"
                                               type="file"
                                               accept="{{config('site.addresses.accept')}}"
                                               name="path"/>

                                        <input type="button" class="btn btn-ms d-inline-block image-upload-button"
                                               value="@lang('site::messages.load')"/>
															  

                                        <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
