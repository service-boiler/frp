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
                <a href="{{route('admin.head_banner_blocks.index') }}">@lang('site::admin.head_banner_blocks_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$headBannerBlock->title}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$headBannerBlock->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="form"
                              action="{{route('admin.head_banner_blocks.update', $headBannerBlock) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="url">@lang('site::admin.head_banner_blocks_url')</label>
                                    <input type="text" name="headBannerBlock[url]"
                                           id="url"
                                           required
                                           class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.url')"
                                           value="{{ old('url', $headBannerBlock->url) }}">
                                    <span class="invalid-feedback">{{ $errors->first('url') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.head_banner_blocks_title')</label>
                                    <input type="text" name="headBannerBlock[title]"
                                           id="title"
                                           required
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.title')"
                                           value="{{ old('title', $headBannerBlock->title) }}">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.head_banner_block_path')</label>
                                    <input type="text" name="headBannerBlock[path]"
                                           id="path"
                                           required
                                           class="form-control{{ $errors->has('path') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.path')"
                                           value="{{ old('path', $headBannerBlock->path) }}">
                                    <span class="invalid-feedback">{{ $errors->first('path') }}</span>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('headBannerBlock.show_ferroli', $headBannerBlock->show_ferroli)) checked @endif
                                               class="custom-control-input{{  $errors->has('headBannerBlock.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="headBannerBlock[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('headBannerBlock.show_ferroli') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('headBannerBlock.show_market_ru', $headBannerBlock->show_market_ru)) checked @endif
                                               class="custom-control-input{{  $errors->has('headBannerBlock.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="headBannerBlock[show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('headBannerBlock.show_market_ru') }}</span>
                                    </div>
                                
                              
												
                                </div>
                            </div>
                           
                        </form>
                            <hr />
                            <div class=" text-right">
                                <button form="form" value="0" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{route('admin.head_banner_blocks.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            
                        
                        <button type="submit" form="headBannerBlock-delete-form-{{$headBannerBlock->id}}"
								class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>
                            </div>
							<form id="headBannerBlock-delete-form-{{$headBannerBlock->id}}"
									action="{{route('admin.head_banner_blocks.destroy', $headBannerBlock)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
                        
					 <div class="row mt-4">
                     <div class="col-md-6">
                        <div id="images" class="row bg-white">
                            @include('site::admin.image.edit')
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="image_id">@lang('site::catalog.image_id')</label>

                                <form method="POST" enctype="multipart/form-data"
                                      action="{{route('admin.images.store')}}">
                                    @csrf
                                    <input type="hidden"
                                           name="storage"
                                           value="banners"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.banners.accept')}}"
                                           name="path"/>

                                    <input type="button" class="btn btn-ms image-upload-button"
                                           value="@lang('site::messages.load')"/>
                                    <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                </form>


                            </div>
                        </div>
                    </div>
                    
                    </div>
                </div>
								
							
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
