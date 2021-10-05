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
            <li class="breadcrumb-item active">@lang('site::admin.head_banner_blocks_index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.head_banner_block_icon')"></i> @lang('site::admin.head_banner_blocks_index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.head_banner_blocks.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.head_banner_block')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
            <ul class="list-group" data-target="{{route('admin.head_banner_blocks.sort')}}" id="sort-list">
										@foreach($headBannerBlocks as $headBannerBlock)
            
                                    <li class="sort-item list-group-item p-2" data-id="{{$headBannerBlock->id}}">
                                        <i class="fa fa-arrows"></i>
                                        <div style="min-width: 550px; display: inline-block;">
                                        <img style="height: 100px;" src="{{$headBannerBlock->image->src()}}")>
                                        <a href="{{route('admin.head_banner_blocks.edit', $headBannerBlock)}}">{{ $headBannerBlock->title }}</a>
                                        </div>
													 <p><span>@bool(['bool' => $headBannerBlock->show_market_ru])@endbool</span> @lang('site::messages.show_market_ru')</p>
													 <p><span>@bool(['bool' => $headBannerBlock->show_ferroli])@endbool</span> @lang('site::messages.show_ferroli')</p>
                                                     <p><span>@lang('site::admin.head_banner_block_path'): {{$headBannerBlock->path}}</p>
                                    </li>
                                @endforeach
                            </ul>
			
		
    </div>
@endsection
