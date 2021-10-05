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
                <a href="{{route('admin.video_blocks.index') }}">@lang('site::admin.video_blocks_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$videoBlock->title}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$videoBlock->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="video_block-edit-form"
                              action="{{route('admin.video_blocks.update', $videoBlock) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="url">@lang('site::admin.video_block_url')</label>
                                    <input type="text" name="videoBlock[url]"
                                           id="url"
                                           required
                                           class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.url')"
                                           value="{{ old('url', $videoBlock->url) }}">
                                    <span class="invalid-feedback">{{ $errors->first('url') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.video_block_title')</label>
                                    <input type="text" name="videoBlock[title]"
                                           id="title"
                                           required
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.title')"
                                           value="{{ old('title', $videoBlock->title) }}">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('videoBlock.show_ferroli', $videoBlock->show_ferroli)) checked @endif
                                               class="custom-control-input{{  $errors->has('videoBlock.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="videoBlock[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('videoBlock.show_ferroli') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('videoBlock.show_market_ru', $videoBlock->show_market_ru)) checked @endif
                                               class="custom-control-input{{  $errors->has('videoBlock.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="videoBlock[show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('videoBlock.show_market_ru') }}</span>
                                    </div>
                                
                              
												
                                </div>
                            </div>
                           

                            <hr />
                            <div class=" text-right">
                                <button form="video_block-edit-form" value="0" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{route('admin.video_blocks.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </form>
								
								<button type="submit" form="videoBlock-delete-form-{{$videoBlock->id}}"
								class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>
							<form id="videoBlock-delete-form-{{$videoBlock->id}}"
									action="{{route('admin.video_blocks.destroy', $videoBlock)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
