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
                <a href="{{route('admin.promocodes.index') }}">@lang('site::admin.promocodes_index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit') {{$promocode->title}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$promocode->name}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" id="form"
                              action="{{route('admin.promocodes.update', $promocode) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.promocodes_name')</label>
                                    <input type="text" name="promocode[name]"
                                           id="name"
                                           required
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')"
                                           value="{{ old('name', $promocode->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocodes_bonuses')</label>
                                    <input type="number" name="promocode[bonuses]"
                                           id="bonuses"
                                           required
                                           class="form-control{{ $errors->has('bonuses') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.bonuses')"
                                           value="{{ old('bonuses', $promocode->bonuses) }}">
                                    <span class="invalid-feedback">{{ $errors->first('bonuses') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocode_comment')</label>
                                    <input type="text" name="promocode[comment]"
                                           id="comment"
                                           
                                           class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.comment')"
                                           value="{{ old('comment', $promocode->comment) }}">
                                    <span class="invalid-feedback">{{ $errors->first('comment') }}</span>
                                </div>
                            </div>


                           
                        </form>
                            <hr />
                            <div class=" text-right">
                                <button form="form" value="0" type="submit" class="btn btn-ms">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{route('admin.promocodes.index') }}" class="d-page d-sm-inline btn btn-secondary">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            
                        
                        <button type="submit" form="promocode-delete-form-{{$promocode->id}}" @cannot('delete', $promocode) disabled @endcannot
								class="ml-5 btn btn-danger d-block d-sm-inline @cannot('delete', $promocode) disabled @endcannot" title="@lang('site::messages.delete')">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>
                            </div>
							<form id="promocode-delete-form-{{$promocode->id}}"
									action="{{route('admin.promocodes.destroy', $promocode)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
                        
					
                </div>
								
							
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
