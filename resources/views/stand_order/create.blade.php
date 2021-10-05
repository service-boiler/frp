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
            <a href="{{ route('orders.index') }}">@lang('site::stand_order.orders')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.create')</li>
    </ol>
    <h1 class="header-title mb-4"><i
                class="fa fa-magic"></i> @lang('site::messages.create') @lang('site::stand_order.order')</h1>

    @alert()@endalert()
    @if($addresses->count() == 0)
        <div class="card mb-4 card-body bg-danger text-white px-2 d-inline-block">
        В Вашем личном кабинете нет фактических адресов Ваших магазинов. Пожалуйста, добавьте адрес магазина, в котором будут размещены выставочные образцы!
        </div>
        @endif

    <div class="card mt-2 mb-2">
    <form id="form"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('stand-orders.store') }}">
                      @csrf
                      <input type="hidden" name="standOrder[status_id]" value="1">
                      
       
        
        <div class="card-body">
            <h5>Добавьте в заказ желаемое оборудование (котлы, автоматику, дымоходы и другое оборудование и принадлежности)</h5>
            Специальные цены будут установлены после одобрения заказа менеджером Ферроли.
            </div>
        <div class="card-body">
            <div class="row">
             
                <div class="col-5">
                <fieldset class="border p-3" id="parts-search-fieldset">
                    <div class="form-group required">
                        <label class="control-label"
                                   for="parts_search">@lang('site::stand_order.add_item')</label>
                            <select data-limit="10" id="parts_search" class="form-control">
                                <option value=""></option>
                            </select>
                            <span id="parts_searchHelp" class="d-block form-text text-success">
                            @lang('site::order_item.help.product_id')
                        </span>
                    </div>
                    
                </div>
                
                <div class="col-7">
                    <div class="form-group">
                        <label class="control-label"
                               for="">@lang('site::order.items')</label>
                        <div class="list-group" id="parts"
                             data-currency-symbol="{{ auth()->user()->currency->symbol_right }}">
                            @foreach($parts as $part)
                                                    @include('site::part.create', ['product' => $part['product'], 'count' => $part['count']])
                            @endforeach
                            
                            
                            
                        </div>
                       <hr />
                        <div class="text-right text-xlarge">
                            @lang('site::part.total'):
                            @if(!$parts->isEmpty())
                                <span id="total-cost">
                                    {{Site::format($parts->sum('cost') + old('cost_difficulty', 0) + old('cost_distance', 0))}}
                                    </span>
                            @else
                                {{Site::currency()->symbol_left}}
                                <span id="total-cost">0</span>
                                {{Site::currency()->symbol_right}}
                            @endif

                        </div>
                    </div>
                    <button type="button" class="btn btn-ms-blue btn-sm stand-add">
                        Добавить стойку для оборудования
                    </button>
                </div>
               </fieldset>

               
               
            </div>
        </div>
    
    
    <div class="card-body">
<!-- Плательщик -->    
        <div class="form-group required">
            <label class="control-label"
                   for="contragent_id">@lang('site::stand_order.contragent')</label>
            <div class="input-group">
                <select required
                        class="form-control{{  $errors->has('standOrder.contragent_id') ? ' is-invalid' : '' }}"
                        name="standOrder[contragent_id]"
                        id="contragent_id">
                    @if($contragents->count() == 0 || $contragents->count() > 1)
                        <option value="">@lang('site::messages.select_from_list')</option>
                    @endif
                    @foreach($contragents as $contragent)
                        <option
                                @if(old('standOrder.contragent_id') == $contragent->id) selected
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
            <span class="invalid-feedback">{{ $errors->first('standOrder.contragent_id') }}</span>
        </div>
        
