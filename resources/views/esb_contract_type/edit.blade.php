@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-contract-types.index') }}">@lang('site::user.esb_contract_type.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_contract_type.new')</h1>

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
                    <div class="card mb-2">
                        <div class="card-body">
                        
                            <form id="form"
                                  method="POST"
                                  action="{{ route('esb-contract-types.update', $esbContractType) }}">
                                @csrf
                                @method('PUT')
                           
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label"
                                               for="name">@lang('site::user.esb_contract_type.name')</label>
                                    
                                    <input type="text"
                                           name="name"
                                           id="name"
                                           maxlength="200"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{ old('name', $esbContractType->name) }}"
                                           placeholder="@lang('site::user.esb_contract_type.name_placeholder')">
                                    
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                        
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-6">

                                    <label class="control-label" for="template_id">@lang('site::user.esb_contract_type.template_id')</label>
                                    <select class="form-control{{  $errors->has('template_id') ? ' is-invalid' : '' }}"
                                            name="template_id"
                                            id="template_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @if($templates)
                                            @foreach($templates as $template)
                                                <option
                                                        @if(old('template_id', $esbContractType->template_id) == $template->id)
                                                        selected
                                                        @endif
                                                        value="{{ $template->id }}">{{ $template->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('template_id') }}</span>
                                    
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label"
                                               for="color">@lang('site::user.esb_contract_type.color')</label>
                                    
                                    <input type="text"
                                           name="color"
                                           id="color"
                                           maxlength="200"
                                           class="form-control{{ $errors->has('color') ? ' is-invalid' : '' }}"
                                           value="{{ old('color', $esbContractType->color) }}">
                                    
                                    <span class="invalid-feedback">{{ $errors->first('color') }}</span>
                                        
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="enabled">@lang('site::user.esb_contract_type.enabled')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="enabled_1"
                                                       name="enabled"
                                                       
                                                       @if(old('enabled', $esbContractType->enabled) == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('enabled') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="enabled_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="enabled_0"
                                                       name="enabled"
                                                       
                                                       @if(old('enabled', $esbContractType->enabled) == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('enabled') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="enabled_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('enabled') }}</span>
                                        </div>
                                    </div>
                                        
                                </div>
                                @if(auth()->user()->hasPermission('admin_esb_super') || auth()->user()->admin)
                                <div class="col-sm-2">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="shared">@lang('site::user.esb_contract_type.shared')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="shared_1"
                                                       name="shared"
                                                       
                                                       @if(old('shared', $esbContractType->shared) == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('shared') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="shared_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="shared_0"
                                                       name="shared"
                                                       
                                                       @if(old('shared', $esbContractType->shared) == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('shared') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="shared_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('shared') }}</span>
                                        </div>
                                    </div>
                                        
                                </div>
                                @endif
                            </div>
                           
                            <div class="form-row">
                                
                                    <div class="col mb-3">
                                        <label class="control-label" for="comments">@lang('site::user.esb_contract_type.comments')</label>
                                        <textarea
                                              name="comments"
                                              id="comments"
                                              class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              >{{ old('comments', $esbContractType->comments) }}</textarea>
                                        <span class="invalid-feedback">{{ $errors->first('comments') }}</span>
                                    </div>
                            </div>
                        </form>
                        
                        
                                    

                            <div class="form-row">
                                <div class="col text-right">
                                    <button form="form" type="submit"
                                            class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span id="save_btn">
                                        @lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('esb-contract-types.index') }}" class="btn btn-secondary mb-1">
                                        <i class="fa fa-close"></i>
                                        <span>@lang('site::messages.cancel')</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            
    </div>
@endsection

