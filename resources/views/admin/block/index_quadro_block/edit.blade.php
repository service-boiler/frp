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
                <a href="{{route('admin.index_quadro_blocks.index') }}">@lang('site::admin.index_quadro_blocks_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$indexQuadroBlock->title}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$indexQuadroBlock->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="form"
                              action="{{route('admin.index_quadro_blocks.update', $indexQuadroBlock) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="url">@lang('site::admin.index_quadro_block_url')</label>
                                    <input type="text" name="indexQuadroBlock[url]"
                                           id="url"
                                           required
                                           class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.url')"
                                           value="{{ old('url', $indexQuadroBlock->url) }}">
                                    <span class="invalid-feedback">{{ $errors->first('url') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.index_quadro_block_title')</label>
                                    <input type="text" name="indexQuadroBlock[title]"
                                           id="title"
                                           required
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.title')"
                                           value="{{ old('title', $indexQuadroBlock->title) }}">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="text">@lang('site::admin.index_quadro_block_text')</label>
                                    <input type="text" name="indexQuadroBlock[text]"
                                           id="text"
                                           required
                                           class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.text')"
                                           value="{{ old('text', $indexQuadroBlock->text) }}">
                                    <span class="invalid-feedback">{{ $errors->first('text') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="text_hover">@lang('site::admin.index_quadro_block_text_hover')</label>
                                    <input type="text" name="indexQuadroBlock[text_hover]"
                                           id="text_hover"
                                           required
                                           class="form-control{{ $errors->has('text_hover') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.text_hover')"
                                           value="{{ old('text_hover', $indexQuadroBlock->text_hover) }}">
                                    <span class="invalid-feedback">{{ $errors->first('text_hover') }}</span>
                                </div>
                            </div>
                           <div class="form-row required">
                           
                           <div class="col">
                           <label class="control-label" for="sort_order">@lang('site::admin.index_quadro_block_sort_order')</label>
                           <div class="col mb-3 col-xl-1">
                                    
                                    <input type="number" min="0" max="5" step="1"  name="indexQuadroBlock[sort_order]"
                                           id="sort_order"
                                           value="{{old('sort_order', $indexQuadroBlock->sort_order)}}"
                                           required
                                           class="form-control{{ $errors->has('sort_order') ? ' is-invalid' : '' }}">
                                    <span class="invalid-feedback">{{ $errors->first('sort_order') }}</span>
                                </div>
                           </div>
                            </div>
                           
                           
                            <div class="form-row">
                                <div class="col mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('indexQuadroBlock.enabled', $indexQuadroBlock->enabled)) checked @endif
                                               class="custom-control-input{{  $errors->has('indexQuadroBlock.enabled') ? ' is-invalid' : '' }}"
                                               id="enabled"
                                               name="indexQuadroBlock[enabled]">
                                        <label class="custom-control-label"
                                               for="enabled">@lang('site::messages.enabled')</label>
                                        <span class="invalid-feedback">{{ $errors->first('indexQuadroBlock.enabled') }}</span>
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
                                <a href="{{route('admin.index_quadro_blocks.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            
                        
                        <button type="submit" form="indexQuadroBlock-delete-form-{{$indexQuadroBlock->id}}"
								class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>
                            </div>
							<form id="indexQuadroBlock-delete-form-{{$indexQuadroBlock->id}}"
									action="{{route('admin.index_quadro_blocks.destroy', $indexQuadroBlock)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
                     <div class="row mt-4">
                     <img src="/images/index-quadro-sample.jpg">
                     </div>
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
                                           value="index_quadro"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.index_quadro.accept')}}"
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
