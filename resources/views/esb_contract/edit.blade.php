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
                <a href="{{ route('esb-contracts.show', $esbContract) }}">{{$esbContract->number}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>

        <div class="row">
            <div class="col-sm-8">
                <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::contract.contract')  {{ $esbContract->number }}</h1>
            </div>
            <div class="col-sm-4 mb-2 text-right">
                <button form="contract-form" type="submit"
                        class="btn btn-ms mb-1">
                    <i class="fa fa-check"></i>
                    <span>@lang('site::messages.save')</span>
                </button>
                <a href="{{ route('contracts.show', $esbContract) }}" class="btn btn-secondary mb-1">
                    <i class="fa fa-close"></i>
                    <span>@lang('site::messages.cancel')</span>
                </a>
    
            </div>
        </div>
        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="contract-form" method="POST"
                      action="{{ route('esb-contracts.update', $esbContract) }}">
                    @csrf
                    @method('PUT')

                    <div class="form-row mt-3">
                        <div class="col-sm-2 form-group required">
                            <label class="control-label" for="name">@lang('site::contract.number')</label>
                            <input required
                                   type="text"
                                   name="contract[number]"
                                   id="number"
                                   class="form-control{{ $errors->has('contract.number') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contract.placeholder.number')"
                                   value="{{ old('contract.number', $esbContract->number) }}">
                            <span class="invalid-feedback">{{ $errors->first('contract.number') }}</span>
                        </div>

                        <div class="col-sm-2 form-group required">
                            <label class="control-label"
                                   for="date">@lang('site::contract.date')</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date"
                                 data-target-input="nearest">
                                <input type="text"
                                       name="contract[date]"
                                       id="date"
                                       maxlength="10"
                                       required
                                       placeholder="@lang('site::contract.placeholder.date')"
                                       data-target="#datetimepicker_date"
                                       data-toggle="datetimepicker"
                                       class="datetimepicker-input form-control{{ $errors->has('contract.date') ? ' is-invalid' : '' }}"
                                       value="{{ old('contract.date', optional($esbContract->date)->format('d.m.Y')) }}">
                                <div class="input-group-append"
                                     data-target="#datetimepicker_date"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('contract.date') }}</span>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label"
                                   for="date_from">Срок действия с:</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_from"
                                 data-target-input="nearest">
                                <input type="text"
                                       name="contract[date_from]"
                                       id="date_from"
                                       maxlength="10"
                                       placeholder="@lang('site::contract.placeholder.date')"
                                       data-target="#datetimepicker_date"
                                       data-toggle="datetimepicker"
                                       class="datetimepicker-input form-control{{ $errors->has('contract.date_from') ? ' is-invalid' : '' }}"
                                       value="{{ old('contract.date_from', optional($esbContract->date_from)->format('d.m.Y')) }}">
                                <div class="input-group-append"
                                     data-target="#datetimepicker_date_from"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('contract.date_from') }}</span>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label"
                                   for="date_to">Срок действия по:</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_to"
                                 data-target-input="nearest">
                                <input type="text"
                                       name="contract[date_to]"
                                       id="date_to"
                                       maxlength="10"
                                       placeholder="@lang('site::contract.placeholder.date')"
                                       data-target="#datetimepicker_date"
                                       data-toggle="datetimepicker"
                                       class="datetimepicker-input form-control{{ $errors->has('contract.date_to') ? ' is-invalid' : '' }}"
                                       value="{{ old('contract.date_to', optional($esbContract->date_to)->format('d.m.Y')) }}">
                                <div class="input-group-append"
                                     data-target="#datetimepicker_date_to"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('contract.date_to') }}</span>
                        </div>

                        <div class="col-sm-4  form-group required">
                            <label class="control-label"
                                   for="template_id">Шаблон договора</label>
                            <select required
                                    class="form-control{{  $errors->has('contract.template_id') ? ' is-invalid' : '' }}"
                                    name="contract[template_id]"
                                    id="template_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                            @foreach($templates as $template)
                                <option value="{{ $template->id }}" @if(old('contract.template_id',$esbContract->template_id)==$template->id) selected @endif>{{ $template->name }}</option>
                            @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.type_id') }}</span>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="service_id">Исполнитель</label>
                            <select class="form-control{{  $errors->has('contract.service_id') ? ' is-invalid' : '' }}"
                                    name="contract[service_id]"
                                    id="service_contragent_id">
                                    <option selected  value="{{ $esbContract->service_id }}">{{ optional($esbContract->service)->name }} </option>
                                    <option value="{{ auth()->user()->company()->id }}">{{ auth()->user()->company()->name }} </option>

                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.service_contragent_id') }}</span>
                        </div>
                       <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="service_contragent_id">Юр. лицо исполнителя</label>
                           <div class="input-group">
                                <select class="form-control{{  $errors->has('contract.service_contragent_id') ? ' is-invalid' : '' }}"
                                        name="contract[service_contragent_id]"
                                        id="service_contragent_id">
                                    @if(auth()->user()->contragents()->count() == 0 || auth()->user()->contragents()->count() > 1)
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                    @endif
                                    @foreach(auth()->user()->contragents as $contragent)
                                        <option
                                                @if(old('contract.service_contragent_id',$esbContract->service_contragent_id) == $contragent->id) selected
                                                @endif
                                                value="{{ $contragent->id }}">{{ $contragent->name }}
                                        </option>
                                    @endforeach
                                </select>
                               <div class="input-group-append">
                                   <div class="input-group-text">
                                       <a href="{{route('contragents.show',$esbContract->service_contragent_id)}}"><i class="fa fa-external-link"></i></a>
                                   </div>
                               </div>
                           </div>
                            <span class="invalid-feedback">{{ $errors->first('contract.service_contragent_id') }}</span>
                        </div>

                        <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="client_user_id">Заказчик</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('contract.client_user_id') ? ' is-invalid' : '' }}"
                                        name="contract[client_user_id]"
                                        id="client_user_id">
                                    <option selected value="{{ $esbContract->client_user_id }}">{{ $esbContract->esbUser->name_filtred }} </option>

                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        {{$esbContract->esbUser->type->name_short}}
                                    </div>
                                </div>
                            </div>
                            <span class="invalid-feedback">{{ $errors->first('contract.client_user_id') }}</span>
                        </div>
                        <div class="col-sm-6">
                            <label class="control-label"
                                   for="esb_contragent_id">Юр. лицо заказчика</label>
                            <select class="form-control{{  $errors->has('contract.esb_contragent_id') ? ' is-invalid' : '' }}"
                                    name="contract[esb_contragent_id]"
                                    id="esb_contragent_id">
                                @if($esbContract->esbUser->contragents()->count() == 0 || $esbContract->esbUser->contragents()->count() > 1)
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                @endif
                                @foreach($esbContract->esbUser->contragents as $contragent)
                                    <option
                                            @if(old('contract.contragent_id') == $contragent->id) selected
                                            @endif
                                            value="{{ $contragent->id }}">{{ $contragent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.contragent_id') }}</span>
                        </div>
                    </div>
                    <hr/>
                    <div class="form-row" id="fields">
                        <div class="col-12">
                            <span class="font-weight-bold">Дополнительные поля для подстановки в шаблон:</span>
                        </div>
                    @if($esbContract->template)
                        @foreach($esbContract->template->esbContractFields as $field)
                                <div class="col-12">
                                    <label class="control-label"
                                           for="contract_fields_{{$field->id}}">{{'${'.$field->name.'} '.$field->title}}</label>
                                    <input class="form-control{{  $errors->has('contract.esb_contragent_id') ? ' is-invalid' : '' }}"
                                           type="text"
                                            name="contract_fields[{{$field->id}}]"
                                            id="contract_fields_{{$field->id}}"
                                           value="{{$esbContract->getFieldValue($field)}}">

                                    <span class="invalid-feedback">{{ $errors->first('contract.contragent_id') }}</span>
                                </div>

                        @endforeach
                        <div class="col-12">
                            <span class="font-weight-bold">Предопределенные поля для подстановки в шаблон:</span>
                        </div>
                        @foreach($esbContract->template->presetFields()->get() as $field)
                            <div class="col-12">
                                <label class="control-label"
                                       for="contract_fields_{{$field->id}}">{{'${'.$field->name.'} '.$field->title}}</label>
                                <input class="form-control{{  $errors->has('contract.esb_contragent_id') ? ' is-invalid' : '' }}"
                                       type="text" @if($field->calculated)disabled @endif
                                       name="contract_fields[{{$field->id}}]"
                                       id="contract_fields_{{$field->id}}"
                                value="{{$esbContract->getFieldValue($field)}}">

                                <span class="invalid-feedback">{{ $errors->first('contract.contragent_id') }}</span>
                            </div>
                        @endforeach
                    @else
                        <span class="ml-sm-3 text-danger font-weight-bold">Шаблон договора не назначен. Укажите шаблон, сохраните договор и откройте снова.</span>
                    @endif
                    </div>
                </form>


            </div>
        </div>

    </div>
@endsection
@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {
            $(document)
                .on('change', '#template_id', (function(I){
                    $('#fields').html('<span class="text-danger">Шаблон договора был изменен. Сохраните договор и после будет доступно изменение дополнительных полей</span>');
                }))

        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush