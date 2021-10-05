@extends('layouts.app')

@section('content')
<div class="container">
	<ol class="breadcrumb">
		<li class="breadcrumb-item">
			<a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
		</li>
		<li class="breadcrumb-item">
			<a href="{{ route('admin.region-biz-districts.index') }}">@lang('site::region.region_biz_districts')</a>
		</li>
		
		<li class="breadcrumb-item active">@lang('site::messages.edit')</li>
	</ol>
	<h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::region.region_biz_district')</h1>

	@alert()@endalert()
	@if ($errors->any())
		<div class="alert alert-danger" role="alert">
			<h4 class="alert-heading">@lang('site::messages.has_error')</h4>
			<ul>
				@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif
	
	
	<div class="card">
		<div class="card-body">
			
			<div class=" text-right">
				<button type="submit" form="region-biz-district-delete-form-{{$regionBizDistrict->id}}"
					class="ml-5 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
					<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
				</button>
				<form id="region-biz-district-delete-form-{{$regionBizDistrict->id}}"
					action="{{route('admin.region-biz-districts.destroy', $regionBizDistrict)}}"
					method="POST">
					@csrf
					@method('DELETE')
					</form>
			</div>
			

			
			<form id="form-content"
				method="POST"
				enctype="multipart/form-data"
				action="{{ route('admin.region-biz-districts.update', $regionBizDistrict) }}">

				@csrf
				@method('PUT')
			</form>

			<form method="POST" id="page-edit-form"
				action="{{ route('admin.region-biz-districts.update', $regionBizDistrict) }}">
				@csrf
				@method('PUT')

				<div class="form-row required">
					<div class="col mb-3">
						<label class="control-label" for="id">@lang('site::region.region_biz_district_name')</label>
						<input type="text" name="region_biz_district[name]"
						   id="region_biz_district_name"
						   required
						   class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}"
						   placeholder="@lang('site::region.region_biz_district_name')"
						   value="{{ old('id', $regionBizDistrict->name) }}">
						<span class="invalid-feedback">{{ $errors->first('name') }}</span>
					</div>
				</div>
				<div class="form-row required">
					<div class="col mb-3">
						<label class="control-label"
							   for="region_biz_district_sort_order">@lang('site::region.region_biz_district_sort_order')</label>
						<input type="text"
							   name="region_biz_district[sort_order]"
							   id="region_biz_district_sort_order"
							   required
							   class="form-control{{$errors->has('region_biz_district.sort_order') ? ' is-invalid' : ''}}"
							   value="{{ old('region_biz_district.sort_order',$regionBizDistrict->sort_order) }}">
						<span class="invalid-feedback">{{ $errors->first('region_biz_district.sort_order') }}</span>
					</div>
				</div>

				<hr />
				<div class=" text-right">

					<button name="_stay" form="page-edit-form" value="0" type="submit" class="btn btn-ms">
						<i class="fa fa-check"></i>
						<span>@lang('site::messages.save')</span>
					</button>
					<a href="{{ route('admin.region-biz-districts.index') }}" class="d-page d-sm-inline btn btn-secondary">
						<i class="fa fa-close"></i>
						<span>@lang('site::messages.cancel')</span>
					</a>
				</div>
			</form>
			

		</div>
	</div>
</div>
@endsection
