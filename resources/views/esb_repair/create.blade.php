@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-repairs.index') }}">@lang('site::user.esb_repair.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        
            <div class="ml-md-auto">
            <li class="breadcrumb-item">
                <a href="{{ route('esb-user-products.index') }}">@lang('site::user.esb_user_product.index')</a>
            </li>
            </div>
        
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_repair.new')</h1>

        @alert()@endalert()
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
                    <div class="card mb-2">
                        <div class="card-body">
                        
                            <form id="form"
                                  method="POST"
                                  action="{{ route('esb-repairs.store') }}">
                                @csrf
                       
                            <div class="row">
                                <div class="col-sm-3 mr-0">
                                    <div class="form-row mb-2 required">
                                        <label class="control-label w-100"
                                               for="search_serial">@lang('site::user.esb_repair.serial')</label>
                                        
                                            <div class="col-10 mr-0">
                                                <input type="text"
                                                       name="esb_repair[product_serial]" required
                                                       id="search_serial"
                                                       data-route="serial"
                                                       maxlength="24"
                                                       class="search_product form-control{{ $errors->has('esb_repair.product_serial') ? ' is-invalid' : '' }}"
                                                       value="{{ old('esb_repair.product_serial',$esbUserProduct ? $esbUserProduct->serial : null) }}"
                                                       >
                                             
                                            
                                            <span class="invalid-feedback invalid-serial">{{ $errors->first('esb_repair.product_serial') }}</span>
                                            </div>
                                            
                                            <div class="col-1">
                                                 <a class="btn btn-secondary search_product_btn" href="javascript:void(0);" data-search-field="search_serial">
                                                 <i class="fa fa-search"></i></a>
                                            </div>
                                        </div>
                                    
                                    <div class="form-row mb-0 mt-0">
                                        <div class="col">       
                                           <div class="ml-1 mt-1 search_wrapper" id="serial_wrapper"></div>
                                                    
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3 ml-0">
                                
                                    <div class="form-row mb-2">
                                    <label class="control-label w-100"
                                               for="search_phone">@lang('site::user.esb_repair.phone_search')</label>
                                        
                                        <div class="col-10 mr-0">
                                            <input type="text"
                                                   name="esb_repair[phone]"
                                                   id="search_phone"
                                                   data-route="phone"
                                                   maxlength="24"
                                                   class="search_product form-control{{ $errors->has('esb_repair.phone') ? ' is-invalid' : '' }}"
                                                   value="{{ old('esb_repair.phone',$esbUserProduct ? $esbUserProduct->esbUser->phone_formated : null) }}"
                                                  >
                                            
                                        
                                        <span class="invalid-feedback">{{ $errors->first('esb_repair.phone') }}</span>
                                        </div>
                                        <div class="col-1">
                                            <a class="btn btn-secondary search_product_btn" href="javascript:void(0);" data-search-field="search_phone"><i class="fa fa-search"></i></a>
                                        </div>
                                    </div>
                                    <div class="form-row mb-0 mt-0">
                                        <div class="col-1">       
                                           <div class="ml-1 mt-1 search_wrapper" id="phone_wrapper"></div>
                                                    
                                        </div>
                                    </div>
                                
                                </div>
                                </div>
                                
                              <div class="row">
                              <div class="col-sm-6">

                                    <label class="control-label" for="client_id">@lang('site::user.esb_repair.client')</label>
                                    <select class="form-control{{  $errors->has('esb_repair.client_id') ? ' is-invalid' : '' }}"
                                            name="esb_repair[client_id]"
                                            id="client_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($clients as $client)
                                            <option
                                                    @if(old('esb_repair.client_id',$esbUserProduct ? $esbUserProduct->esbUser->id : null) == $client->id)
                                                    selected
                                                    @endif
                                                    value="{{ $client->id }}">{{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esb_repair.client_id') }}</span>
                                    
                                </div>
                                
                                                                
                                <div class="col-sm-6">

                                    <label class="control-label" for="esb_user_product_id">@lang('site::user.esb_repair.user_product')</label>
                                    <select class="esb_user_product_id form-control{{  $errors->has('esb_repair.esb_user_product_id') ? ' is-invalid' : '' }}"
                                            name="esb_repair[esb_user_product_id]"
                                            id="esb_user_product_id">
                                        <option value="" disabled>@lang('site::user.esb_repair.client_select_first')</option>
                                        @foreach($userProducts as $userProduct)
                                            <option
                                                    @if(old('esb_repair.esb_user_product_id',$esbUserProduct ? $esbUserProduct->id : null) == $userProduct->id)
                                                    selected
                                                    @endif
                                                    value="{{ $userProduct->id }}">{{ $userProduct->product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esb_repair.esb_user_product_id') }}</span>
                                    <input type="hidden" name="esb_repair[product_id]" value="{{old('esb_repair.product_id',$esbUserProduct ? $esbUserProduct->product->id : null)}}" id="repair_product_id">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <label class="control-label"
                                               for="product_name">@lang('site::user.esb_repair.product_name')</label>
                                    
                                    <input type="text"
                                           name="esb_repair[product_name]"
                                           id="product_name"
                                           maxlength="50"
                                           class="mb-0 form-control{{ $errors->has('esb_repair.product_name') ? ' is-invalid' : '' }}"
                                           value="{{ old('esb_repair.product_name',$esbUserProduct ? $esbUserProduct->product->name : null) }}"
                                           placeholder="@lang('site::user.esb_repair.product_name_placeholder')">
                                    
                                    <span class="invalid-feedback">{{ $errors->first('esb_repair.product_name') }}</span>
                                     <span class="text-success" id="product_info_help">Заполняется, если нет зарегистрированного оборудования</span>
                               
                                </div>
                                <div class="col-sm-6">
                                    
                                </div>
                            
                            </div>
                           
                            <div class="row">
                                <div class="col-sm-6">

                                    <label class="control-label" for="engineer_id">@lang('site::user.esb_repair.engineer_id')</label>
                                    <select class="form-control{{  $errors->has('esb_repair.engineer_id') ? ' is-invalid' : '' }}"
                                            name="esb_repair[engineer_id]"
                                            id="engineer_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($engineers as $engineer)
                                            <option
                                                    @if(old('esb_repair.engineer_id') == $engineer->id)
                                                    selected
                                                    @endif
                                                    value="{{ $engineer->id }}">{{ $engineer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('esb_repair.engineer_id') }}</span>
                                    @if(empty($engineers)) <span class="text-sucess">Ваши инженеры должны быть зарегистрированы на сайте под своим телефоном или email</span>@endif
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label"
                                               for="number">@lang('site::user.esb_repair.number')</label>
                                    
                                    <input type="text"
                                           name="esb_repair[number]"
                                           id="number"
                                           maxlength="50"
                                           class="form-control{{ $errors->has('esb_repair.number') ? ' is-invalid' : '' }}"
                                           value="{{ old('esb_repair.number') }}"
                                           placeholder="@lang('site::user.esb_repair.number_placeholder')">
                                    
                                    <span class="invalid-feedback">{{ $errors->first('esb_repair.number') }}</span>
                                        
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-row mb-2 required">
                                        <label class="control-label"
                                               for="date_repair">@lang('site::user.esb_repair.date_maintenance')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_repair"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="esb_repair[date_repair]" required
                                                   id="date_repair"
                                                   maxlength="10"
                                                   placeholder="@lang('site::messages.datetimepicker_placeholder')"
                                                   data-target="#datetimepicker_date_repair"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('esb_repair.date_repair') ? ' is-invalid' : '' }}"
                                                   value="{{ old('esb_repair.date_repair') }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_repair"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('esb_repair.date_repair') }}</span>
                                    </div>
                                </div>
                                
                            </div>    
                            <div class="row">
                                
                                <div class="col-sm-2">
                                    <label class="control-label"
                                               for="cost">@lang('site::user.esb_repair.cost')</label>
                                    
                                    <input type="number"
                                           name="esb_repair[cost]"
                                           id="cost"
                                           
                                           class="form-control{{ $errors->has('cost') ? ' is-invalid' : '' }}"
                                           value="{{ old('cost') }}"
                                          >
                                    
                                    <span class="invalid-feedback">{{ $errors->first('cost') }}</span>
                                        
                                
                                </div>
                                <div class="col-sm-2">
                                    <label class="control-label"
                                               for="parts_cost">@lang('site::user.esb_repair.parts_cost')</label>
                                    
                                    <input type="number"
                                           name="esb_repair[parts_cost]"
                                           id="parts_cost"
                                           
                                           class="mb-0 form-control{{ $errors->has('parts_cost') ? ' is-invalid' : '' }}"
                                           value="{{ old('parts_cost') }}"
                                          >
                                    <small class="text-success mt-0">(@lang('site::user.esb_repair.parts_cost_wide'))</small>
                                    <span class="invalid-feedback">{{ $errors->first('parts_cost') }}</span>
                                    
                                        
                                
                                </div>
                                <div class="col-sm-8">
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
                                
                                </div>
                            </div>
                           
                            <div class="form-row">
                                <div class="col-sm-4">
                                </div>
                                <div class="col-sm-8">
                                    <div class="form-group">
                                            <label class="control-label"
                                                   for="">@lang('site::part.parts'):</label>
                                            <div class="list-group" id="parts"
                                                 data-currency-symbol="{{ auth()->user()->currency->symbol_right }}">
                                                @foreach($parts as $part)
                                                    @include('site::part.create', ['product' => $part['product'], 'count' => $part['count'], 'repair_price_ratio' =>$repair_price_ratio])
                                                @endforeach
                                            </div>
                                            
                                    </div>
                                </div>
                            </div>
                            <div class="form-row required">
                                
                                    <div class="col mb-3">
                                        <label class="control-label" for="comments">@lang('site::user.esb_repair.comments')</label>
                                        <textarea
                                              name="esb_repair[comments]"
                                              id="comments" required
                                              class="form-control{{ $errors->has('esb_repair.comments') ? ' is-invalid' : '' }}"
                                              >{{ old('esb_repair.comments') }}</textarea>
                                        <span class="invalid-feedback">{{ $errors->first('esb_repair.comments') }}</span>
                                    </div>
                            </div>
                        </form>
                        
                            

                            <div class="form-row">
                                <div class="col text-right">
                                    <button form="form" type="submit"
                                            class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span id="save_btn">
                                        @lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('esb-repairs.index') }}" class="btn btn-secondary mb-1">
                                        <i class="fa fa-close"></i>
                                        <span>@lang('site::messages.cancel')</span>
                                    </a>
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
        
            let product = $('#repair_product_id'),
                    parts_search = $('#parts_search'),
                    parts = $('#parts'),
                    selected = [];
                
                $(document)
                    .on('click', '.part-delete', (function () {
                        let index = selected.indexOf($(this).data('id'));
                        if (index > -1) {
                            selected.splice(index, 1);
                            $('.product-' + $(this).data('id')).remove();
                        }
                        
                    }))
                

                parts_search.select2({
                    theme: "bootstrap4",
                    ajax: {
                         
                        url: '/api/products/search',
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                'filter[search_part]': params.term,
                                'filter[search_product]': product.val(),
                            };
                        },
                        processResults: function (data, params) {
                            console.log(product.val());
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
                            .get("/api/parts/create-retail/" + product_id)
                            .then((response) => {

                                parts.append(response.data);
                                $('[name="count[' + product_id + ']"]').focus();
                                parts_search.val(null)
                            })
                            .catch((error) => {
                                this.status = 'Error:' + error;
                            });
                    } else {
                        parts_search.addClass('is-invalid');
                    }
                });

        
        
            suggest_count = 0;
            $(document)
                
                .on('change', '.esb_user_product_id', (function(I){
                    $('#product_name').val($('.esb_user_product_id :selected').text());
                                            product.val($(this).value);
                }))
                .on('click', '.search_product_btn', (function(I){
                    var route = $(this)[0].dataset.searchField;
                    let search_field = $('#'+$(this)[0].dataset.searchField);
                    var route = search_field[0].dataset.route;
                    $('.invalid-'+route).html("").hide();

                    $.get("/api/esb-product-serach-"+route+"/"+search_field.val(),function(data){
                        var list = JSON.parse(data).data;
                        
                        let product_id = document.getElementById('esb_user_product_id');
                        let client_id = document.getElementById('client_id');
                        suggest_count = list.length;
                        if(suggest_count > 0){
                            if(suggest_count > 1) {
                                $('#'+route+'_wrapper').html("").show()
                                ;$('#'+route+'_wrapper').append('<div>Найдено оборудование</div>');
                                for(var i in list){
                                    if(list[i] != ''){
                                        $('#'+route+'_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].product_name+' '+list[i].address_str+'</div>');
                                        
                                        $('#'+route+'_wrapper').find('#result_id-'+list[i].id).click(function() {
                                            prod_data=list[$(this)[0].getAttribute('data-key')];
                                            
                                            product_id.innerHTML = '<option selected value="'+prod_data.id+'">'+prod_data.product_name+' SN:'+prod_data.serial+' '+prod_data.address_str+'</option>';
                                            client_id.innerHTML = '<option selected value="'+prod_data.user_id+'">'+prod_data.user_name+'</option>';
                                            $('#product_serial').val(prod_data.serial);
                                            $('#product_name').val(prod_data.product_name);
                                            product.val(prod_data.product_id);
                                            
                                            $('#'+route+'_wrapper').fadeOut(2350).html('');
                                        });
                                    }
                                }
                            } else {
                                    prod_data=list[0];
                                    product_id.innerHTML = '<option selected value="'+prod_data.id+'">'+prod_data.product_name+' SN:'+prod_data.serial+' '+prod_data.address_str+'</option>';
                                    client_id.innerHTML = '<option selected value="'+prod_data.user_id+'">'+prod_data.user_name+'</option>';
                                    $('#product_serial').val(prod_data.serial);
                                    $('#product_name').val(prod_data.product_name); 
                                    product.val(prod_data.product_id);                                    
                                  
                            
                            }
                            
                            
                        } else {
                        console.log(route);
                            $('.invalid-'+route).html("").show();
                            $('.invalid-'+route).html('Данные не найдены!');
                        
                        }
                    }, 'html');
    
    
                
                
                })
                ).on('change', '#client_id', (function(I){
                    
                    let product_id = document.getElementById('esb_user_product_id');
                
                    product_id.disabled = true;
                    product_id.innerHTML = '<option value="">{{trans('site::messages.data_load')}}</option>';

                    function checkStatus(response) {
                        if (response.status >= 200 && response.status < 300) {
                            return response;
                        } else {
                            let error = new Error(response.statusText);
                            error.response = response;
                            throw error
                        }
                    }

                    function parseJSON(response) {
                        return response.json()
                    }

                    function renderProductsList(data) {
                        product_id.disabled = false;
                        if (typeof data.data != 'undefined') {
                            let list = '<option value="">{{trans('site::messages.select_from_list')}}</option>';
                            data.data.forEach(function (product, index) {
                                list += `<option value="${product.id}">${product.product_name} SN: ${product.serial} ${product.address_str} </option>`;
                            });
                            product_id.innerHTML = list;
                        } else{
                            product_id.innerHTML = '<option value="">{{trans('site::user.esb_repair.select_client_first')}}</option>';
                        }
                    }

                    fetch('/api/esb-product-serach-user-id/?userId=' + event.target.value)
                        .then(checkStatus)
                        .then(parseJSON)
                        .then(renderProductsList)
                        .catch(error => console.error(error));
                }))
                
        
        
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush
