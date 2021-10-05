@extends('layouts.app')
@section('title')@lang('site::messages.add') @lang('site::admin.customer.add')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.customers.index') }}">@lang('site::admin.customer.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.customers.show',$customer) }}">{{$customer->name}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.customer.contact_edit') {{$contact->name}}</li>
        </ol>
        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('admin.customer_contact.update',['customer'=>$customer, 'contact'=>$contact]) }}">
                    @csrf
                    
                    @method('PUT')
                    <div class="form-row">
                                <div class="col">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('lpr',$contact->lpr)) checked @endif
                                               class="custom-control-input{{  $errors->has('lpr') ? ' is-invalid' : '' }}"
                                               id="lpr"
                                               name="lpr">
                                        <label class="custom-control-label"
                                               for="lpr">@lang('site::admin.customer.lpr')</label>
                                        <span class="invalid-feedback">{{ $errors->first('lpr') }}</span>
                                    </div>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">Имя</label>
                                    <input type="text" name="contact[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            value="{{ old('contact.name',$contact->name) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="position">Должность</label>
                                    <input type="text" name="contact[position]"
                                           id="position"
                                           class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}"
                                            value="{{ old('contact.position',$contact->position) }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('contact.position') }}</span>
                                </div>
                    </div>
                            
                    
                    
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="phone">@lang('site::admin.customer.phone_contact')</label>
                                    <input 
                                           type="tel"
                                           name="contact[phone]"
                                           id="phone"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('contact.number') ? ' is-invalid' : (old('contact.phone') ? ' is-valid' : '') }}"
                                           value="{{ old('contact.phone',$contact->phone) }}">
                                    <span class="invalid-feedback">{{ $errors->first('contact.phone') }}</span>
                                </div>
                    </div>   
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="email">@lang('site::admin.customer.email_contact')</label>
                                    <input type="text" name="contact[email]"
                                           id="email"
                                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                                           value="{{ old('contact.email',$contact->email) }}">
                                    <span class="invalid-feedback">{{ $errors->first('contact.email') }}</span>
                                </div>
                    </div>   
                    <div class="form-row">
                                <div class="col mb-1">
                                    <label class="control-label" for="any_contacts">@lang('site::admin.customer.any_contacts_contact')</label>
                                    <textarea name="contact[any_contacts]"
                                           id="any_contacts"
                                           class="form-control{{ $errors->has('contact.any_contacts') ? ' is-invalid' : '' }}"
                                           >{{ old('contact.any_contacts',$contact->any_contacts) }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('contact.any_contacts') }}</span>
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
                        <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection