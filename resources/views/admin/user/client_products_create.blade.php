@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.index') }}">Клиенты</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.show',$user) }}">{{ $user->name }}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_user_product.add')</li>
        </ol>

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
        <div class="row justify-content-center mb-5">
            <div class="col">
                <form id="form"
                      method="POST"
                      action="{{ route('esb-user-products.store') }}">
                    @csrf
                    <div class="card mb-2">
                        <div class="card-body">

                            <div class="form-row mb-3">
                                <div class="col-sm-3">
                                    <label class="control-label" for="client">@lang('site::user.esb_user_product.serial')</label>
                                    <input type="text"
                                           id="serial"
                                           name="serial"
                                           class="serial form-control{{ $errors->has('serial') ? ' is-invalid' : '' }}"
                                           value="{{ old('serial') }}"
                                           placeholder="@lang('site::user.esb_user_product.serial_placeholder')">
                                    <span class="invalid-feedback">{{ $errors->first('serial') }}</span>
                                </div>
                                <input type="hidden" name="serial_finded" id="serial_finded" value="0">
                            
                            
                                <div class="col-sm-3">
                                    <label class="control-label"
                                           for="date_sale">@lang('site::user.esb_user_product.date_sale')</label>
                                    <div class="input-group date datetimepicker" id="datetimepicker_date_sale"
                                         data-target-input="nearest">
                                        <input type="text"
                                               name="date_sale"
                                               id="date_sale"
                                               maxlength="10"
                                               
                                               placeholder="@lang('site::mounter.placeholder.mounter_at')"
                                               data-target="#datetimepicker_date_sale"
                                               data-toggle="datetimepicker"
                                               class="datetimepicker-input form-control{{ $errors->has('date_sale') ? ' is-invalid' : '' }}"
                                               value="{{ old('date_sale') }}">
                                        <div class="input-group-append"
                                             data-target="#datetimepicker_date_sale"
                                             data-toggle="datetimepicker">
                                            <div class="input-group-text">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="invalid-feedback">{{ $errors->first('date_sale') }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="control-label" for="client">@lang('site::user.esb_user_product.dealer')</label>
                                    <input type="text"
                                           id="sale_comment"
                                           name="sale_comment"
                                           class="form-control{{ $errors->has('sale_comment') ? ' is-invalid' : '' }}"
                                           value="{{ old('sale_comment') }}"
                                           placeholder="@lang('site::user.esb_user_product.dealer_placeholder')">
                                    <span class="invalid-feedback">{{ $errors->first('sale_comment') }}</span>
                                </div>
                            </div>
                            
                            
                            <div class="form-row mb-3">
                                <div class="col-sm-6">
                                    <label class="control-label"
                                           for="address_id">@lang('site::user.esb_user_product.address_id')</label>
                                    <select class="form-control{{  $errors->has('address_id') ? ' is-invalid' : '' }}"
                                            name="address_id"
                                            id="address_id">
                                        @if($addresses->count() == 0 || $addresses->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($addresses as $address)
                                            <option
                                                    @if(old('address_id') == $address->id) selected
                                                    @endif
                                                    value="{{ $address->id }}">{{ $address->full }} </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('address_id') }}</span>

                                </div>
                                <div class="col-sm-6"><label class="control-label"
                                                             for="address_id">Владелец оборудования</label>
                                    <input type="text" class="form-control" name="user_name" value="{{$user->name}}" disabled>
                                    <input type="hidden" class="form-control" name="user_id" value="{{$user->id}}">
                                </div>
                            </div>


                            <div class="form-row">
                                <div class="col-sm-4">
                                    <label class="control-label"
                                           for="brand_id">Бренд</label>
                                    <select class="form-control{{  $errors->has('brand_id') ? ' is-invalid' : '' }}"
                                            name="brand_id"
                                            id="brand_id">
                                        @if($brands->count() == 0 || $brands->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($brands as $brand)
                                            <option
                                                    @if(old('brand_id') == $brand->id) selected
                                                    @endif
                                                    value="{{ $brand->id }}">{{ $brand->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('brand_id') }}</span>
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label"
                                           for="equipment_id">@lang('site::user.esb_user_product.equipment_id')</label>
                                    <select class="form-control{{  $errors->has('equipment_id') ? ' is-invalid' : '' }}"
                                            name="equipment_id"
                                            id="equipment_id">
                                        @if($equipments->count() == 0)
                                            <option value="">Выберите бренд</option>
                                        @elseif($equipments->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($equipments as $equipment)
                                            <option
                                                    @if(old('equipment_id') == $equipment->id) selected
                                                    @endif
                                                    value="{{ $equipment->id }}">{{ $equipment->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('equipment_id') }}</span>
                                </div>

                                <div class="col-sm-4">
                                    <label class="control-label"
                                           for="product_id">@lang('site::mounter.product_id')</label>
                                    <select class="form-control{{  $errors->has('product_id') ? ' is-invalid' : '' }}"
                                            name="product_id"
                                            id="product_id">
                                        @if($products->count() == 0)
                                            <option value="">Выберите модельный ряд</option>
                                        @else
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($products as $product)
                                            <option
                                                    @if(old('product_id') == $product->id) selected
                                                    @endif
                                                    value="{{ $product->id }}">{{ $product->name }} {{ $product->phone }}</option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('product_id') }}</span>
                                </div>
                            </div>

                            <div class="form-row mb-3">
                                <label class="control-label" for="client">@lang('site::user.esb_user_product.product_no_cat')</label>
                                <textarea
                                       id="product_no_cat"
                                       name="product_no_cat"
                                       class="form-control{{ $errors->has('product_no_cat') ? ' is-invalid' : '' }}"
                                       >{{ old('product_no_cat') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('product_no_cat') }}</span>
                            </div>


                    </div>
                    <div class="form-row">
                        <div class="col text-left">
                            <button form="form" type="submit"
                                    class="btn btn-ms mb-1">
                                <i class="fa fa-check"></i>
                                <span>@lang('site::messages.save')</span>
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary mb-1">
                                <i class="fa fa-close"></i>
                                <span>@lang('site::messages.cancel')</span>
                            </a>
                        </div>
                    </div>



                </div>
                        </div>
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
        
            suggest_count = 0;
            input_initial_value = '';
            suggest_selected = 0;
            $(document)
                .on('change', '.serial', (function(I){
                            $(this).attr('autocomplete','off');
                                input_initial_value = $(this).val();
                                $.get("/api/serial-product", { "serial":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    if(list.product_id!='empty') {
                                        document.getElementById('equipment_id').value = list.equipment_id;
                                        product_id.innerHTML = '<option selected value="'+list.product_id+'">'+list.product_name+'</option>'
                                    }
                                    
                                }, 'html');
                            
                        
                })
                )
        
        
            document.getElementById('equipment_id').addEventListener('change', function () {

                let product_id = document.getElementById('product_id');
                
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
                    if (data.data.length > 0) {
                        let list = '<option value="">{{trans('site::messages.select_from_list')}}</option>';
                        data.data.forEach(function (product, index) {
                            list += `<option value="${product.id}">${product.name}</option>`;
                        });
                        product_id.innerHTML = list;
                    } else{
                        product_id.innerHTML = '<option value="">{{trans('site::mounter.help.select_equipment')}}</option>';
                    }
                }

                fetch('/api/products/search?filter[equipment_id]=' + event.target.value)
                    .then(checkStatus)
                    .then(parseJSON)
                    .then(renderProductsList)
                    .catch(error => console.error(error));
            });

            document.getElementById('brand_id').addEventListener('change', function () {

                let equipment_id = document.getElementById('equipment_id');

                equipment_id.disabled = true;
                equipment_id.innerHTML = '<option value="">{{trans('site::messages.data_load')}}</option>';

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

                function renderEquipmentsList(data) {
                    equipment_id.disabled = false;
                    if (data.data.length > 0) {
                        let list = '<option value="">{{trans('site::messages.select_from_list')}}</option>';
                        data.data.forEach(function (equipment, index) {
                            list += `<option value="${equipment.id}">${equipment.name}</option>`;
                        });
                        equipment_id.innerHTML = list;
                    } else{
                        equipment_id.innerHTML = '<option value="">Выберите бренд</option>';
                    }
                }

                fetch('/api/equipments?filter[brand_id]=' + event.target.value)
                    .then(checkStatus)
                    .then(parseJSON)
                    .then(renderEquipmentsList)
                    .catch(error => console.error(error));
            });



        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush