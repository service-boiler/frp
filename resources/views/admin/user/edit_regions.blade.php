@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.index') }}">@lang('site::user.users')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.users.show', $user) }}">{{ $user->name }}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.edit_regions')</li>
        </ol>

        @alert()@endalert()

        <div class="row justify-content-center my-4">
            <div class="col-md-12">
                <form id="user-form" method="POST"
                      action="{{ route('admin.users.update_regions', $user) }}">

                    @csrf
                    @method('PUT')
                    <div class="card mb-4">
                        <div class="card-body">

                            
                            

                            <div class="form-row required">
                                <div class="col-sm-6 mb-3">
                                    <label class="control-label"
                                           for="user_region_id">@lang('site::user.select_region_for_add')</label>
                                    <select class="form-control
                                            {{$errors->has('user.region_id') ? ' is-invalid' : ''}}"
                                            required
                                            name="region_id"
                                            id="user_region_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($districts as $district)
                                            <optgroup label="{{$district->name}}">
                                                @foreach($district->regions()->orderBy('name')->get() as $region)
                                                    <option
                                                            @if(old('user.region_id', $user->region_id) == $region->id)
                                                            selected 
                                                            @endif
                                                            value="{{ $region->id }}"
                                                            data-name="{{$region->name}}">{{ $region->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('user.region_id') }}</strong>
                                    </span>
                                </div>
                                <div class="col-sm-6 text-right">
                                    <button type="submit" class="btn btn-ms">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                        <i class="fa fa-close"></i>
                                        <span>@lang('site::messages.cancel')</span>
                                    </a>
                                </div>
                            </div>

                           
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body" id="regions">
                            @foreach($userRegions as $region)
                                <div class="row mb-3" id="{{$region->id}}">
                                    <div class="col-5">
                                        <input type="hidden" name="regions[]" value="{{$region->id}}">
                                        {{$region->name}}
                                    </div>
                                    <div class="col-5">
                                         <a href="javascript:void(0);" onclick="$(this).parent().parent().remove()"   class="btn btn-danger"> <i class="fa fa-close"></i></a>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        try {
            window.addEventListener('load', function () {

                let user_region_id = $('#user_region_id');


                user_region_id.on('change', function (selected) {
                    console.log(selected.target.options[selected.target.selectedIndex].dataset.name);
                    $('#regions').append('<div class="row mb-3" id="'+$(this).val()+'"><div class="col-5"><input type="hidden" name="regions[]" value="'+$(this).val()+'">'+selected.target.options[selected.target.selectedIndex].dataset.name+'</div><div class="col-5"><a href="javascript:void(0);" onclick="$(this).parent().parent().remove()"   class="btn btn-danger"> <i class="fa fa-close"></i></a></div></div>');
                    
                });
            });
        } catch (e) {
            console.log(e);
        }

    </script>
@endpush