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
            <li class="breadcrumb-item active">@lang('site::region.region_italy_districts')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::region_italy_district.icon')"></i> @lang('site::region.region_italy_districts')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.region-italy-districts.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::region.region_italy_district')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @pagination(['pagination' => $region_italy_districts])@endpagination
        {{$region_italy_districts->render()}}

		<div class="card mb-2">
            <div class="card-body">
                <table class="table table-sm ">
                    <thead>
                    <tr>
                        <th>@lang('site::region.region_italy_district')</th>
                        <th>@lang('site::region.region_italy_district_id')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
		
					@foreach($region_italy_districts as $region_italy_district)
					<tr>
						<td><a href="{{ route('admin.region-italy-districts.edit', $region_italy_district) }}">{{ $region_italy_district->name }}</a></td>
						<td>{{ $region_italy_district->id }}</td>
						
					</tr>
					@endforeach
					
					</tbody>
                </table>
            </div>
        </div>
        {{$region_italy_districts->render()}}
    </div>
@endsection