<!-- Адрес витрины -->        
        <div class="form-group required">
            <label class="control-label"
                   for="address_id">@lang('site::stand_order.address')</label>
            <div class="input-group">
                <select required
                        class="form-control{{  $errors->has('standOrder.address') ? ' is-invalid' : '' }}"
                        name="standOrder[address_id]"
                        id="address_id">
                    @if($addresses->count() == 0 || $addresses->count() > 1)
                        <option value="">@lang('site::messages.select_from_list')</option>
                    @endif
                    @foreach($addresses as $address)
                        <option
                                @if(old('standOrder.address_id') == $address->id) selected
                                @endif
                                value="{{ $address->id }}">
                            {{ $address->name }} {{ $address->full }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-@lang('site::address.icon')"></i>
                    </div>
                </div>
            </div>
            <span class="invalid-feedback">{{ $errors->first('standOrder.address') }}</span>
        </div>
        
        
<!-- Адрес дистрибьютора -->        
        <div class="form-group required">
            <label class="control-label"
                   for="warehouse_address_id">@lang('site::stand_order.warehouse_address_id')<br />
                   <span class="text-muted">Выберите дистрибьютора, у которого будет приобретено оборудование по специальной цене.</span></label>
            <div class="input-group">
                <select required
                        class="form-control{{  $errors->has('standOrder.warehouse_address_id') ? ' is-invalid' : '' }}"
                        name="standOrder[warehouse_address_id]"
                        id="warehouse_address_id">
                    @if($addresses->count() == 0 || $addresses->count() > 1)
                        <option value="">@lang('site::messages.select_from_list')</option>
                    @endif
                    @foreach($warehouses as $warehouse_address)
                        <option
                                @if(old('standOrder.warehouse_address_id') == $warehouse_address->id) selected
                                @endif
                                value="{{ $warehouse_address->id }}">
                            {{ $warehouse_address->name }} {{ $warehouse_address->full }}
                        </option>
                    @endforeach
                </select>
                <div class="input-group-append">
                    <div class="input-group-text">
                        <i class="fa fa-@lang('site::address.icon')"></i>
                    </div>
                </div>
            </div>
            <span class="invalid-feedback">{{ $errors->first('standOrder.warehouse_address_id') }}</span>
        </div>
        <div class="row no-gutters">
            
        <div class="form-group required d-flex col flex-column"><label class="control-label"
                   for="message_text">@lang('site::stand_order.comment')</label>
                                    <div class="flex-grow-1 position-relative">
                                        <div class="form-group">
                                            <input type="hidden" name="message[receiver_id]"
                                                   value="{{$user->id}}">
                                            <textarea required name="message[text]"
                                                      id="message_text"
                                                      rows="4"
                                                      class="form-control{{  $errors->has('message.text') ? ' is-invalid' : '' }}"></textarea>
                                            <span class="invalid-feedback">{{ $errors->first('message.text') }}</span>
                                        </div>
                                        
                                    </div>
                                </div>
        </div>
        
    </form>    
        <div class="form-group">
            <div class="col text-right">
                <button form="form" type="submit"
                        class="btn btn-ms mb-1">
                    <i class="fa fa-check"></i>
                    <span>@lang('site::messages.save')</span>
                </button>
                <a href="{{ route('stand-orders.index') }}" class="btn btn-secondary mb-1">
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

            let product = $('#product_id'),
                parts_search = $('#parts_search'),
                parts = $('#parts'),
                selected = [];
            let number_format = function (number, decimals, dec_point, thousands_sep) {

                let i, j, kw, kd, km;

                // input sanitation & defaults
                if (isNaN(decimals = Math.abs(decimals))) {
                    decimals = 0;
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
                    
                        selected.splice(index, 1);
                        $('.product-' + $(this).data('id')).remove();
                    
                    calc_parts();
                }))
                .on('click', '.stand-add', (function () {
                if (!selected.includes('УТ-00002764')) {
                    parts_search.removeClass('is-invalid');
                    selected.push('УТ-00002764');
                    axios
                        .get("/api/stand-items/create/УТ-00002764")
                        .then((response) => {
                            parts.append(response.data);
                            $('[name="count[УТ-00002764]"]').focus();
                            calc_parts();
                            parts_search.val(null)
                        })
                        
                    calc_parts();
                    }
                }))
                .on('keyup mouseup', '.parts_count', (function () {
                    calc_parts();
                }));
            let calc_parts = function () {
                let cost = 0;
                parts.children().each(function (i) {
                    let el = $(this).find('.parts_count');
                    cost += (parseInt(el.data('cost')) * el.val());
                });

                $('#total-cost').html(number_format(cost));
            };
            calc_parts();

            parts_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/stand-items',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_part]': params.term,
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
                    } else {
                    return "-- выберите товары --";
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
                        .get("/api/stand-items/create/" + product_id)
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
