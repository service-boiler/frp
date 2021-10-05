@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.faqs_index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.video_block_icon')"></i> @lang('site::admin.faqs_index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.faq.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.video_block')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $faqEntries])@endpagination
        {{$faqEntries->render()}}
		  
		    <ul class="list-group" data-target="{{route('admin.faq.sort')}}" id="sort-list">
										@foreach($faqEntries as $entry)
            
                                    <li class="sort-item list-group-item p-2" data-id="{{$entry->id}}">
                                        <i class="fa fa-arrows"></i>
                                        <div style="min-width: 550px; display: inline-block;"><a href="{{route('admin.faq.show', $entry)}}">{{ $entry->title }}</a></div>
													 
                                    </li>
                                @endforeach
                            </ul>
			
				
				
        {{$faqEntries->render()}}
    </div>
@endsection
