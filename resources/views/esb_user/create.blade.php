@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-product-launches.index') }}">@lang('site::user.esb_product_launch.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::user.esb_product_launch.new')</h1>

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
                                  action="{{ route('esb-product-launches.store') }}">
                                @csrf
                       
                            <div class="row">
                                <div class="col-sm-3 mr-0">
                                    <div class="form-row mb-2 required">
                                        <label class="control-label w-100"
                                               for="search_serial">@lang('site::user.esb_product_launch.serial')</label>
                                        
                                            <div class="col-10 mr-0">
                                                <input type="text"
                                                       name="serial" required
                                                       id="search_serial"
                                                       data-route="serial"
                                                       maxlength="24"
                                                       class="earch_product form-control{{ $errors->has('serial') ? ' is-invalid' : '' }}"
                                                       value="{{ old('serial') }}"
                                                       placeholder="@lang('site::user.esb_product_launch.search_serial')">
                                                
                                            
                                            <span class="invalid-feedback invalid-serial">{{ $errors->first('search_serial') }}</span>
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
                                               for="date_launch">@lang('site::user.esb_product_launch.phone')</label>
                                        
                                        <div class="col-10 mr-0">
                                            <input type="text"
                                                   name="phone"
                                                   id="search_phone"
                                                   data-route="phone"
                                                   maxlength="24"
                                                   class="search_product form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                                   value="{{ old('phone') }}"
                                                   placeholder="@lang('site::user.esb_product_launch.search_phone')">
                                            
                                        
                                        <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
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
                                
                              
                                
                                <div class="col-sm-3">
                                    <div class="form-row mb-2 required">
                                        <label class="control-label"
                                               for="date_launch">@lang('site::user.esb_product_launch.date_launch')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_launch"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="date_launch" required
                                                   id="date_launch"
                                                   maxlength="10"
                                                   
                                                   placeholder="@lang('site::messages.datetimepicker_placeholder')"
                                                   data-target="#datetimepicker_date_launch"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('date_launch') ? ' is-invalid' : '' }}"
                                                   value="{{ old('date_launch') }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_launch"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('date_launch') }}</span>
                                    </div>
                                </div>
                                
                                <div class="col-sm-3">
                                    <div class="form-row mb-2 required">
                                        <label class="control-label"
                                               for="date_launch">@lang('site::user.esb_user_product.date_trade')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_sale"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="date_sale" required
                                                   id="date_sale"
                                                   maxlength="10"
                                                   
                                                   placeholder="@lang('site::messages.datetimepicker_placeholder')"
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
                                </div>
                            </div>
                           <h5 id="product_info_header">Информация об оборудовании:</h5>
                           <span class="text-success" id="product_info_help">Информация загрузится по результатам поиска серийного номера или телефона. Оборудование должно быть зарегистрировано в ЛК потребителя.</span>
                            <div class="form-row">
                                <input type="hidden" name="esb_user_product_id" id="esb_user_product_id" value="">
                                    <div class="col mb-3" id="product_info">
                                        
                                   <dl class="row mb-0"> 
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.product')</dt>
                                        <dd class="col-sm-9" id="product_info_product"></dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                                        <dd class="col-sm-9" id="product_info_serial"></dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                                        <dd class="col-sm-9" id="product_info_address"></dd>
                                        
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_product_launch.user')</dt>
                                        <dd class="col-sm-9" id="product_info_user_name"></dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                                        <dd class="col-sm-9" id="product_info_second"></dd>
                                        <dt class="col-sm-3 text-left text-sm-right"></dt>
                                        <dd class="col-sm-9 text-success d-none" id="product_info_perm">@lang('site::user.esb_user_product.perm_help')</dd>
                                    </dl>
                                    </div>
                            </div>
                           
                           
                            <div class="form-row">
                                <div class="col-sm-6">

                                    <label class="control-label" for="engineer_id">Инженер, осуществивший работы</label>
                                    <select class="form-control{{  $errors->has('engineer_id') ? ' is-invalid' : '' }}"
                                            name="engineer_id"
                                            id="engineer_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($engineers as $engineer)
                                            <option
                                                    @if(old('engineer_id') == $engineer->id)
                                                    selected
                                                    @endif
                                                    value="{{ $engineer->id }}">{{ $engineer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('engineer_id') }}</span>
                                    @if(empty($engineers)) <span class="text-sucess">Ваши инженеры должны быть зарегистрированы на сайте под своим телефоном или email</span>@endif
                                </div>
                                <div class="col-sm-3">
                                    <label class="control-label"
                                               for="number">@lang('site::user.esb_product_launch.number')</label>
                                    
                                    <input type="text"
                                           name="number"
                                           id="number"
                                           maxlength="50"
                                           class="form-control{{ $errors->has('number') ? ' is-invalid' : '' }}"
                                           value="{{ old('number') }}"
                                           placeholder="@lang('site::user.esb_product_launch.number_placeholder')">
                                    
                                    <span class="invalid-feedback">{{ $errors->first('number') }}</span>
                                        
                                </div>
                                <div class="col-sm-3">
                                    {{--
                                    <label class="control-label" for="contract_id">Договор с клиентом</label>
                                    <select class="form-control{{  $errors->has('contract_id') ? ' is-invalid' : '' }}"
                                            name="contract_id"
                                            id="contract_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($contracts as $contract)
                                            <option
                                                    @if(old('contract_id') == $engineer->id)
                                                    selected
                                                    @endif
                                                    value="{{ $contract->id }}">{{ $contract->id .' ' .$contract->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">{{ $errors->first('contract_id') }}</span>
                                    --}}
                                </div>
                            </div>
                           
                            <div class="form-row">
                                
                                    <div class="col mb-3">
                                        <label class="control-label" for="comments">@lang('site::user.esb_request.comments_equipment')</label>
                                        <textarea
                                              name="comments"
                                              id="comments"
                                              class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              >{{ old('comments') }}</textarea>
                                        <span class="invalid-feedback">{{ $errors->first('comments') }}</span>
                                    </div>
                            </div>
                        </form>
                        
                        
                                    <h5 class="card-title">@lang('site::file.files')</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">@lang('site::file.maxsize5mb')</h6>
                                    @include('site::file.create.type')
                               
                            

                            <div class="form-row">
                                <div class="col text-right">
                                    <button form="form" type="submit"
                                            class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span id="save_btn">
                                        @lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('esb-product-launches.index') }}" class="btn btn-secondary mb-1">
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
        
            suggest_count = 0;
            $(document)
                .on('click', '.search_product_btn', (function(I){
                    var route = $(this)[0].dataset.searchField;
                    let search_field = $('#'+$(this)[0].dataset.searchField);
                    var route = search_field[0].dataset.route;
                    $('.invalid-'+route).html("").hide();

                    $.get("/api/esb-product-serach-"+route+"/"+search_field.val(),function(data){
                        var list = JSON.parse(data).data;
                        
                        
                        suggest_count = list.length;
                        if(suggest_count > 0){
                          $('#save_btn').html ("{{trans('site::user.esb_product_launch.save_and_warranty')}}");
                            if(suggest_count > 1) {
                                $('#'+route+'_wrapper').html("").show()
                                ;$('#'+route+'_wrapper').append('<div>Найдено оборудование</div>');
                                for(var i in list){
                                    if(list[i] != ''){
                                        $('#'+route+'_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].product_name+' '+list[i].address_str+'</div>');
                                        
                                        $('#'+route+'_wrapper').find('#result_id-'+list[i].id).click(function() {
                                            prod_data=list[$(this)[0].getAttribute('data-key')];
                                        
                                            $('#esb_user_product_id').val(prod_data.id);
                                            $('#date_sale').val(prod_data.date_sale);
                                            $('#product_info_help').addClass('d-none');
                                            $('#product_info_header').addClass('text-success');
                                            $('#product_info_product').html(prod_data.product_name);
                                            $('#search_serial').val(prod_data.serial);
                                            $('#product_info_serial').html(prod_data.serial);
                                            $('#product_info_address').html(prod_data.address_str);
                                            $('#product_info_second').html(prod_data.product_no_cat);
                                            $('#product_info_user_name').html(prod_data.user_name);
                                           
                                            if(prod_data.permission_ok != 1){
                                                $('#product_info_perm').removeClass('d-none');
                                            }
                                            
                                            $('#'+route+'_wrapper').fadeOut(2350).html('');
                                        });
                                    }
                                }
                            } else {
                                    prod_data=list[0];
                                   $('#esb_user_product_id').val(prod_data.id);
                                   $('#date_sale').val(prod_data.date_sale);
                                   $('#product_info_help').addClass('d-none');
                                   $('#product_info_header').addClass('text-success');
                                   $('#product_info_product').html(prod_data.product_name);
                                   $('#product_info_serial').html(prod_data.serial);
                                   $('#product_info_address').html(prod_data.address_str);
                                   $('#product_info_second').html(prod_data.product_no_cat);
                                   $('#product_info_user_name').html(prod_data.user_name);
                                   
                                   if(prod_data.permission_ok != 1){
                                           $('#product_info_perm').removeClass('d-none');
                                           }
                            
                            }
                            
                            
                        } else {
                        console.log(route);
                            $('.invalid-'+route).html("").show();
                            $('.invalid-'+route).html('Данные не найдены!');
                        
                        }
                    }, 'html');
    
    
                
                
                })
                )
                
        
        
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush
