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
                <a href="{{ route('repairs.index') }}">@lang('site::repair.repairs')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.create') @lang('site::repair.repair')</h1>
        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <button form="form"
                    type="submit"
                    class="btn btn-ms">
                <i class="fa fa-check"></i>
                <span>@lang('site::messages.save')</span>
            </button>
            <a href="{{ route('repairs.index') }}" class="btn btn-secondary">
                <i class="fa fa-close"></i>
                <span>@lang('site::messages.cancel')</span>
            </a>
        </div>
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
        @if(!$user->has_contract)
        <div class="card mb-4 card-body bg-danger text-white px-2 d-inline-block">
        Внимание! Оригинал договора не получен, отчет не сможет быть одобрен. Пожалуйста, пришлите оригинал подписанного договора по адресу 141014, Московская обл., г. Мытищи, а/я 482.
        Шаблон договора Вы можете сформировать в личном кабинете в разделе <a style="color: white; font-weight: bold;" href="{{route('contracts.index')}}">Договоры <i class="fa fa-external-link"></i>(ссылка)</a>
        </div>
        @endif
        <div class="row justify-content-center mb-5">
            <div class="col">
                <form id="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('repairs.store') }}">
                    @csrf
                    {{-- КЛИЕНТ --}}
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::repair.header.client')</h5>
                            <div class="form-group mt-2 required">
                                <label class="control-label" for="client">@lang('site::repair.client')</label>
                                <input required
                                       type="text"
                                       id="client"
                                       name="repair[client]"
                                       class="form-control{{ $errors->has('repair.client') ? ' is-invalid' : '' }}"
                                       value="{{ old('repair.client') }}"
                                       placeholder="@lang('site::repair.placeholder.client')">
                                <span class="invalid-feedback">{{ $errors->first('repair.client') }}</span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label" for="address">@lang('site::repair.address')</label>
                                <div class="input-group">
                                    <input required
                                           type="text"
                                           id="address"
                                           name="repair[address]"
                                           class="form-control{{ $errors->has('repair.address') ? ' is-invalid' : '' }}"
                                           value="{{ old('repair.address') }}"
                                           placeholder="@lang('site::repair.placeholder.address')"/>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-map-marker"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.address') }}</span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label"
                                       for="country_id">@lang('site::repair.country_id')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('repair.country_id') ? ' is-invalid' : '' }}"
                                            required
                                            name="repair[country_id]"
                                            id="country_id">
                                        @if($countries->count() == 0 || $countries->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($countries as $country)
                                            <option
                                                    @if(old('repair.country_id') == $country->id) selected
                                                    @endif
                                                    value="{{ $country->id }}">{{ $country->name }} {{ $country->phone }}</option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-@lang('site::country.icon')"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.country_id') }}</span>
                            </div>
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="phone_primary">@lang('site::repair.phone_primary')</label>
                                <div class="input-group">
                                    <input required
                                           type="tel"
                                           oninput="mask_phones()"
                                           id="phone_primary"
                                           name="repair[phone_primary]"
                                           class="phone-mask form-control{{ $errors->has('repair.phone_primary') ? ' is-invalid' : '' }}"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           value="{{ old('repair.phone_primary') }}"
                                           placeholder="@lang('site::repair.placeholder.phone_primary')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.phone_primary') }}</span>
                            </div>

                            <div class="form-group ">
                                <label class="control-label"
                                       for="phone_secondary">@lang('site::repair.phone_secondary')</label>
                                <div class="input-group">
                                    <input type="tel"
                                           oninput="mask_phones()"
                                           id="phone_secondary"
                                           name="repair[phone_secondary]"
                                           class="phone-mask form-control{{ $errors->has('repair.phone_secondary') ? ' is-invalid' : '' }}"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           value="{{ old('repair.phone_secondary') }}"
                                           placeholder="@lang('site::repair.placeholder.phone_secondary')">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.phone_secondary') }}</span>
                            </div>
                        </div>
                    </div>
                    {{--ОРГАНИЗАЦИИ --}}
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::repair.header.org')</h5>
                            <div class="form-group" id="form-group-trade_id">
                                <label class="control-label" for="trade_id">@lang('site::repair.trade_id')</label>
                                <div class="input-group">
                                    <select data-form-action="{{ route('trades.create') }}"
                                            data-btn-ok="@lang('site::messages.save')"
                                            data-btn-cancel="@lang('site::messages.cancel')"
                                            data-options="#trade_id_options"
                                            data-label="@lang('site::messages.add') @lang('site::trade.trade')"
                                            class="dynamic-modal-form form-control{{  $errors->has('repair.trade_id') ? ' is-invalid' : '' }}"
                                            name="repair[trade_id]"
                                            id="trade_id">
                                        @include('site::trade.options', ['trade_id' => old('repair.trade_id', isset($trade_id) ? $trade_id : null)])
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-@lang('site::trade.icon')"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.trade_id') }}</span>
                            </div>
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="date_trade">@lang('site::repair.date_trade')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_date_trade"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="repair[date_trade]"
                                           id="date_trade"
                                           maxlength="10"
                                           required
                                           placeholder="@lang('site::repair.placeholder.date_trade')"
                                           data-target="#datetimepicker_date_trade"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('repair.date_trade') ? ' is-invalid' : '' }}"
                                           value="{{ old('repair.date_trade') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_trade"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.date_launch') }}</span>
                            </div>
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="date_launch">@lang('site::repair.date_launch')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_date_launch"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="repair[date_launch]"
                                           id="date_launch"
                                           maxlength="10"
                                           required
                                           placeholder="@lang('site::repair.placeholder.date_launch')"
                                           data-target="#datetimepicker_date_launch"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('repair.date_launch') ? ' is-invalid' : '' }}"
                                           value="{{ old('repair.date_launch') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_launch"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.date_launch') }}</span>
                            </div>
                        </div>
                    </div>
                    {{--ВЫЕЗД НА ОБСЛУЖИВАНИЕ --}}
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::repair.header.call')</h5>
                            <div class="form-group required" id="form-group-engineer_id">
                                <label class="control-label"
                                       for="engineer_id">@lang('site::repair.engineer_id')</label>
                                <div class="input-group">
                                    <select required
                                            data-form-action="{{ route('engineers.create', ['certificate_type_id' => 1]) }}"
                                            data-btn-ok="@lang('site::messages.save')"
                                            data-btn-cancel="@lang('site::messages.cancel')"
                                            data-options="#engineer_id_options"
                                            data-label="@lang('site::messages.add') @lang('site::engineer.engineer')"
                                            class="dynamic-modal-form form-control{{  $errors->has('repair.engineer_id') ? ' is-invalid' : '' }}"
                                            name="repair[engineer_id]"
                                            id="engineer_id">
                                        @include('site::engineer.options', ['certificate_type_id' => 1, 'engineer_id' => old('repair.engineer_id', isset($engineer_id) ? $engineer_id : null)])
                                    </select>
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-@lang('site::engineer.icon')"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.engineer_id') }}</span>
                            @if(!$user->engineers()->whereHas('certificates',function ($query) {$query->where('certificates.type_id', 1);})->exists())
                            Внимание!
                                Инженеру необходимо зарегистрироваться как физическое лицо на сайте, пройти обучение и выполнить задания тестов. Также необходимо в личном кабинете инженера отправить заявку на привязку к Вашей организации. После успешного прохождения online-обучения инженер автоматически появится в Вашем списке инженеров.

                                С 1 января 2021 года отчеты о гарантийном ремонте будут приниматься только при наличии авторизованных сервисных инженеров (зарегистрированных и прошедших online-тестирование) 
                            @endif
                            </div>
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="date_call">@lang('site::repair.date_call')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_date_call"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="repair[date_call]"
                                           id="date_call"
                                           maxlength="10"
                                           required
                                           placeholder="@lang('site::repair.placeholder.date_call')"
                                           data-target="#datetimepicker_date_call"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('repair.date_call') ? ' is-invalid' : '' }}"
                                           value="{{ old('repair.date_call') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_call"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.date_call') }}</span>
                            </div>
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="reason_call">@lang('site::repair.reason_call')</label>
                                <textarea required
                                          name="repair[reason_call]"
                                          id="reason_call"
                                          class="form-control{{ $errors->has('repair.reason_call') ? ' is-invalid' : '' }}"
                                          placeholder="@lang('site::repair.placeholder.reason_call')">{{ old('repair.reason_call') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('repair.reason_call') }}</span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label"
                                       for="diagnostics">@lang('site::repair.diagnostics')</label>
                                <textarea required
                                          name="repair[diagnostics]"
                                          id="diagnostics"
                                          class="form-control{{ $errors->has('repair.diagnostics') ? ' is-invalid' : '' }}"
                                          placeholder="@lang('site::repair.placeholder.diagnostics')">{{ old('repair.diagnostics') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('repair.diagnostics') }}</span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label" for="works">@lang('site::repair.works')</label>
                                <textarea required
                                          name="repair[works]"
                                          id="works"
                                          class="form-control{{ $errors->has('repair.works') ? ' is-invalid' : '' }}"
                                          placeholder="@lang('site::repair.placeholder.works')">{{ old('repair.works') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('repair.works') }}</span>
                            </div>
                            <div class="form-group required">
                                <label class="control-label"
                                       for="date_repair">@lang('site::repair.date_repair')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_date_repair"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="repair[date_repair]"
                                           id="date_repair"
                                           maxlength="10"
                                           required
                                           placeholder="@lang('site::repair.placeholder.date_repair')"
                                           data-target="#datetimepicker_date_repair"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('repair.date_repair') ? ' is-invalid' : '' }}"
                                           value="{{ old('repair.date_repair') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_repair"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('repair.date_repair') }}</span>
                            </div>
                        </div>
                    </div>
                    {{-- ОПЛАТА --}}
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::repair.header.payment')</h5>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group required">
                                        <label class="control-label" for="product_id">
                                            @lang('site::repair.product_id')
                                        </label>
                                        <select required
                                                id="product_id"
                                                name="repair[product_id]"
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($products as $product)
                                                <option @if(old('repair.product_id') == $product->id) selected @endif value="{{$product->id}}"> {{$product->name}} / {{$product->sku}} </option>
                                            @endforeach
                                        </select>
                                        <small id="product_idHelp" class="d-block form-text text-success">
                                            @lang('site::repair.placeholder.product_id')
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('repair.product_id') }}</span>
                                    </div>
                                    <div class="form-group ">
                                        <label class="control-label"
                                               for="serial_id">@lang('site::repair.serial_id')</label>
                                        <div class="input-group">
                                            <input type="text"
                                                   name="repair[serial_id]"
                                                   id="serial_id"
                                                   class="form-control{{ $errors->has('repair.serial_id') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::repair.placeholder.serial_id')"
                                                   maxlength="20"
                                                   value="{{ old('repair.serial_id') }}">
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-@lang('site::serial.icon')"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('repair.serial_id') }}</span>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="difficulty_id">@lang('site::repair.difficulty_id')</label>
                                        <div class="input-group">
                                            <select class="form-control{{  $errors->has('repair.difficulty_id') ? ' is-invalid' : '' }}"
                                                    required
                                                    name="repair[difficulty_id]"
                                                    id="difficulty_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($difficulties as $difficulty)
                                                    <option data-cost="{{$difficulty->cost}}"
                                                            @if(old('repair.difficulty_id') == $difficulty->id) selected
                                                            @endif
                                                            value="{{ $difficulty->id }}">{{ $difficulty->name }}@if($difficulty->cost > 0)
                                                            - {{ Site::formatBack($difficulty->cost) }}@endif</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-@lang('site::difficulty.icon')"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('repair.difficulty_id') }}</span>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="distance_id">@lang('site::repair.distance_id')</label>
                                        <div class="input-group">
                                            <select class="form-control{{  $errors->has('repair.distance_id') ? ' is-invalid' : '' }}"
                                                    required
                                                    name="repair[distance_id]"
                                                    id="distance_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($distances as $distance)
                                                    <option data-cost="{{$distance->cost}}"
                                                            @if(old('repair.distance_id') == $distance->id) selected
                                                            @endif
                                                            value="{{ $distance->id }}">{{ $distance->name }}@if($distance->cost > 0)
                                                            - {{ Site::formatBack($distance->cost) }}@endif</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-@lang('site::distance.icon')"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('repair.distance_id') }}</span>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="contragent_id">@lang('site::repair.contragent_id')</label>
                                        <div class="input-group">
                                            <select required
                                                    class="form-control{{  $errors->has('repair.contragent_id') ? ' is-invalid' : '' }}"
                                                    name="repair[contragent_id]"
                                                    id="contragent_id">
                                                @if($contragents->count() == 0 || $contragents->count() > 1)
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                @endif
                                                @foreach($contragents as $contragent)
                                                    <option
                                                            @if(old('repair.contragent_id') == $contragent->id) selected
                                                            @endif
                                                            value="{{ $contragent->id }}">
                                                        {{ $contragent->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-@lang('site::contragent.icon')"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('repair.contragent_id') }}</span>
                                    </div>

                                </div>
                                <div class="col-sm-6">
                                    <fieldset id="product-search-fieldset">
                                    </fieldset>
                                    <fieldset id="parts-search-fieldset"
                                              style="display: @if( !old('allow_parts') || old('allow_parts') == 1) block @else none @endif;">
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="parts_search">@lang('site::messages.find') @lang('site::part.part')</label>
                                            <select style="width:100%" class="form-control" id="parts_search">
                                                <option></option>
                                            </select>
                                            <span class="invalid-feedback">Такая деталь уже есть в списке</span>
                                            <small id="partsHelp"
                                                   class="d-block form-text text-success">Введите артикул или
                                                наименование заменённой детали и выберите её из списка
                                            </small>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label"
                                                   for="">@lang('site::part.parts')</label>
                                            <div class="list-group" id="parts"
                                                 data-currency-symbol="{{ auth()->user()->currency->symbol_right }}">
                                                @foreach($parts as $part)
                                                    @include('site::part.create', ['product' => $part['product'], 'count' => $part['count'], 'repair_price_ratio' =>$repair_price_ratio])
                                                @endforeach
                                            </div>
                                            <hr/>
                                            <div class="text-right text-xlarge">
                                                @lang('site::part.total'):
                                                @if(!$parts->isEmpty())
                                                    <span id="total-cost">
                                                        {{Site::formatBack($parts->sum('cost') + old('cost_difficulty', 0) + old('cost_distance', 0))}}
                                                        </span>
                                                @else
                                                    {{Site::currency()->symbol_left}}
                                                    <span id="total-cost">0</span>
                                                    {{Site::currency()->symbol_right}}
                                                @endif

                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card mt-2 mb-2">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::file.files')</h5>
                        <h6 class="card-subtitle mb-2 text-muted">@lang('site::file.maxsize5mb')</h6>
                        @include('site::file.create.type')
                    </div>
                </div>
                <div class="card my-2">
                    <div class="card-body">
                        <div class="form-row required">
                            <div class="col mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input required
                                           form="form"
                                           type="checkbox"
                                           name="accept"
                                           value="1"
                                           class="custom-control-input{{ $errors->has('accept') ? ' is-invalid' : '' }}"
                                           id="accept">
                                    <label class="custom-control-label" for="accept">
                                        <span style="color:red;margin-right: 2px;">*</span>
                                        @lang('site::repair.accept')
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col text-right">
                                <button form="form" type="submit"
                                        class="btn btn-ms mb-1">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('repairs.index') }}" class="btn btn-secondary mb-1">
                                    <i class="fa fa-close"></i>
                                    <span>@lang('site::messages.cancel')</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {

            let product = $('#product_id'),
                parts_search = $('#parts_search'),
                parts = $('#parts'),
                selected = [];
            let number_format = function (number, decimals, dec_point, thousands_sep) {

                let i, j, kw, kd, km;

                // input sanitation & defaults
                if (isNaN(decimals = Math.abs(decimals))) {
                    decimals = 2;
                }
                if (dec_point === undefined) {
                    dec_point = ".";
                }
                if (thousands_sep === undefined) {
                    thousands_sep = " ";
                }

                i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

                if ((j = i.length) > 3) {
                    j = j % 3;
                } else {
                    j = 0;
                }

                km = (j ? i.substr(0, j) + thousands_sep : "");
                kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
                //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
                kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


                return km + kw + kd;
            };
            $(document)
                .on('click', '.part-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    if (index > -1) {
                        selected.splice(index, 1);
                        $('.product-' + $(this).data('id')).remove();
                    }
                    calc_parts();
                }))
                .on('keyup mouseup', '.parts_count', (function () {
                    calc_parts();
                }));
            let calc_parts = function () {
                let cost = 0;
                parts.children().each(function (i) {
                    let el = $(this).find('.parts_count');
                    //cost += (parseInt(el.data('cost')) * el.val());
                    cost += (el.data('cost') * el.val());
                });

                $('#total-cost').html(number_format(cost));
            };
            calc_parts();

            product.select2({
                theme: "bootstrap4",
                placeholder: '@lang('site::messages.select_from_list')',
                selectOnClose: true,
                minimumInputLength: 3,
            });

            parts_search.select2({
                theme: "bootstrap4",
                ajax: {
                    url: '/api/parts',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_part]': params.term,
                            'filter[search_product]': product.val(),
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (product) {
                    if (product.loading) return "...";
                    let markup = "<img style='width:70px;' src=" + product.image + " /> &nbsp; " + product.name + ' (' + product.sku + ')';
                    return markup;
                },
                templateSelection: function (product) {
                    console.log(product);
                    if (product.id !== "") {
                        return product.name + ' (' + product.sku + ')';
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            parts_search.on('select2:select', function (e) {
                let product_id = $(this).find('option:selected').val();
                if (!selected.includes(product_id)) {
                    parts_search.removeClass('is-invalid');
                    selected.push(product_id);
                    axios
                        .get("/api/parts/create/" + product_id)
                        .then((response) => {

                            parts.append(response.data);
                            $('[name="count[' + product_id + ']"]').focus();
                            calc_parts();
                            parts_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    parts_search.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush