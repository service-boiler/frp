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
                <a href="{{ route('retail-sale-reports.index') }}">@lang('site::retail_sale_report.reports')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::retail_sale_report.new_report')</li>
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
                      enctype="multipart/form-data"
                      action="{{ route('retail-sale-reports.store') }}">
                    @csrf
                    
                    
                    
                    
                    <div class="card mt-2 mb-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-5">

                                    <div class="form-group required">
                                        <label class="control-label" for="product_id">
                                            @lang('site::retail_sale_report.product_id')
                                        </label>
                                        <select required
                                                id="list_product_id"
                                                name="report[product_id]"
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($products as $product)
                                                <option @if(old('report.product_id') == $product->id)
                                                        selected
                                                        @endif
                                                        value="{{$product->id}}">
                                                    {{$product->name}} - ( 
                                                        @if($user_motivation_status =='basic')
                                                            {{$product->product_retail_sale_bonus ? $product->product_retail_sale_bonus->value : 0}}
                                                        @elseif($user_motivation_status =='start')
                                                            {{$product->product_retail_sale_bonus ? $product->product_retail_sale_bonus->start : 0}}
                                                        @elseif($user_motivation_status =='profi')
                                                            {{$product->product_retail_sale_bonus ? $product->product_retail_sale_bonus->profi : 0}}
                                                        @endif
                                                        
                                                        баллов за продажу)
                                                </option>
                                            @endforeach
                                        </select>
                                        <small id="product_idHelp" class="d-block form-text text-success">
                                            @lang('site::retail_sale_report.product_id_help')
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('report.product_id') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">

                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="serial_id">@lang('site::retail_sale_report.serial_id')</label>
                                        <input type="text" required
                                               name="report[serial_id]"
                                               id="serial_id"
                                               class="form-control{{ $errors->has('report.serial_id') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::retail_sale_report.serial_id_placeholder')"
                                               maxlength="20"
                                               value="{{ old('report.serial_id') }}">
                                        <span class="invalid-feedback">{{ $errors->first('report.serial_id') }}</span>

                                    </div>
                                 
                                    
                                </div>
                              
                                <div class="col-sm-3">
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="date_trade">@lang('site::retail_sale_report.date_trade')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_trade"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="report[date_trade]"
                                                   id="date_trade"
                                                   maxlength="10"
                                                   required
                                                   data-target="#datetimepicker_date_trade"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('report.date_trade') ? ' is-invalid' : '' }}"
                                                   value="{{ old('report.date_trade') }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_trade"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('report.date_trade') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-5">

                                    <div class="form-group required">
                                        <label class="control-label" for="address_id">
                                            @lang('site::retail_sale_report.address_id')
                                        
                                        </label>
                                        <select
                                                id="list_product_id"
                                                name="report[address_id]"
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($addresses as $address)
                                                <option @if(old('report.address_id') == $address->id)
                                                        selected
                                                        @endif
                                                        value="{{$address->id}}">
                                                    {{$address->full}} 
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('report.address_id') }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-7">

                                    <div class="form-group">
                                        <label class="control-label" for="address_new">
                                         <small class="text-success">
                                            @lang('site::retail_sale_report.address_help')
                                        </small>
                                        </label>
                                       <input type="text"
                                               name="report[address_new]"
                                               id="address_new"
                                               class="form-control{{ $errors->has('report.address_new') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::retail_sale_report.address_new_placeholder')"
                                               maxlength="20"
                                               value="{{ old('report.address_new') }}">
                                               
                                        
                                        <span class="invalid-feedback">{{ $errors->first('report.product_id') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">    
                                   <div class="card mb-2 card-body bg-danger text-white">
                                                Внимание! Оборудование, не участвующее в программе мотивации (п 4.1 Правил):
                                                <div class="d-none">
                                          <p><ul>
                                                <li>- проданное ранее 01.10.2020г.</li>
                                                <li>- с датой производства ранее 36 недели 2019 г (например, <span style="color:black; font-weight: 700;">1935</span>L08362 или <span style="color:black; font-weight: 700;">1920</span>0001)</li>
                                                <li>- объектные поставки</li>
                                                <li>- реализованное или смонтированное на территории Калининградской области или вне территории РФ.</li>
                                                </ul></p>
                                                </div>
                                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('fixed-height-450')" 
                                            class="align-text-bottom text-left text-white">
                                            <b>ПОКАЗАТЬ</b>
                                        </a>
                            
                                    </div>
                            </div>


                        </div>
                    </div>
                    


                    <div class="card my-2">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::retail_sale_report.comment')</h5>

                            <div class="form-group">
                                <textarea
                                        class="form-control{{ $errors->has('report.comment') ? ' is-invalid' : '' }}"
                                        placeholder="@lang('site::retail_sale_report.comment_placeholder')"
                                        name="report[comment]"
                                        id="comment">{{ old('report.comment') }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('report.comment') }}</span>
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
                                <button form="form"
                                        type="submit"
                                        class="btn btn-ms mb-1">
                                    <i class="fa fa-check"></i>
                                    <span>@lang('site::messages.save')</span>
                                </button>
                                <a href="{{ route('mountings.index') }}" class="btn btn-secondary mb-1">
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

                let product_id = $('#list_product_id');

                product_id.select2({
                    theme: "bootstrap4",
                    placeholder: '@lang('site::messages.select_from_list')',
                    selectOnClose: true,
                });

                product_id.on('select2:select', function (e) {
                    let data = e.params.data;
                    //console.log(data);
                    $('#product-name').html(data.text);
                    $('#product-sku').html(data.element.getAttribute('data-sku'));
                    let bonus = data.element.getAttribute('data-bonus'),
                        social = data.element.getAttribute('data-social-bonus');
                    $('#product-bonus').html(bonus === '0' ? '@lang('site::retail_sale_report.error.product_id')' : bonus);
                    $('#product-social-bonus').html(social === '0' ? '@lang('site::retail_sale_report.error.product_id')' : social);
                });
            });
        } catch (e) {
            console.log(e);
        }

    </script>
@endpush
