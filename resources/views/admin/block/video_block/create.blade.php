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
                <a href="{{ route('admin.video_blocks.index') }}">@lang('site::admin.video_blocks_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::admin.video_block')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('admin.video_blocks.store') }}">
                    @csrf
                    <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="url">@lang('site::admin.video_block_url')</label>
                                    <input type="text" name="videoBlock[url]"
                                           id="url"
                                           required
                                           class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.url')">
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
                                           placeholder="@lang('site::admin.placeholder.title')">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               checked
                                               class="custom-control-input{{  $errors->has('videoBlock.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="videoBlock[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('videoBlock.show_ferroli') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               checked
                                               class="custom-control-input{{  $errors->has('videoBlock.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="videoBlock[show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('videoBlock.show_market_ru') }}</span>
                                    </div>
                                
                              
												
                                </div>
                            </div>
                </form>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form-content" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.video_blocks.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection