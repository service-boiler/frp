@extends('site::layouts.print')

@section('content')
    <div class="container">
                
<h5>@lang('site::stand_order.order_h') @lang('site::stand_order.breadcrumb_show', ['standOrder' => $standOrder->id, 'date' => $standOrder->created_at->format('d.m.Y H:i') ])</h5>
        <div class="row">
            <div class="col-xl-12">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge text-normal badge-pill text-white"
                                  style="background-color: {{ $standOrder->status->color }}">
                                <i class="fa fa-{{ $standOrder->status->icon }}"></i> {{ $standOrder->status->name }}
                            </span>
                            
                            <span class="text-muted">@lang('site::stand_order.created_at'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->created_at->format('d.m.Y H:i' )}}</span>&nbsp;
                            </div>

                            <div class="mb-2">                        
                            <span class="text-muted">@lang('site::stand_order.user_id'):</span>&nbsp;
                            <span class="text-dark">{{ $standOrder->user->name }}</span>&nbsp;
                        
                            <span class="text-muted">@lang('site::stand_order.contragent_id'):</span>&nbsp;
                            <span class="text-dark">{{ $standOrder->contragent->name }}
                            </span>
                        &nbsp;
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{$standOrder->user->email}}</span>
                        </div>

                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.address_id'):</span>&nbsp;
                            <span class="text-dark">{{ optional($standOrder->address)->name }}</span>
                            <span class="text-dark">{{ optional($standOrder->address)->full }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::stand_order.warehouse_address_id'):</span>
                            <span class="text-dark">{{ optional($standOrder->warehouse_address)->name }}</span>
                        &nbsp;
                            <span class="text-muted">@lang('site::stand_order.shipped_at'):</span>&nbsp;
                            
                               <span class="text-dark">@if(!empty($standOrder->shipped_at)){{ $standOrder->shipped_at->format('d.m.Y') }}@endif</span>
                                
                          </div>   
                            
                    </div>
                </div>

            </div>
            </div>
            <div class="row">
                <div class="col-xl-6">
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::stand_order.items')</span>
                    </h6>
                    <div class="card-body">
                        @foreach($standOrder->items as $item)
                            <hr class="mb-3"/>
                            <div class="row mb-sm-1">

                                <div class="col-sm-9">
                                    <div class="row">
                                        
                                        <div class="col-sm-12">
                                            <span class="d-block"
                                               >{!! $item->product->name() !!}</span>
                                            <div class="text-left">
                                                {{ $item->quantity }} {{ $item->product->unit }} x
                                                {{Site::convert($item->price, $item->currency_id, $item->currency_id, 1, false, true)}}
                                                @if ($item->currency_id != 643)
                                                    ({{Site::convert($item->price, $item->currency_id, 643, 1, false, true)}}
                                                    )
                                                @endif
                                                РРЦ: {{$item->retailPrice->price}} @if($item->retailPrice->price > 0)(-{{round(($item->retailPrice->price - $item->price) * 100 / $item->retailPrice->price, 2) }}%)@endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 mb-4 mb-sm-0 text-left text-sm-right">
                                    {{Site::convert($item->price, $item->currency_id, $item->currency_id, $item->quantity, false, true)}}
                                    @if ($item->currency_id != 643)
                                        <br />({{Site::convert($item->price, $item->currency_id, 643, $item->quantity, true, true)}}
                                        )
                                    @endif
                                    
                                </div>
                            </div>

                        @endforeach
                        
                        </div>
                        <div class="card-body">
                        
                        <div class="mb-2">
                            <div class="font-weight-bold mb-0">@lang('site::stand_order.total')</div>&nbsp;
                            <div class="text-dark display-6 font-weight-bolder">
                                @include('site::stand_order.total')
                            </div>
                        </div>
                           </div>
                    </div>
                </div>
                 
                <div class="col-xl-6">
                <div class="card mb-4">
                    <h6 class="card-header with-elements"><span class="card-header-title">@lang('site::message.messages')</span></h6>
                    <div class="card-body flex-grow-1 position-relative overflow-hidden">
                        @if($standOrder->messages->isNotEmpty())
                            <div class="row no-gutters h-100">
                                <div class="d-flex col flex-column">
                                    <div class="flex-grow-1 position-relative">
                                        <div class="chat-messages p-1">

                                            @foreach($standOrder->messages as $message)
                                                <div class="@if($message->user_id == auth()->user()->getAuthIdentifier()) chat-message-right @else chat-message-left @endif">
                                                    <div>
                                                       
                                                        <div class="text-muted small text-nowrap mt-2">{{ $message->created_at->format('d.m.Y H:i') }}</div>
                                                    </div>
                                                    <div class="flex-shrink-1 bg-lighter rounded py-2 px-3 @if($message->user_id == auth()->user()->getAuthIdentifier()) mr-3 @else ml-3 @endif">
                                                        <div class="mb-1"><b>{{$message->user->name}}</b>
                                                        <span class="text-big">{!! nl2br($message->text) !!}</span></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                       
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
                    
                   
                }))
                
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
                        .get("/api/stand-items/createAdmin/" + product_id)
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
