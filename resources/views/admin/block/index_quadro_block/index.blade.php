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
            <li class="breadcrumb-item active">@lang('site::admin.index_quadro_blocks_index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.index_quadro_block_icon')"></i> @lang('site::admin.index_quadro_blocks_index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.index_quadro_blocks.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.index_quadro_block')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
            <ul class="list-group" data-target="{{route('admin.index_quadro_blocks.sort')}}" id="sort-list">
										@foreach($indexQuadroBlocks as $indexQuadroBlock)
            
                                    <li class="sort-item list-group-item p-2" data-id="{{$indexQuadroBlock->id}}">
                                        <i class="fa fa-arrows"></i>&nbsp;&nbsp;<strong>{{$indexQuadroBlock->sort_order}}</strong> &nbsp;
                                        <div style="min-width: 200px; display: inline-block;">
                                       @if(!empty($indexQuadroBlock->image)) <img style="width: 100px;" src="{{$indexQuadroBlock->image->src()}}")>@endif
                                        <a href="{{route('admin.index_quadro_blocks.edit', $indexQuadroBlock)}}">{{ $indexQuadroBlock->title }}<br />
                                        @if(!empty($indexQuadroBlock->image)){{formatImageDimension(Storage::disk($indexQuadroBlock->image->storage)->url($indexQuadroBlock->image->path))}}@endif</a>
                                        </div>
													 <p><span>{{$indexQuadroBlock->text}}<br />
                                                     <span>{{$indexQuadroBlock->text_hover}}</p>
                                                     <p><span>@bool(['bool' => $indexQuadroBlock->enabled])@endbool</span> @lang('site::messages.enabled')
                                                     <br /><span>{{$indexQuadroBlock->url}}</p>
                                                     
                                    </li>
                                @endforeach
                            </ul>
		<div class="row mt-4">
                     <img src="/images/index-quadro-sample.jpg">
                     </div>
    </div>
    
@endsection
