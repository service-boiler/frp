@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-contracts.index') }}">Договоры с клиентами</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.show',$esbContract->esbUser) }}">{{optional($esbContract->esbUser)->name}}</a>
            </li>
            <li class="breadcrumb-item active">{{ $esbContract->number }}</li>
        </ol>
        
        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <a class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn btn-ms"
               href="{{route('esb-contracts.edit', $esbContract)}}">
                @lang('site::messages.edit')
            </a>
            @if($esbContract->template)
            <a class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn btn-success"
               href="{{route('esb-contracts.download', $esbContract)}}">
                @lang('site::contract.help.download')
            </a>
            @endif
            <button @cannot('delete', $esbContract) disabled @endcannot
            class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-danger btn-row-delete"
                    data-form="#contract-delete-form-{{$esbContract->id}}"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::contract.contract')? "
                    data-toggle="modal" data-target="#form-modal"
                    title="@lang('site::messages.delete')">
                <i class="fa fa-close"></i>
                @lang('site::messages.delete')
            </button>
            <a href="{{ route('contracts.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>

        </div>
        <form id="contract-delete-form-{{$esbContract->id}}"
              action="{{route('contracts.destroy', $esbContract)}}"
              method="POST">
            @csrf
            @method('DELETE')
        </form>
        <div class="card mb-4">
            <div class="card-body">
                <dl class="row">

                    <dt class="col-sm-5 text-left text-sm-right">@lang('site::messages.created_at')</dt>
                    <dd class="col-sm-7">{{ $esbContract->created_at->format('d.m.Y H:i') }}</dd>

                    <dt class="col-sm-5 text-left text-sm-right">@lang('site::contract.date')</dt>
                    <dd class="col-sm-7">{{ optional($esbContract->date)->format('d.m.Y') ?: 'не указана'}}</dd>

                    <dt class="col-sm-5 text-left text-sm-right">Срок действия</dt>
                    <dd class="col-sm-7">
                        c: {{ optional($esbContract->date_from)->format('d.m.Y') ?: 'не указана'}},
                        по:  {{ optional($esbContract->date_to)->format('d.m.Y') ?: 'не указана'}}
                    </dd>

                    @if($esbContract->contragent)
                        <dt class="col-sm-5 text-left text-sm-right">@lang('site::contract.contragent_id')</dt>
                        <dd class="col-sm-7">
                            <a href="{{route('contragents.show', $esbContract->contragent)}}">
                                {{ $esbContract->contragent->name }} <i class="fa fa-external-link"></i>
                            </a>
                        </dd>
                    @endif

                    <dt class="col-sm-5 text-left text-sm-right">Шаблон договора</dt>


                    @if($esbContract->template)
                        <dd class="col-sm-7">
                                <a target="_blank" href="{{route('esb-contract-templates.show',$esbContract->template)}}">{{$esbContract->template->name}}
                                    <i class="fa fa-external-link"></i></a>
                        </dd>
                        <dt class="col-sm-5 text-left text-sm-right"><span class="font-weight-bold">Дополнительные поля для подстановки в шаблон:</span></dt>
                        <dd class="col-sm-7"></dd>
                        @foreach($esbContract->template->esbContractFields as $field)
                            <dt class="col-sm-5 text-left text-sm-right mb-2">{{'${'.$field->name.'} '.$field->title}}</dt>
                            <dd class="col-sm-7 mb-2">{{$esbContract->getFieldValue($field)}}</dd>

                        @endforeach

                        <dt class="col-sm-5 text-left text-sm-right"><span class="font-weight-bold">Предопределенные поля для подстановки в шаблон:</span></dt>
                        <dd class="col-sm-7"></dd>

                        @foreach($esbContract->template->presetFields()->get() as $field)

                                <dt class="col-sm-5 text-left text-sm-right mb-2">{{'${'.$field->name.'} '.$field->title}}</dt>
                                <dd class="col-sm-7 mb-2">{{$esbContract->getFieldValue($field)}}</dd>

                        @endforeach
                    @else
                        <dd class="col-sm-7">
                            <b>Шаблон договора не назначен</b>
                        </dd>

                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
