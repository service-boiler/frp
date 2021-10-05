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
            <li class="breadcrumb-item active">ID типа договра: {{$esbContractType->id}}</li>
        </ol>
        <h1 class="header-title mb-4">{{$esbContractType->name}}</h1>
        @alert()@endalert()
        <div class="p-3 mb-0">
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-contract-types.edit', $esbContractType) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::user.esb_contract_type.contract_type')</span>
            </a>
            <a href="{{ route('esb-contract-types.index') }}" class="d-block d-sm-inline-block btn btn-secondary mr-sm-3">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            
            <button
            class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 btn btn-danger btn-row-delete mt-1"
                    data-form="#delete-form"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::user.esb_contract_type.contract_type')? "
                    data-toggle="modal" data-target="#form-modal"
                    title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i>
                @lang('site::messages.delete')
            </button>
            <form id="delete-form"
                      method="POST"
                      action="{{ route('esb-contract-types.destroy',$esbContractType) }}">
                    @csrf
                    @method('DELETE')
                    
                    </form>
        </div>
        <div class="card mb-4">
            <div class="card-body">

                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_contract_type.name')</dt>
                    <dd class="col-sm-8">{{ $esbContractType->name }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_contract_type.template_id')</dt>
                    <dd class="col-sm-8">{{ $esbContractType->template ? $esbContractType->template->name : 'не задан' }}
                    
                    @if($esbContractType->template && $esbContractType->template->file->exists())
                            <a class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                               href="{{route('files.show', $esbContractType->template->file)}}">
                                <i class="fa fa-download"></i>
                                
                            </a>
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_contract_type.enabled')</dt>
                    <dd class="col-sm-8">{{ $esbContractType->enabled ? 'ДА' : 'НЕТ'}}</dd>

                     @if(auth()->user()->hasPermission('admin_esb_super') || auth()->user()->admin)
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_contract_type.shared')</dt>
                    <dd class="col-sm-8">{{ $esbContractType->shared ? 'ДА' : 'НЕТ'}}</dd>
                    @endif

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_contract_type.comments')</dt>
                    <dd class="col-sm-8">{{ $esbContractType->comments}}</dd>

                    
                </dl>
                
                
            </div>
        </div>
    </div>
@endsection
