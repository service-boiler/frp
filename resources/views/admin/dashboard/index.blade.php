@extends('layouts.app')
</div>
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.dashboards')</li>
        </ol>
        
        @alert()@endalert
        <div class="row mb-4">

            <div class="col-md-4">

                <!-- Tasks -->
                <div class="card mb-4">
                    <h6 class="card-header"><i class="fa fa-dashboard"></i> @lang('site::messages.dashboards')</h6>
                    <div class="list-group">

                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.dashboards.asc_scs') }}">
                            <i class="fa fa-wrench"></i> Сервисы и региональные склады
                        </a>
                        
                    </div>
                    <div class="list-group">

                        <a class="list-group-item list-group-item-action py-1" href="{{ route('admin.dashboards.tenders') }}">
                            <i class="fa fa-legal"></i> Тендеры
                        </a>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endsection
