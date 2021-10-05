@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::region.region_biz_districts')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::region_biz_district.icon')"></i> @lang('site::region.region_biz_districts')
        </h1>

        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.region-biz-districts.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::region.region_biz_district')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary mr-0 mr-sm-1 mb-1 mb-sm-0">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
            <a href="{{ route('admin.regions.index') }}" class="d-block d-sm-inline btn btn-primary mr-0 mr-sm-1 mb-1 mb-sm-0">
                <i class="fa fa-@lang('site::address.icon')"></i>
                <span>@lang('site::region.regions')</span>
            </a>
        </div>


		<div class="card mb-2">
            <div class="card-body">
                <table class="table table-sm ">
                    <thead>
                    <tr>
                        <th>@lang('site::region.region_biz_district')</th>
                        <th>@lang('site::region.region_biz_district_sort_order')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
		
					@foreach($regionBizDistricts as $region_biz_district)
					<tr>
						<td><a href="{{ route('admin.region-biz-districts.edit', $region_biz_district) }}">{{ $region_biz_district->name }}</a></td>
						<td>{{ $region_biz_district->sort_order }}</td>
						
					</tr>
					@endforeach
					
					</tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
