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
                <a href="{{ route('admin.tenders.index') }}">@lang('site::tender.tenders')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.create') @lang('site::tender.tender')</h1>
        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <button form="form"
                    type="submit"
                    class="btn btn-ms">
                <i class="fa fa-check"></i>
                <span>@lang('site::messages.save')</span>
            </button>
            <a href="{{ route('admin.tenders.index') }}" class="btn btn-secondary">
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
        <div class="row justify-content-center mb-5">
            <div class="col">
                <form id="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.tenders.store') }}">
                    @csrf
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Адрес объекта строительства</h5>
                            <div class="row">        
                                <div class="col-12">
                                    <label class="control-label"
                                               for="search_address">Поиск адреса. Наберите адрес в свободном формате и выберите подходящий из предложенных</label>
                                        <input class="form-control" type="text" name="search_address" id="search_address" value="" autocomplete="off">
                                
                                    <div class="row ml-3 mt-3" id="search_address_wrapper"></div>
                            
                                </div>
                        
                            </div> 
                    
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col required">

                                            <label class="control-label" for="region_id">Регион</label>
                                            <select  style="background-color: #e9ecef;" class="form-control{{  $errors->has('tender.region_id') ? ' is-invalid' : '' }}"
                                                    name="tender[region_id]"
                                                    required 
                                                    id="region_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($regions as $region)
                                                    <option
                                                            @if(old('tender.region_id') == $region->id)
                                                            selected
                                                            @endif
                                                            value="{{ $region->id }}">{{ $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">{{ $errors->first('tender.region_id') }}</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label" for="city">Город</label>
                                            <input type="text"
                                                   name=""
                                                   id="city"
                                                   required  disabled
                                                   class="form-control{{ $errors->has('tender.city') ? ' is-invalid' : '' }}"
                                                   placeholder="Заполняется из результатов поиска в строке выше."
                                                   value="{{ old('tender.city') }}">
                                            <span class="invalid-feedback">{{ $errors->first('tender.city') }}</span>
                                            <input type="hidden"
                                                   name="tender[city]" 
                                                   id="city_hidden"
                                                   value="{{ old('tender.city') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label" for="address">Полный адрес объекта строительства</label>
                                    <div class="input-group">
                                    <input type="text"
                                           name="tender[address]" required
                                           id="address"   style="background-color: #e9ecef;"
                                           class="form-control{{ $errors->has('tender.address') ? ' is-invalid' : '' }}"
                                           placeholder="Заполняется автоматически из поиска адреса. При необходимости дополняется"
                                           value="{{ old('tender.address') }}">
                                    <span class="invalid-feedback">{{ $errors->first('tender.address') }}</span>
                                    <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <i class="fa fa-map-marker"></i>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label" for="address_addon">Дополнение к адресу</label>
                                    <input type="text"
                                           name="tender[address_addon]"
                                           id="address_addon" 
                                           class="form-control{{ $errors->has('tender.address_addon') ? ' is-invalid' : '' }}"
                                           placeholder="Например, строящийся корпус №7"
                                           value="{{ old('tender.address_addon') }}">
                                    <span class="invalid-feedback">{{ $errors->first('tender.address_addon') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label" for="address_name">Название объекта</label>
                                            <input type="text"
                                                   name="tender[address_name]"
                                                   id="address_name" 
                                                   class="form-control{{ $errors->has('tender.address') ? ' is-invalid' : '' }}"
                                                   placeholder="Например, ЖК Алые Паруса"
                                                   value="{{ old('tender.address_name') }}">
                                            <span class="invalid-feedback">{{ $errors->first('tender.address_name') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="form-row required">
                                            <div class="col">

                                                <label class="control-label" for="tender_category_id">Категория объекта</label>
                                                <select class="form-control{{  $errors->has('tender.tender_category_id') ? ' is-invalid' : '' }}"
                                                        name="tender[tender_category_id]"
                                                        required 
                                                        id="tender_category_id">
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                    @foreach($tenderCategories as $category)
                                                        <option
                                                                @if(old('tender.tender_category_id') == $category->id)
                                                                selected
                                                                @endif
                                                                value="{{ $category->id }}">{{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback">{{ $errors->first('tender.tender_category_id') }}</span>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Субъекты строительства</h5>
                            <a class="text-small" href="{{route('admin.customers.index')}}" target="_blank">Справочник клиентов 2-го уровня</a>
                            <div id="investor">
                            
                                @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'investor','row_num'=>'0', 'title'=>'Инвестор'])
                            
                            </div>
                            <div id="customer">
                            @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'customer','row_num'=>'0', 'title'=>'Заказчик'])
                            
                            </div>
                            <div id="gen_contractor">
                            
                            @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'gen_contractor','row_num'=>'0', 'title'=>'Генподрядчик'])
                           
                            </div>
                            <div id="gen_designer">
                           
                            @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'gen_designer','row_num'=>'0', 'title'=>'Генпроектировщик'])
                            
                            </div>
                            <div id="designer">
                                @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'designer','row_num'=>'0', 'title'=>'Проектировщики'])
                            </div>
                            
                            <div id="contractor">
                                @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'contractor','row_num'=>'0', 'title'=>'Подрядчики'])
                            </div>
                            
                            <div id="picker">
                                @include('site::admin.tender.customer_second', ['errors' => $errors,'field_name' => 'picker','row_num'=>'0', 'title'=>'Комплектовщик'])
                            </div>
                            
                            
                            <div class="row">
                                <div class="col">
                                <label class="control-label" for="distributor_id">Дистрибьютор</label>
                                <span id="usersHelp" class="d-block form-text text-success">
                                    Введите название или ИНН и выберите вариант из выпадающего списка.
                                </span>
                                            <fieldset id="users-search-fieldset"
                                                      style="display: block; padding-left: 5px;">
                                                <div class="form-row">
                                                    <select class="form-control" id="users_search"  name="tender[distributor_id]">
                                                    @if(old('tender.distributor_id')) <option selected value="{{old('tender.distributor_id')}}"> {{old('tender.distributor_id')}}</option>@endif
                                                        <option></option>
                                                    </select>
                                                    
                                                </div>
                                            </fieldset>
                                </div>
                            </div>
                            
                            <input type="hidden" name="tender[country_id]" value="643">
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="distr_contact_phone">Телефон контактного лица дистибьютора</label>
                                <div class="input-group">
                                    <input required
                                           type="tel"
                                           oninput="mask_phones()"
                                           id="distr_contact_phone"
                                           name="tender[distr_contact_phone]"
                                           class="phone-mask form-control{{ $errors->has('tender.distr_contact_phone') ? ' is-invalid' : '' }}"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           value="{{ old('tender.distr_contact_phone') }}"
                                           >
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('tender.distr_contact_phone') }}</span>
                            </div>
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label" for="distr_contact">Контакты дистрибьютора</label>
                                    <input type="text"
                                           name="tender[distr_contact]"
                                           id="distr_contact" 
                                           class="form-control{{ $errors->has('tender.distr_contact') ? ' is-invalid' : '' }}"
                                           placeholder="ФИО ответственного, другие контактные данные"
                                           value="{{ old('tender.distr_contact') }}">
                                    <span class="invalid-feedback">{{ $errors->first('tender.distr_contact') }}</span>
                                </div>
                            </div>

                           
                        </div>
                    </div>
                    
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <h5 class="card-title">Объект тендера, информация о закупке.</h5>
                            <div class="form-row">
                                <div class="col-md-3">
                                        <div class="form-row required">
                                            <div class="col">

                                                <label class="control-label" for="planned_purchase_year">Планируемый год закупки</label>
                                                <select class="form-control{{  $errors->has('tender.planned_purchase_year') ? ' is-invalid' : '' }}"
                                                        name="tender[planned_purchase_year]"
                                                        required 
                                                        id="planned_purchase_year">
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                    @foreach($planned_purchase_years as $planned_purchase_year)
                                                        <option
                                                                @if(old('tender.planned_purchase_year') == $planned_purchase_year)
                                                                selected
                                                                @endif
                                                                value="{{ $planned_purchase_year}}">{{ $planned_purchase_year }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback">{{ $errors->first('tender.planned_purchase_year') }}</span>
                                            </div>
                                        </div>

                                </div>
                                <div class="col-md-3">
                                        <div class="form-row required">
                                            <div class="col">

                                                <label class="control-label" for="planned_purchase_month">Планируемы месяц закупки</label>
                                                <select class="form-control{{  $errors->has('tender.planned_purchase_month') ? ' is-invalid' : '' }}"
                                                        name="tender[planned_purchase_month]"
                                                        required 
                                                        id="planned_purchase_month">
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                    @foreach(trans('site::messages.months_cl') as $mounth_num=>$mounth_name)
                                                        <option
                                                                @if(old('tender.planned_purchase_month') == $mounth_num)
                                                                selected
                                                                @endif
                                                                value="{{ $mounth_num}}">{{ $mounth_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <span class="invalid-feedback">{{ $errors->first('tender.planned_purchase_month') }}</span>
                                            </div>
                                        </div>

                                </div>
                                <div class="col-md-3">     
                                    <div class="form-row required">
                                        <div class="col">               
                                        <label class="control-label"
                                                   for="date_price">Цена действительна до: </label>
                                            <div class="input-group date datetimepicker" id="datetimepicker_date_to_max" 
                                                 data-target-input="nearest"  data-max-date-months="3">
                                                <input type="text"
                                                       name="tender[date_price]" 
                                                       id="date_price" maxlength="10" required 
                                                       data-target="#datetimepicker_date_to_max" data-toggle="datetimepicker"
                                                       class="datetimepicker-input form-control{{ $errors->has('tender.date_price') ? ' is-invalid' : '' }}"
                                                       value="{{ old('tender.date_price') }}">
                                                <div class="input-group-append"
                                                     data-target="#datetimepicker_date_to_max"
                                                     data-toggle="datetimepicker">
                                                    <div class="input-group-text">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('tender.date_price') }}</span>
                                        </div>   
                                    </div>   
                                </div> 
                            </div>
                                
                                
                      
                    <div class="form-row">
                        <div class="col">             
                        <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('tender.cb_rate')) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('tender.cb_rate') ? ' is-invalid' : '' }}"
                                       id="cb_rate"
                                       name="tender[cb_rate]">
                                <label class="custom-control-label"
                                       for="cb_rate"><b>@lang('site::tender.cb_rate')</b></label>
                                <span class="invalid-feedback">{{ $errors->first('tender.cb_rate') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates">Курс для дистрибьютора</label>
                                       <input type="number" name="tender[rates]" step="0.01"
                                            title="Курс"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div>  
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates_min">Коридор курса ЦБ для дистр-а от:</label>
                                       <input type="number" name="tender[rates_min]" step="0.01"
                                            title="Курс"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates_max">до:</label>
                                       <input type="number" name="tender[rates_max]" step="0.01"
                                            title="Курс"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div>   
                    </div>   
                          
                    <div class="form-row">   
                   
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates_object">Курс для объекта</label>
                                       <input type="number" name="tender[rates_object]" step="0.01"
                                            title="Скидка"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates_object_min">Коридор курса для объекта от:</label>
                                       <input type="number" name="tender[rates_object_min]" step="0.01"
                                            title="Курс"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div> 
                        <div class="col-md-3">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="rates_object_max">до:</label>
                                       <input type="number" name="tender[rates_object_max]" step="0.01"
                                            title="Курс"
                                            class="form-control parts_count" value="{{$rates}}">
                                </div>
                            </div>
                        </div> 
                      
                     
                    </div>
                            <h5 class="card-title">Оборудование, количество, цена</h5>
                            <div class="row">
                                
                                
                                    <div class="col-sm-6">
                                    <div class="form-group required">
                                        <label class="control-label" for="product_id">
                                            Оборудование
                                        </label>
                                        <select 
                                                id="product_id"
                                               
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($products as $product)
                                                <option @if(old('tender.product_id') == $product->id) selected @endif value="{{$product->id}}"> {{$product->name}} / {{$product->sku}} </option>
                                            @endforeach
                                        </select>
                                        <small id="product_idHelp" class="d-block form-text text-success">
                                            Введите наименование и выберите из предложенного списка
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('tender.product_id') }}</span>
                                    </div>
                                    </div>
                                    <div class="col-sm-6">
                                    <div class="form-group">  
                                        <div class="list-group" id="parts">
                                        
                                        </div>
                                    </div>
                                 </div>
                                
                            </div>
                            
                            <div class="form-group required ">
                                <label class="control-label"
                                       for="rivals">Конкурентные предложения на тендере</label>
                                <textarea required
                                          name="tender[rivals]"
                                          id="rivals"
                                          class="form-control{{ $errors->has('tender.rivals') ? ' is-invalid' : '' }}"
                                          placeholder="Например, Baxi Eco Four - 303 евро/шт"
                                          >{{ old('tender.rivals') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('tender.rivals') }}</span>
                            </div>
                            
                            
                            <div class="form-group ">
                                <label class="control-label"
                                       for="comment">Примечания</label>
                                <textarea 
                                          name="tender[comment]"
                                          id="comment"
                                          class="form-control{{ $errors->has('tender.comment') ? ' is-invalid' : '' }}"
                                          >{{ old('tender.comment') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('tender.comment') }}</span>
                            </div>
                            <div class="form-row required">
                                <div class="col">

                                    <label class="control-label" for="source_id">@lang('site::tender.tender_source.source')</label>
                                    <select class="form-control{{  $errors->has('tender.source_id') ? ' is-invalid' : '' }}"
                                            name="tender[source_id]"
                                            required 
                                            id="source_id">
                                        <option value="0" @if(old('tender.source_id') == 0) selected @endif >@lang('site::tender.tender_source.0')</option>
                                        <option value="1" @if(old('tender.source_id') == 1) selected @endif >@lang('site::tender.tender_source.1')</option>
                                        <option value="2" @if(old('tender.source_id') == 2) selected @endif >@lang('site::tender.tender_source.2')</option>
                                            
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('tender.source_id') }}</span>
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
                        <div class="form-group">
                            <div class="col text-right">
                                <button form="form" type="submit"
                                        class="btn btn-ms mb-1">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('admin.tenders.index') }}" class="btn btn-secondary mb-1">
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
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
    try {
        window.addEventListener('load', function () {
            
            suggest_count = 0;
            input_initial_value = '';
            suggest_selected = 0;
            $(document)
                .on('click', '.store-field', (function(I){
                    
                    var field_name = $(this)[0].dataset.fieldName;
                    var form_id = $(this)[0].dataset.formId;
                    
                    var customer = {customer: {"name" : $('#' + form_id).val(), 
                                                "id" : $('#' + form_id+'_customer_id').val(), 
                                                "locality" : $('#' + form_id+'_locality').val(), 
                                                "region_id" : $('#' + form_id+'_region_id').val(), 
                                                "phone" : $('#' + form_id+'_customer_phone').val(), 
                                                "email" : $('#' + form_id+'_customer_email').val(), 
                                                "role_name" : field_name
                                            },
                                    contact: {
                                                "id" : $('#' + form_id+'_contact_id').val(), 
                                                "name" : $('#' + form_id+'_contact_name').val(), 
                                                "position" : $('#' + form_id+'_contact_position').val(), 
                                                "phone" : $('#' + form_id+'_contact_phone').val(), 
                                                "email" : $('#' + form_id+'_contact_email').val(), 
                                                "lpr" : document.querySelector('#' + form_id+'_contact_lpr').checked
                                            }, 
                                    "form_id" :form_id};
                                         
                    axios
                        .post("/api/customer-store", customer)
                        
                        .then((response) => {
                            
                            $('#' + form_id+'_customer_id').val(response.data['customer']['id']);
                            if(response.data['contact']) {$('#' + form_id+'_contact_id').val(response.data['contact']['id']);}
                            
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                
                }))
                .on('click', '.add-field', (function(I){
                    
                    var field_name = $(this)[0].dataset.fieldName;
                    
                    let field = $('#'+field_name);
                    
                    axios
                        .get("/api/customer-create/" + field_name)
                        .then((response) => {

                            field.append(response.data);
                            
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                
                }))
                .on('keyup', '.customer-contact-name', (function(I){
                    
                    var form_id = $(this)[0].dataset.formId;
                    $('#' + form_id+'_contact_id').val('');
                
                }))
                .on('keyup', '.customers', (function(I){
                
                    var field_name = $(this)[0].dataset.fieldName;
                    
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            
                            $(this).attr('autocomplete','off');
                            $('#' + field_name+'_customer_id').val('');
                            $('#' + field_name+'_contact_id').val('');
                                                   
                            if($(this).val().length>2){
                                input_initial_value = $(this).val();
                                $.get("/api/customer-search", { "filter[search_customer]":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    suggest_count = list.data.length;
                                    $('#' + field_name+'_wrapper').hide();               
                                    if(suggest_count > 0){
                                    
                                        $('#' + field_name+'_wrapper').html("").show();
                                        for(var i in list.data){
                                            if(list.data[i] != ''){
                                            
                                                $('#' + field_name+'_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list.data[i].id+'">'+list.data[i].name+'</div>');
                                                
                                                $('#' + field_name+'_wrapper').find('#result_id-'+list.data[i].id).click(function() {
                                                  
                                                   $('#' + field_name+'_search_address').val('');
                                                   $('#' + field_name+'_locality').val('');
                                                   $('#' + field_name+'_region_id').val('');
                                                   $('#' + field_name+'_customer_phone').val('');
                                                   $('#' + field_name+'_customer_email').val('');
                                                   $('#' + field_name+'_contact_name').val('');
                                                   $('#' + field_name+'_contact_position').val('');
                                                   $('#' + field_name+'_contact_phone').val('');
                                                   $('#' + field_name+'_contact_email').val('');
                                                   document.querySelector('#' + field_name+'_contact_lpr').checked=false;
                                                  
                                                  
                                                   $('#' + field_name).val(list.data[$(this)[0].getAttribute('data-key')].name);
                                                   $('#' + field_name+'_customer_id').val(list.data[$(this)[0].getAttribute('data-key')].id);
                                                   $('#' + field_name+'_search_address').val(list.data[$(this)[0].getAttribute('data-key')].locality);
                                                   $('#' + field_name+'_locality').val(list.data[$(this)[0].getAttribute('data-key')].locality);
                                                   $('#' + field_name+'_region_id').val(list.data[$(this)[0].getAttribute('data-key')].region_id);
                                                   $('#' + field_name+'_customer_phone').val(list.data[$(this)[0].getAttribute('data-key')].phone);
                                                   $('#' + field_name+'_customer_email').val(list.data[$(this)[0].getAttribute('data-key')].email);
                                                   $('#' + field_name+'_contact_name').val(list.data[$(this)[0].getAttribute('data-key')].contact.name);
                                                   $('#' + field_name+'_contact_id').val(list.data[$(this)[0].getAttribute('data-key')].contact.id);
                                                   $('#' + field_name+'_contact_position').val(list.data[$(this)[0].getAttribute('data-key')].contact.position);
                                                   $('#' + field_name+'_contact_phone').val(list.data[$(this)[0].getAttribute('data-key')].contact.phone);
                                                   $('#' + field_name+'_contact_email').val(list.data[$(this)[0].getAttribute('data-key')].contact.email);
                                                   if(list.data[$(this)[0].getAttribute('data-key')].contact.lpr == 1) document.querySelector('#' + field_name+'_contact_lpr').checked=true;
                                                    $('#' + field_name+'_wrapper').fadeOut(2350).html('');
                                                });
                                            } else {
                                             $('#' + field_name+'_wrapper').html("").hide();
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                        break;
                    }
                    })
                )
            
            
                .on('keydown', '.customers', (function(I){
                var field_name = $(this)[0].dataset.fieldName;
                    switch(I.keyCode) {
                        case 27: // escape
                            $('#' + field_name+'_wrapper').hide();
                            return false;
                        break;
                        
                    }
                })
                
                );

             $('html').click(function(){
                $('#search_wrapper').hide();
            }); 
            
            // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
          /*   $('#customers').click(function(event){
                if(suggest_count)
                    $('#investor_wrapper').show();
                event.stopPropagation();
            });
                 */
            
            
            
            let product = $('#product_id'),
                parts_search = $('#parts_search'),
                parts = $('#parts'),
                selected = [],
                date_to = $('#datetimepicker_date_to_max');
                
                var max_date = new Date();
                max_date.setMonth(max_date.getMonth() + Number(date_to[0].dataset.maxDateMonths));
                
                $('#datetimepicker_date_to_max').datetimepicker('maxDate', max_date);
                $('#datetimepicker_date_to_max_object').datetimepicker('maxDate', max_date);
                
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
                    var retailprice = $('#retailprice-' + $(this)[0].dataset.product)[0].value;
                    var discount = $(this)[0].value;
                    $('#' + $(this)[0].dataset.priceType).html('(€ ' +(retailprice*(100-discount)/100).toFixed(0) +')');
                   
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
            
            let calc_price = function (product_id) {
                var discount = $('#discount-' + product_id)[0].value;
                            var retailprice = $('#retailprice-' + product_id)[0].value;
                            
                            //console.log((retailprice*(100-discount)/100).toFixed(2));
                            
            };
            

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
                    //console.log(product);
                    if (product.id !== "") {
                        return product.name + ' (' + product.sku + ')';
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            product.on('select2:select', function (e) {
                let product_id = $(this).find('option:selected').val();
                if (!selected.includes(product_id)) {
                    product.removeClass('is-invalid');
                    selected.push(product_id);
                    axios
                        .get("/api/tender-items/create/" + product_id)
                        .then((response) => {

                            parts.append(response.data);
                            $('[name="count[' + product_id + ']"]').focus();
                            calc_parts();
                            
                            calc_price(product_id);
                            product.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    product.addClass('is-invalid');
                }
            });
///-------------------------------------------------------------------------
                
            suggest_count = 0;
            input_initial_value = '';
            suggest_selected = 0;
            $(document)
                .on('keyup', '.search_address', (function(I){
                    
                    var field_name = $(this)[0].dataset.fieldName;
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            $(this).attr('autocomplete','off');
                        
                            if($(this).val().length>3){

                                input_initial_value = $(this).val();
                                $.get("/api/dadata/address", { "str":$(this).val() },function(data){
                                    var list = JSON.parse(data);
                                    
                                    suggest_count = list.length;
                                    if(suggest_count > 0){
                                        $('#'+field_name+'_search_address_wrapper').html("").show();
                                        for(var i in list){
                                            if(list[i] != ''){
                                                $('#'+field_name+'_search_address_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                                $('#'+field_name+'_search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                    
                                                    if(list[$(this)[0].getAttribute('data-key')].alldata.city){
                                                        document.getElementById(field_name+'_locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                        $('#'+field_name+'_search_address').val(list[$(this)[0].getAttribute('data-key')].alldata.city+' ('+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type+')');
                                                    } else {
                                                    document.getElementById(field_name+'_locality').value = list[$(this)[0].getAttribute('data-key')].alldata.area_with_type;
                                                    $('#'+field_name+'_search_address').val(list[$(this)[0].getAttribute('data-key')].alldata.area_with_type+' ('+list[$(this)[0].getAttribute('data-key')].alldata.region_with_type+')');
                                                    }
                                                    document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                                    document.getElementById(field_name+'_region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                                    //$('#'+field_name+'_search_address').val($(this).text());
                                                    
                                                    
                                                    // прячем слой подсказки
                                                    $('#search_address_wrapper').fadeOut(2350).html('');
                                                });
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                        break;
                    }
                })
                )

                
                .on('keydown', '.search_address', (function(I){
                    switch(I.keyCode) {
                        case 27: // escape
                            $('#search_wrapper').hide();
                            return false;
                        break;
                        
                    }
                })
                );

                 $('html').on('click', '.search_wrapper', (function(){
                    $(this).hide();
                })); 
                
                // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
                $('#search_address').click(function(event){
                    if(suggest_count)
                        $('#search_address_wrapper').show();
                    event.stopPropagation();
                });


            
///-------------------------------------------------------------------------                    
                $("#search_address").keyup(function(I){
                    switch(I.keyCode) {
                        // игнорируем нажатия 
                        case 13:  // enter
                        case 27:  // escape
                        case 38:  // стрелка вверх
                        case 40:  // стрелка вниз
                        break;

                        default:
                            if($(this).val().length>3){

                                input_initial_value = $(this).val();
                                $.get("/api/dadata/address", { "str":$(this).val() },function(data){
                                    var list = JSON.parse(data);
                                    
                                    suggest_count = list.length;
                                    if(suggest_count > 0){
                                        $("#search_address_wrapper").html("").show();
                                        for(var i in list){
                                            if(list[i] != ''){
                                                $('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                                $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                    
                                                    if(list[$(this)[0].getAttribute('data-key')].alldata.city){
                                                        document.getElementById('city').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                        document.getElementById('city_hidden').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                    } else {
                                                    document.getElementById('city').value = list[$(this)[0].getAttribute('data-key')].alldata.area_with_type;
                                                    document.getElementById('city_hidden').value = list[$(this)[0].getAttribute('data-key')].alldata.area_with_type;
                                                    }
                                                    document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                                    document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                                    $('#search_address').val($(this).text());
                                                    
                                                    // прячем слой подсказки
                                                    $('#search_address_wrapper').fadeOut(2350).html('');
                                                });
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                        break;
                    }
                });

                
                $("#search_address").keydown(function(I){
                    switch(I.keyCode) {
                        case 27: // escape
                            $('#search_address_wrapper').hide();
                            return false;
                        break;
                        
                    }
                });

                 $('html').click(function(){
                    $('#search_address_wrapper').hide();
                    $('search_wrapper').hide();
                }); 
                
                // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
                $('#search_address').click(function(event){
                    if(suggest_count)
                        $('#search_address_wrapper').show();
                    event.stopPropagation();
                });
        
                
                
                
                
                let users_search = $('#users_search'),
                        users = $('#users')
                        ;
                    
                    users_search.select2({
                        theme: "bootstrap4",
                        ajax: {
                            url: '/api/user-search',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    'filter[search_user]': params.term,
                                    'filter[has_distr_roles]': '1',
                                    
                                };
                            },
                            processResults: function (data, params) {
                                return {
                                    results: data.data,
                                };
                            }
                        },
                        
                        minimumInputLength: 3,
                        templateResult: function (user) {
                            if (user.loading) return "...";
                            let markup = user.name;
                            return markup;
                        },
                        templateSelection: function (user) {
                            if (user.id !== "") {
                                return user.name;
                            }
                        },
                        escapeMarkup: function (markup) {
                            return markup;
                        }
                    });
                    users_search.on('select2:select', function (e) {
                        let id = $(this).find('option:selected').val();
                        if (!selected.includes(id)) {
                            users_search.removeClass('is-invalid');
                            selected.push(id);
                        } else {
                            users_search.addClass('is-invalid');
                        }
                    });

        
        
        });
        
                function key_activate(n){
            $('#search_address_wrapper div').eq(suggest_selected-1).removeClass('active');

            if(n == 1 && suggest_selected < suggest_count){
                suggest_selected++;
            }else if(n == -1 && suggest_selected > 0){
                suggest_selected--;
            }

            if( suggest_selected > 0){
                $('#search_address_wrapper div').eq(suggest_selected-1).addClass('active');
                $("#search_address").val( $('#search_address_wrapper div').eq(suggest_selected-1).text() );
            } else {
                $("#search_address").val( input_initial_value );
            }
        }
        
        
  
            
        
    } catch (e) {
        console.log(e);
    }

</script>
@endpush