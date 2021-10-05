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
                <a href="{{ route('admin.ticket-themes.index') }}">@lang('site::ticket.theme.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::ticket.theme.ticket_theme_edit')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.ticket-themes.update',$ticketTheme) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::ticket.theme.name')</label>
                                    <input type="text" name="ticketTheme[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           value="{{old('ticketTheme.name',$ticketTheme->name)}}"
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name_feedback">@lang('site::ticket.theme.name_feedback')</label>
                                    <input type="text" name="ticketTheme[name_feedback]"
                                           id="name_feedback"
                                           class="form-control{{ $errors->has('name_feedback') ? ' is-invalid' : '' }}"
                                           value="{{old('ticketTheme.name_feedback',$ticketTheme->name_feedback)}}"
                                    <span class="invalid-feedback">{{ $errors->first('name_feedback') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                
                        <div class="col">

                            <div class="form-group">
                                <label class="control-label" for="default_receiver_id">
                                    @lang('site::ticket.theme.default_receiver_id')
                                </label>
                                <select id="list_receiver_id"
                                        name="ticketTheme[default_receiver_id]"
                                        style="width:100%"
                                        class="form-control">
                                    <option></option>
                                    @foreach($users as $user)
                                        <option @if(old('ticketTheme.default_receiver_id',$ticketTheme->default_receiver_id) == $user->id)
                                                selected
                                                @endif
                                                value="{{$user->id}}">
                                            {{$user->name}}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('ticketTheme.default_receiver_id') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                                
                        <div class="col">
                            <label class="control-label" for="default_text">@lang('site::ticket.text')(Шаблон)</label>
                                <textarea 
                                      name="ticketTheme[default_text]"
                                      id="default_text"
                                      class="form-control{{ $errors->has('ticketTheme.default_text') ? ' is-invalid' : '' }}"
                                      >{{ old('ticketTheme.default_text',$ticketTheme->default_text) }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('ticketTheme.default_text') }}</span>
                            
                        </div>
                    </div> 
                    <div class="form-row">
                                
                        
                        <div class="col-md-3">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox mt-4 ml-3">
                                        <input type="checkbox"
                                               @if(old('ticketTheme.for_feedback',$ticketTheme->for_feedback)) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('ticketTheme.for_feedback') ? ' is-invalid' : '' }}"
                                               id="for_feedback"
                                               name="ticketTheme[for_feedback]">
                                        <label class="custom-control-label"
                                               for="for_feedback">@lang('site::ticket.for_feedback')</label>
                                        <span class="invalid-feedback">{{ $errors->first('ticketTheme.for_feedback') }}</span>
                                    </div>
                                </div> 
                            </div>       
                        </div>         
                        <div class="col-md-3">
                            <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox mt-4 ml-3">
                                        <input type="checkbox"
                                               @if(old('ticketTheme.for_manager',$ticketTheme->for_manager)) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('ticketTheme.for_manager') ? ' is-invalid' : '' }}"
                                               id="for_manager"
                                               name="ticketTheme[for_manager]">
                                        <label class="custom-control-label"
                                               for="for_manager">@lang('site::ticket.for_manager')</label>
                                        <span class="invalid-feedback">{{ $errors->first('ticketTheme.for_manager') }}</span>
                                    </div>
                                </div> 
                            </div>       
                        </div> 
                    </div>          
                            
                </form>
					 
                <hr/>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('admin.ticket-themes.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection