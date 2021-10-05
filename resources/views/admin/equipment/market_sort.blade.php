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
            <li class="breadcrumb-item active">@lang('site::equipment.equipments_sort_menu')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::equipment.icon')"></i> @lang('site::equipment.equipments_sort_menu')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.equipments.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::equipment.equipment')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $equipments])@endpagination
        {{$equipments->render()}}
		  
		    <ul class="list-group" data-target="{{route('admin.equipments.sort-menu')}}" id="sort-list">
										@foreach($equipments as $equipment)
            
                                    <li class="sort-item list-group-item p-2" data-id="{{$equipment->id}}">
                                        <i class="fa fa-arrows"></i>
                                        <div style="min-width: 250px; display: inline-block;"><a href="{{route('admin.equipments.show', $equipment)}}">{{ $equipment->name }}</a></div>
													 <p><span>@bool(['bool' => $equipment->show_market_ru])@endbool</span> @lang('site::messages.show_market_ru')</p>
													 <p><span>@bool(['bool' => $equipment->show_ferroli])@endbool</span> @lang('site::messages.show_ferroli')</p>
                                    </li>
                                @endforeach
                            </ul>
			
				
				
        {{$equipments->render()}}
    </div>
@endsection
