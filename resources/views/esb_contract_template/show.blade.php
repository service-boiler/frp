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
                <a href="{{ route('esb-contract-templates.index') }}">@lang('site::user.esb_contract_template.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $esbContractTemplate->name }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $esbContractTemplate->name }}</h1>
        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('esb-contract-templates.edit', $esbContractTemplate) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::user.esb_contract_template.edit')</span>
            </a>
            @if($esbContractTemplate->file && $esbContractTemplate->file->exists())
                <a class="btn btn-success d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                   href="{{route('files.show', $esbContractTemplate->file)}}">
                    <i class="fa fa-download"></i>
                    @lang('site::messages.download')
                </a>
            @endif
            <a href="{{ route('esb-contract-templates.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <button type="button" class="btn btn-danger  btn-row-delete py-1 mr-0 ml-sm-5 mb-1 mb-sm-0"
               data-form="#delete-form" data-btn-delete="Удалить"
               data-btn-cancel="Отмена"
               data-label="Подтверждение удаления"
               data-message="Вы действительно хотите удалить шаблон?"
               data-toggle="modal"
               data-target="#form-modal"
               @cannot('delete', $esbContractTemplate)
               disabled
               @endcannot

               href="javascript:void(0);" title="Удалить">
                <i class="fa fa-close"></i>Удалить</button>
            <form id="delete-form" action="{{route('esb-contract-templates.destroy',$esbContractTemplate)}}" method="POST">
                @csrf
                @method('DELETE')
            </form>

        </div>
        <div class="card mb-2">
            <div class="card-body">
                <dl class="row">

                    <dt class="col-sm-5 text-left text-sm-right">@lang('site::user.esb_contract_template.enabled')</dt>
                    <dd class="col-sm-7">@bool(['bool' => $esbContractTemplate->enabled])@endbool</dd>

                    <dt class="col-sm-5 text-left text-sm-right">@lang('site::user.esb_contract_template.name')</dt>
                    <dd class="col-sm-7">{{$esbContractTemplate->name}}</dd>

                    <dt class="col-sm-5 text-left text-sm-right">Префикс автоматической нумерации</dt>
                    <dd class="col-sm-7">{{$esbContractTemplate->prefix}}</dd>
                    @if($esbContractTemplate->file)
                        <dt class="col-sm-5 mt-3 text-left text-sm-right">@lang('site::file.path') / @lang('site::file.storage')</dt>
                        <dd class="col-sm-7 mt-3">
                            {!! $esbContractTemplate->file->name !!} / {{$esbContractTemplate->file->path}}
                            @if(!$esbContractTemplate->file->exists())
                                <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                            @endif
                        </dd>

                    @else
                        <dd class="col-sm-7">
                            <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                        </dd>
                    @endif
                    <dt class="col-sm-5 text-left text-sm-right"><span class="font-weight-bold">Дополнительные поля для подстановки в шаблон:</span></dt>
                    <dd class="col-sm-7"></dd>
                    @foreach($esbContractTemplate->esbContractFields as $field)
                        <dt class="col-sm-5 text-left text-sm-right">{{'${'.$field->name.'}'}}</dt>
                        <dd class="col-sm-7">{{$field->title}}</dd>
                    @endforeach
                    <dt class="col-sm-5 text-left text-sm-right"><span class="font-weight-bold">Предопределенные поля для подстановки в шаблон:</span></dt>
                    <dd class="col-sm-7"></dd>
                    @foreach($esbContractTemplate->presetFields()->get() as $field)
                        <dt class="col-sm-5 text-left text-sm-right">{{'${'.$field->name.'}'}}</dt>
                        <dd class="col-sm-7">{{$field->title}}</dd>
                    @endforeach
                    <dt class="col-sm-5 text-left text-sm-right"><span class="font-weight-bold">Договоры:</span></dt>
                    <dd class="col-sm-7"></dd>
                    @foreach($esbContractTemplate->esbContracts()->get() as $contract)
                        <dt class="col-sm-5 text-left text-sm-right"><a href="{{route('esb-contracts.show', $contract)}}" target="_blank">
                            {{$contract->number ? $contract->number : $contract->id}}</dt>
                        <dd class="col-sm-7">{{$contract->esbUser ? $contract->esbUser->name_filtred : $contract->title }}</a></dd>
                    @endforeach

                </dl>

            </div>
        </div>
    </div>
@endsection
