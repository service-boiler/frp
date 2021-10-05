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
		<li class="breadcrumb-item">
			<a href="{{ route('admin.regions.index') }}">@lang('site::region.regions')</a>
		</li>
		
		<li class="breadcrumb-item active">@lang('site::messages.edit')</li>
	</ol>
	<h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::region.region')</h1>

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
			
			<form id="form-content"
				method="POST"
				enctype="multipart/form-data"
				action="{{ route('admin.regions.update', $region) }}">

				@csrf
				@method('PUT')
			</form>

			<form method="POST" id="page-edit-form"
				action="{{ route('admin.regions.update', $region) }}">
				@csrf
				@method('PUT')

				<div class="form-row required">
					<div class="col mb-3">
						<label class="control-label" for="id">@lang('site::region.name')</label>
						<input type="text" name="region[name]"
						   id="region_name"
						   required
						   class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}"
						   placeholder="@lang('site::region.name')"
						   value="{{ old('id', $region->name) }}">
						<span class="invalid-feedback">{{ $errors->first('name') }}</span>
					</div>
				</div>
				<div class="form-row required">
					<div class="col mb-3">
						<label class="control-label" for="id">@lang('site::region.notification_address')</label>
						<input type="text" name="region[notification_address]"
						   id="notification_address"
						   required
						   class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}"
						   placeholder="@lang('site::region.notification_address')"
						   value="{{ old('id', $region->notification_address) }}">
						<span class="invalid-feedback">{{ $errors->first('notification_address') }}</span>
					</div>
				</div>
				
				 <div class="form-row required">
                                <div class="col mb-3 required">

                                    <label class="control-label"
                                           for="italy_district_id">@lang('site::region.region_italy_district')</label>
                                    <select class="form-control{{  $errors->has('region.italy_district_id') ? ' is-invalid' : '' }}"
                                            name="region[italy_district_id]"
                                            
                                            id="italy_district_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($region_italy_districts as $region_italy_district)
                                            <option
                                                    @if(old('region.italy_district_id', $region->italy_district_id) == $region_italy_district->id)
                                                    selected
                                                    @endif
                                                    value="{{ $region_italy_district->id }}">{{ $region_italy_district->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('region.italy_district_id') }}</span>
                                </div>
                            </div>

				<hr />
				<div class=" text-right">

					<button name="_stay" form="page-edit-form" value="0" type="submit" class="btn btn-ms">
						<i class="fa fa-check"></i>
						<span>@lang('site::messages.save')</span>
					</button>
					<a href="{{ route('admin.regions.index') }}" class="d-page d-sm-inline btn btn-secondary">
						<i class="fa fa-close"></i>
						<span>@lang('site::messages.cancel')</span>
					</a>
				</div>
			</form>
			

		</div>
	</div>
</div>
@endsection
