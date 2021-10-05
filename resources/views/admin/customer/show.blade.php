@extends('layouts.app')
@section('title'){{ $customer->name }} @lang('site::admin.customer.index')@lang('site::messages.title_separator')@endsection
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
                <a href="{{ route('admin.customers.index') }}">@lang('site::admin.customer.index')</a>
            </li>
            <li class="breadcrumb-item active">{{$customer->name}}</li>
        </ol>
      
         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('admin.customers.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.customers.edit', $customer) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::admin.customer.edit')</span>
            </a>
            
            <a class="btn btn-primary" href="{{route('admin.customer_contact.create',['customer'=>$customer])}}"><i class="fa fa-plus"></i> Добавить контактное лицо</a>
           
                            <a class="btn btn-danger btn-row-delete "
                                           title="@lang('site::messages.delete')"
                                           href="javascript:void(0);"
                                           data-form="#customer-delete-form-{{$customer->id}}"
                                           data-btn-delete="@lang('site::messages.delete')"
                                           data-btn-cancel="@lang('site::messages.cancel')"
                                           data-label="@lang('site::messages.delete_confirm')"
                                           data-message="@lang('site::messages.delete_sure') {{ $customer->name }}?"
                                           data-toggle="modal"
                                           data-target="#form-modal">
                                            <i class="fa fa-close"></i>
                                            @lang('site::messages.delete')
                                        </a>

        </div>
        <form id="customer-delete-form-{{$customer->id}}"
									action="{{route('admin.customers.destroy', $customer)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    
        <div class="card mb-2">
            <div class="card-body">
                    <dl class="row mb-1">
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.name_show'):</dt>
                    <dd class="col-sm-9">{!!$customer->name!!}</dd>
                    
                    
                    @foreach($customer->customerRoles as $role)
                                    <dt class="col-sm-3 text-left text-sm-right"></dt><dd class="col-9"><i class="fa fa-check text-success"></i> {{$role->title}}</dd>
                                @endforeach
                     
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.region_locality'):</dt>
                    <dd class="col-sm-9">@if($customer->region){{$customer->region->name}}, {{$customer->locality}}@endif</dd>
                    
                    @if(!empty($customer->phone))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.phone'):</dt>
                    <dd class="col-sm-9">{!!$customer->phone!!}</dd>
                    @endif
                    
                    @if(!empty($customer->email))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.email'):</dt>
                    <dd class="col-sm-9">{!!$customer->email!!}</dd>
                    @endif
                    @if(!empty($customer->any_contacts))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.any_contacts'):</dt>
                    <dd class="col-sm-9">{{$customer->any_contacts}}</dd>
                    @endif
                    @if(!empty($customer->comment))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.comment'):</dt>
                    <dd class="col-sm-9">{!!$customer->comment!!}</dd>
                    @endif
                     
                </dl>
                
                
                </div>
            </div>
            <h5>Контактные лица</h5> 
            @foreach($customer->contacts as $contact)
            <div class="card mb-2" id="contact-{{$contact->id}}">
                <div class="card-body">
                    <dl class="row mb-1">
                                    <dt class="col-sm-3 text-left text-sm-right">Имя</dt><dd class="col-9"> <b>{{$contact->name}}</b></dd>
                                    <dt class="col-sm-3 text-left text-sm-right">Должность</dt><dd class="col-9"> {{$contact->position}} @if($contact->lpr)<span class="text-success">@lang('site::admin.customer.lpr')</span>@endif</dd>
                                    @if(!empty($contact->phone))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.phone_contact'):</dt>
                                    <dd class="col-sm-9">{!!$contact->phone!!}</dd>
                                    @endif
                                    
                                    @if(!empty($contact->email))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.email_contact'):</dt>
                                    <dd class="col-sm-9">{!!$contact->email!!}</dd>
                                    @endif
                                    @if(!empty($contact->any_contacts))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.any_contacts_contact'):</dt>
                                    <dd class="col-sm-9">{{$contact->any_contacts}}</dd>
                                    @endif
                                    <dt class="col-sm-3 text-left text-sm-right"></dt>
                                    <dd class="col-sm-9"><a class="btn btn-primary" href="{{route('admin.customer_contact.edit',['customer'=>$customer, 'contact'=>$contact])}}"><i class="fa fa-pencil"></i>Изменить</a>
                                    
                                                <a class="btn btn-danger btn-row-delete "
                                           title="@lang('site::messages.delete')"
                                           href="javascript:void(0);"
                                           data-form="#contact-delete-form-{{$contact->id}}"
                                           data-btn-delete="@lang('site::messages.delete')"
                                           data-btn-cancel="@lang('site::messages.cancel')"
                                           data-label="@lang('site::messages.delete_confirm')"
                                           data-message="@lang('site::messages.delete_sure') {{ $contact->name }}?"
                                           data-toggle="modal"
                                           data-target="#form-modal">
                                            <i class="fa fa-close"></i>
                                            @lang('site::messages.delete')
                                        </a>
                                        <form id="contact-delete-form-{{$contact->id}}"
                                              action="{{route('admin.customer_contact.destroy', ['customer'=>$customer, 'contact'=>$contact])}}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                        </form> 
                                    
                                    </dd>
                      
                     
                </dl>
                </div>
            </div>
             @endforeach
        </div>
    </div>
@endsection
