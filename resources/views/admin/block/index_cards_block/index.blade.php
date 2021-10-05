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
            <li class="breadcrumb-item active">@lang('site::admin.index_cards_blocks_index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.index_cards_block_icon')"></i> @lang('site::admin.index_cards_blocks_index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.index_cards_blocks.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.index_cards_block')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $indexCardsBlocks])@endpagination
        {{$indexCardsBlocks->render()}}
		  
		    <ul class="list-group" data-target="{{route('admin.index_cards_blocks.sort')}}" id="sort-list">
										@foreach($indexCardsBlocks as $indexCardsBlock)
            
                                    <li class="sort-item list-group-item p-2" data-id="{{$indexCardsBlock->id}}">
                                        <i class="fa fa-arrows"></i>
                                        <div style="min-width: 550px; display: inline-block;">
                                        <img style="height: 100px;" src="{{$indexCardsBlock->image->src()}}")>
                                        <a href="{{route('admin.index_cards_blocks.edit', $indexCardsBlock)}}">{{ $indexCardsBlock->title }}</a>
                                        </div>
													 <p><span>@bool(['bool' => $indexCardsBlock->show_market_ru])@endbool</span> @lang('site::messages.show_market_ru')</p>
													 <p><span>@bool(['bool' => $indexCardsBlock->show_ferroli])@endbool</span> @lang('site::messages.show_ferroli')</p>
                                                     <p><span>{{$indexCardsBlock->url}}</p>
                                    </li>
                                @endforeach
                            </ul>
			
				
				
        {{$indexCardsBlocks->render()}}
    </div>
@endsection
