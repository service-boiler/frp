@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.webinars.index') }}">@lang('site::admin.webinar.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::admin.webinar.webinar_add')</h1>

        @alert() @endalert

        <div class="card mb-5">
            <div class="card-body" id="summernote">
                <form id="form" method="POST" action="{{ route('ferroli-user.webinars.store') }}">
                    @csrf
                     <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               checked 
                                               class="custom-control-input{{  $errors->has('webinar.enabled') ? ' is-invalid' : '' }}"
                                               id="enabled"
                                               name="webinar[enabled]">
                                        <label class="custom-control-label"
                                               for="enabled">@lang('site::messages.enabled')</label>
                                        <span class="invalid-feedback">{{ $errors->first('webinar.enabled') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group required" id="form-group-theme_id">
                                <label class="control-label" for="theme_id">@lang('site::admin.webinar.theme') <span class="text-muted">(@lang('site::admin.webinar.theme_help'))</span></label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('webinar.theme_id') ? ' is-invalid' : '' }}"
                                            name="webinar[theme_id]"
                                            id="theme_id"
                                            required >
                                        
                                        
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($themes as $theme)
                                            <option @if((isset($theme_id) && $theme_id == $theme->id) || session('theme_id') == $theme->id || old('webinar.theme_id') == $theme->id)
                                                    selected
                                                    @endif
                                                    value="{{ $theme->id }}">
                                                {{ $theme->name }} &nbsp;&nbsp;  &nbsp;&nbsp; @lang('site::admin.promocodes.promocode') : @if(!empty($theme->promocode))
                                                                       {{ $theme->promocode->name }} ({{ $theme->promocode->bonuses }} @lang('site::admin.bonus_val'),  
                                                                        @lang('site::admin.promocodes.expiry_at') {{ $theme->promocode->expiry ? $theme->promocode->expiry->format('d.m.Y') : 'без срока'}} )
                                                                       @endif
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <a  href="{{ route('admin.webinar-themes.create') }}" role="button">Создать новую тему вебинаров</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('webinar.theme_id') }}</span>
                            </div>
                            
                            <div class="form-group required" id="form-group-type_id">
                                <label class="control-label" for="type_id">@lang('site::admin.webinar.type_id')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('webinar.type_id') ? ' is-invalid' : '' }}" required
                                            name="webinar[type_id]"
                                            id="type_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($types as $type)
                                            <option @if(old('webinar.type_id') == $type->id)
                                                    selected
                                                    @endif
                                                    value="{{ $type->id }}">
                                                {{ $type->name }} 
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    
                                </div>
                            </div>
                            
                            <div class="form-group required" id="form-group-service_name">
                                <label class="control-label" for="service_name">Платформа вебинара</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('webinar.service_name') ? ' is-invalid' : '' }}" required
                                            name="webinar[service_name]"
                                            id="service_name">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        
                                        <option value="zoom">Zoom</option>
                                        <option value="proofme">ProofMe</option>
                                        
                                    </select>
                                    
                                </div>
                            </div>
                                                       
                            
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.webinar.name')</label>
                                    <input type="text" name="webinar[name]" id="name" required
                                           class="form-control{{ $errors->has('webinar.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')"
                                           value="{{ old('webinar.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                            </div>                 
                            
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="link_service">@lang('site::admin.webinar.link_service'), либо ссылка на приглашение Zoom</label>
                                    <input type="text" name="webinar[link_service]" id="link_service"
                                           class="form-control{{ $errors->has('webinar.link_service') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.webinar.link_service_placeholder')"
                                           value="{{ old('webinar.link_service') }}">
                                    <span class="invalid-feedback">{{ $errors->first('webinar.link_service') }}</span>
                                    <span class="text-success">Для Zoom заполняется автоматически</span>
                                </div>
                            </div>
                            
                            
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="id_service">@lang('site::admin.webinar.id_service')</label>
                                    <input type="text" name="webinar[id_service]" id="id_service"
                                           class="form-control{{ $errors->has('webinar.id_service') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.webinar.id_service_placeholder')"
                                           value="{{ old('webinar.id_service') }}">
                                    <span class="invalid-feedback">{{ $errors->first('webinar.id_service') }}</span>
                                    <span class="text-success">Для Zoom заполняется автоматически</span>
                                </div>
                            </div>
                            
                            
                            <div class="form-group" id="form-group-promocode_id">
                                <label class="control-label" for="promocode_id">@lang('site::admin.webinar.promocode') (@lang('site::admin.webinar.promocode_help'))</label>
                                <div class="input-group">
                                    <select data-form-action="{{ route('admin.promocodes.create') }}"
                                            data-btn-ok="@lang('site::messages.save')"
                                            data-btn-cancel="@lang('site::messages.cancel')"
                                            data-options="#promocode_id_options"
                                            data-label="@lang('site::messages.add') @lang('site::admin.promocode')"
                                            class="dynamic-modal-form form-control{{  $errors->has('webinar.promocode_id') ? ' is-invalid' : '' }}"
                                            name="webinar[promocode_id]"
                                            id="promocode_id">
                                        
                                        
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($promocodes as $promocode)
                                            <option @if(isset($promocode_id) && $promocode_id == $promocode->id)
                                                    selected
                                                    @endif
                                                    value="{{ $promocode->id }}">
                                                {{ $promocode->name }} &nbsp;&nbsp; ({{ $promocode->bonuses }} @lang('site::admin.bonus_val'),  @lang('site::admin.promocodes.expiry_at') {{ $promocode->expiry ? $promocode->expiry->format('d.m.Y') : 'без срока'}} )
                                            </option>
                                        @endforeach
                                        <optgroup>
                                            <option value="load">✚ @lang('site::messages.add')</option>
                                        </optgroup>
                                        
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-@lang('site::trade.icon')"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('webinar.promocode_id') }}</span>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label"
                                       for="datetime">@lang('site::admin.webinar.datetime')</label>
                                <div class="input-group date datetimepickerfull" id="datetimepicker_date"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="webinar[datetime]"
                                           id="datetime"
                                           placeholder="@lang('site::admin.webinar.datetime_ph')"
                                           data-target="#datetimepicker_date"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('webinar.datetime') ? ' is-invalid' : '' }}"
                                           value="{{ old('webinar.datetime') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                            
                                
                                
                                <span class="invalid-feedback">{{ $errors->first('webinar.datetime') }}</span>
                            </div>
                            
                            
                             <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="annotation">@lang('site::admin.webinar.annotation')</label>
                                    <textarea class="form-control{{ $errors->has('webinar.annotation') ? ' is-invalid' : '' }}"
                                              required
                                              name="webinar[annotation]" id="annotation">{{ old('webinar.annotation') }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('webinar.annotation') }}</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label"
                                       for="description">@lang('site::admin.webinar.description')</label>
                                <textarea name="webinar[description]"
                                          id="description"
                                          class="summernote form-control{{ $errors->has('webinar.description') ? ' is-invalid' : '' }}"
                                          placeholder="@lang('site::webinar.placeholder.description')">{{ old('webinar.description') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('webinar.description') }}</span>
                            </div>
                            
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::messages.comment')</label>
                                    <input type="text" name="webinar[comment]"
                                           id="comment"
                                           class="form-control{{ $errors->has('comment') ? ' is-invalid' : '' }}"
                                           value="{{ old('webinar.comment') }}">
                                    <span class="invalid-feedback">{{ $errors->first('comment') }}</span>
                                </div>
                            </div>

                            
                </form>
					 
                 <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="image_id">@lang('site::admin.webinar.image_id')</label>

                                <form method="POST"
                                      enctype="multipart/form-data"
                                      action="{{route('admin.images.store')}}">
                                    @csrf
                                    <input type="hidden"
                                           name="storage"
                                           value="webinars"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.webinars.accept')}}"
                                           name="path"/>

                                    <input type="button" class="btn btn-ms image-upload-button"
                                           value="@lang('site::messages.load')"/>
                                    <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="images" class="row bg-white">
                            @include('site::admin.image.edit')
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('ferroli-user.webinars.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection