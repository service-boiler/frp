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
                <a href="{{route('admin.index_cards_blocks.index') }}">@lang('site::admin.index_cards_blocks_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$indexCardsBlock->title}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$indexCardsBlock->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="form"
                              action="{{route('admin.index_cards_blocks.update', $indexCardsBlock) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="url">@lang('site::admin.index_cards_blocks_url')</label>
                                    <input type="text" name="indexCardsBlock[url]"
                                           id="url"
                                           required
                                           class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.url')"
                                           value="{{ old('url', $indexCardsBlock->url) }}">
                                    <span class="invalid-feedback">{{ $errors->first('url') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.index_cards_block_title')</label>
                                    <input type="text" name="indexCardsBlock[title]"
                                           id="title"
                                           required
                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.title')"
                                           value="{{ old('title', $indexCardsBlock->title) }}">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>
                            <div class="form-row required">
                                <div class="col mb-3 col-xl-4">
                                    <label class="control-label" for="text">@lang('site::admin.index_cards_block_text')</label>
                                         
                                    <textarea name="indexCardsBlock[text]" required
                                      id="text"
                                      rows="5"
                                      class="form-control{{ $errors->has('text') ? ' is-invalid' : '' }}"
                                      >{!! $indexCardsBlock->text !!}
                                      
                                    </textarea>         
                                           
                                           
                                    <span class="invalid-feedback">{{ $errors->first('text') }}</span>
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('indexCardsBlock.show_ferroli', $indexCardsBlock->show_ferroli)) checked @endif
                                               class="custom-control-input{{  $errors->has('indexCardsBlock.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="indexCardsBlock[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('indexCardsBlock.show_ferroli') }}</span>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('indexCardsBlock.show_market_ru', $indexCardsBlock->show_market_ru)) checked @endif
                                               class="custom-control-input{{  $errors->has('indexCardsBlock.show_market_ru') ? ' is-invalid' : '' }}"
                                               id="show_market_ru"
                                               name="indexCardsBlock[show_market_ru]">
                                        <label class="custom-control-label"
                                               for="show_market_ru">@lang('site::messages.show_market_ru')</label>
                                        <span class="invalid-feedback">{{ $errors->first('indexCardsBlock.show_market_ru') }}</span>
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
                                <a href="{{route('admin.index_cards_blocks.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            
                        
                        <button type="submit" form="indexCardsBlock-delete-form-{{$indexCardsBlock->id}}"
								class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>
                            </div>
							<form id="indexCardsBlock-delete-form-{{$indexCardsBlock->id}}"
									action="{{route('admin.index_cards_blocks.destroy', $indexCardsBlock)}}"
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
                                           value="index_cards"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.index_cards.accept')}}"
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
