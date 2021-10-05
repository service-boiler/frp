@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
           
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>

        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="user-form" method="POST"
                      action="{{ route('update_profile') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="user_name">@lang('site::user.company_or_name')</label>
                            <input type="text"
                                   name="name"
                                   id="name" required
                                   class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.name')"
                                   value="{{ old('name', $user->name) }}">
                            <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="email">@lang('site::user.email')</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.email')"
                                   value="{{ old('email', $user->email) }}">
                            <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="phone">@lang('site::user.phone')</label>
                            <input type="text"
                                   name="phone"
                                   id="phone"
                                   class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.placeholder.phone')"
                                   value="{{ old('phone', $user->phone) }}">
                            <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col required">

                            <label class="control-label" for="region_id">Регион</label>
                            <select class="form-control{{  $errors->has('region_id') ? ' is-invalid' : '' }}"
                                    name="region_id"
                                    required 
                                    id="region_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($regions as $region)
                                    <option
                                            @if(old('region_id', $user->region_id) == $region->id)
                                            selected
                                            @endif
                                            value="{{ $region->id }}">{{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('region_id') }}</span>
                        </div>
                    </div>
                   
            </div>

                </form>
                <hr/>
                <div class=" mb-2 text-right">
                    <button form="user-form" type="submit"
                            class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('home') }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>

                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
<script>

 try {
            window.addEventListener('load', function () {

                let user_type_id_1 = $('#user_type_id_1');
                let user_type_id_2 = $('#user_type_id_2');
                
                 
                user_type_id_1.on('change', function (e) {
                    $('#user_kpp').prop('disabled', false);
                }); 
                user_type_id_2.on('change', function (e) {
                    $('#user_kpp').prop('disabled', true);
                }); 
                
               
                

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush