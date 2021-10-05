@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::region.regions')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::region.icon')"></i> @lang('site::region.regions')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.regions.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::region.region')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @pagination(['pagination' => $regions])@endpagination
        {{$regions->render()}}

		<div class="card mb-2">
            <div class="card-body">
                <table class="table table-sm ">
                    <thead>
                    <tr>
                        <th>@lang('site::region.region')</th>
                        <th>@lang('site::region.notification_address')</th>
                        <th>@lang('site::region.region_id')</th>
                        <th>@lang('site::region.italy_district_id')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
		
					@foreach($regions as $region)
					<tr>
						<td><a href="{{ route('admin.regions.edit', $region) }}">{{ $region->name }}</a></td>
						<td>{{ $region->notification_address }}</td>
						<td>{{ $region->id }}</td>
						<td>@if($region->italy_district) {{  $region->italy_district->id }} / {{ $region->italy_district->name }} @endif</td>
						
					</tr>
					@endforeach
					
					</tbody>
                </table>
            </div>
        </div>
        {{$regions->render()}}
    </div>
@endsection
