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
            <li class="breadcrumb-item active">@lang('site::admin.promocodes.index')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.promocode_icon')"></i> @lang('site::admin.promocodes.index')
        </h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.promocodes.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.promocodes.promocode')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        <div class="border p-3 mb-2">
        <div class="row mb-2">
                                        <div class="col-sm-4">
                                       Название промокода
                                        </div>
                                        <div class="col-sm-1">
                                        Короткий код
                                        </div>
                                        <div class="col-sm-1">
                                        Сумма баллов
                                        </div>
                                        <div class="col-sm-2">
                                        Срок действия
                                        </div>
                                        <div class="col-sm-5">
                                        Комментарии
                                        </div>
                                       
													 
                                    </div>
            
										@foreach($promocodes as $promocode)
                                    
                                    <div class="row mb-2">
                                        <div class="col-sm-4">
                                        <a href="{{route('admin.promocodes.edit', $promocode)}}">{{ $promocode->name }}</a>
                                        </div>
                                        <div class="col-sm-1">
                                        {{ $promocode->short_token ? $promocode->short_token : '-' }}
                                        </div>
                                        <div class="col-sm-1">
                                        {{ $promocode->bonuses }}
                                        </div>
                                        <div class="col-sm-2">
                                        {{ $promocode->expiry ? $promocode->expiry->format('d.m.Y') : 'без срока'}}
                                        </div>
                                        <div class="col-sm-4">
                                        {{ $promocode->comment }}
                                        </div>
                                       
													 
                                    </div>
                                @endforeach
            
			
		</div>
    </div>
@endsection
