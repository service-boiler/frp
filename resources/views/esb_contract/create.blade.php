@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('contracts.index') }}">@lang('site::contract.contracts')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.create') @lang('site::contract.contract')</h1>

        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="form-content" method="POST" action="{{ route('esb-contracts.store') }}">
                    @csrf
                    <div class="form-row mt-3">

                        <div class="col-sm-4  form-group required">
                            <label class="control-label"
                                   for="template_id">Шаблон договора</label>
                            <select required
                                    class="form-control{{  $errors->has('contract.template_id') ? ' is-invalid' : '' }}"
                                    name="contract[template_id]"
                                    id="template_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($templates as $template)
                                    <option data-prefix="{{$template->prefix}}" value="{{ $template->id }}" @if(old('contract.template_id')) selected @endif>{{ $template->name }}</option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.type_id') }}</span>
                        </div>

                        <div class="col-sm-2 form-group required">
                            <label class="control-label" for="number">@lang('site::contract.number')</label>
                            <input required
                                   type="text"
                                   name="contract[number]"
                                   id="number"
                                   class="form-control{{ $errors->has('contract.number') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contract.placeholder.number')"
                                   value="{{ old('contract.number', $max_id) }}">
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
                                       value="{{ old('contract.date', optional($date)->format('d.m.Y')) }}">
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
                                       value="{{ old('contract.date_from', optional($date)->format('d.m.Y'))}}">
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
                                       value="{{ old('contract.date_to', optional($date_to)->format('d.m.Y')) }}">
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


                    </div>
                    <div class="form-row">
                        <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="service_id">Исполнитель</label>
                            <select class="form-control{{  $errors->has('contract.service_id') ? ' is-invalid' : '' }}"
                                    name="contract[service_id]"
                                    id="service_id">
                                <option selected  value="{{ auth()->user()->id }}">{{ auth()->user()->name }} </option>

                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.service_contragent_id') }}</span>
                        </div>
                        <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="service_contragent_id">Юр. лицо исполнителя</label>
                            <select class="form-control{{  $errors->has('contract.service_contragent_id') ? ' is-invalid' : '' }}"
                                    name="contract[service_contragent_id]"
                                    id="service_contragent_id">
                                @if(auth()->user()->contragents()->count() == 0 || auth()->user()->contragents()->count() > 1)
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                @endif
                                @foreach(auth()->user()->contragents as $contragent)
                                    <option
                                            @if(old('contract.service_contragent_id') == $contragent->id) selected
                                            @endif
                                            value="{{ $contragent->id }}">{{ $contragent->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('contract.service_contragent_id') }}</span>
                        </div>
                        <div class="col-sm-6 form-group required">
                            <label class="control-label"
                                   for="client_user_id">Заказчик</label>
                            <div class="input-group">
                                <select class="form-control{{  $errors->has('contract.client_user_id') ? ' is-invalid' : '' }}"
                                        name="contract[client_user_id]"
                                        id="client_user_id">
                                    <option selected value="{{ $esbClient->id }}">{{ $esbClient->name_filtred }} </option>

                                </select>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        {{$esbClient->type->name_short}}
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
                                @if($esbClient->contragents()->count() == 0 || $esbClient->contragents()->count() > 1)
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                @endif
                                @foreach($esbClient->contragents as $contragent)
                                    <option
                                            @if(old('contract.esb_contragent_id') == $contragent->id) selected
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
                           <span class="ml-sm-3 text-danger font-weight-bold">Шаблон договора не назначен. Укажите шаблон, сохраните договор и откройте на изменение договора.</span>

                    </div>
                </form>
                <hr/>
                <div class=" mb-2 text-right">
                    <button form="form-content" type="submit"
                            class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('contracts.index') }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>

                </div>
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
                        console.log($('#number').val());
                        console.log($('option:selected',this).data('prefix'));
                        $('#number').val($('option:selected',this).data('prefix') + $('#number').val());
                    }))

            });
        } catch (e) {
            console.log(e);
        }

    </script>
@endpush