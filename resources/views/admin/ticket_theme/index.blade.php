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
            <li class="breadcrumb-item active">@lang('site::ticket.theme.index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::ticket.theme.icon')"></i> @lang('site::ticket.theme.index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.ticket-themes.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::ticket.theme.ticket_theme_add')</span>
            </a>
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        <div class="border p-3 mb-2">
            
										@foreach($ticketThemes as $ticketTheme)
            
                                    <div class="row mb-2">
                                        <div class="col-sm-4">
                                        <a href="{{route('admin.ticket-themes.edit', $ticketTheme)}}"><i class="fa fa-edit"></i> {{ $ticketTheme->name }}</a>
                                        </div>
                                       
													 
                                    </div>
                                @endforeach
            
			
		</div>
    </div>
@endsection
