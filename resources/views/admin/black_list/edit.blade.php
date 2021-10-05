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
                <a href="{{ route('admin.black-list.index') }}">@lang('site::admin.black_list.black_list')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$blackList->name}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$blackList->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="black_list-edit-form"
                              action="{{ route('admin.black-list.update', $blackList) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="route">@lang('site::admin.black_list.web')</label>
                                    <input type="text" name="web"
                                           id="web"
                                           required
                                           class="form-control{{ $errors->has('web') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.black_list.placeholder.web')"
                                           value="{{ old('web', $blackList->web) }}">
                                    <span class="invalid-feedback">{{ $errors->first('web') }}</span>
                                </div>
                            </div>
                           

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="route">@lang('site::admin.black_list.name')</label>
                                    <input type="text" name="name"
                                           id="name"
                                           
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.black_list.placeholder.name')"
                                           value="{{ old('name', $blackList->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>
                           

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="route">@lang('site::admin.black_list.full')</label>
                                    <input type="text" name="full"
                                           id="full"
                                           
                                           class="form-control{{ $errors->has('full') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.black_list.placeholder.full')"
                                           value="{{ old('full', $blackList->full) }}">
                                    <span class="invalid-feedback">{{ $errors->first('full') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="route">@lang('site::admin.black_list.comment')</label>
                                    <input type="text" name="comment"
                                           id="comment"
                                           
                                           class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                           value="{{ old('comment', $blackList->comment) }}">
                                    <span class="invalid-feedback">{{ $errors->first('comment') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('active', $blackList->active)) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('active') ? ' is-invalid' : '' }}"
                                               id="active"
                                               name="active">
                                        <label class="custom-control-label"
                                               for="active">@lang('site::messages.active')</label>
                                        <span class="invalid-feedback">{{ $errors->first('active') }}</span>
                                    </div>
                                </div>
                            </div>
                            </form>
                            <hr />
                            <div class=" text-right">
                                <button name="_stay" form="black_list-edit-form" value="1" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save_stay')</span>
                                </button>
                                <button name="_stay" form="black_list-edit-form" value="0" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <button type="submit" form="black_list-delete-form-{{$blackList->id}}"
                                        class="ml-1 btn btn-danger d-block d-sm-inline" title="@lang('site::messages.delete')">
                                    <i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
                                </button>
                                <a href="{{ route('admin.black-list.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                                
                               
                            </div>
                        
                         <form id="black_list-delete-form-{{$blackList->id}}"
                                      action="{{route('admin.black-list.destroy', $blackList)}}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                         </form>
                         
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
