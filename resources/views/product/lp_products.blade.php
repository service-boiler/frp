{{--<div class="col-6 pr-5 pl-0 mb-5 d-none d-md-inline-block">
    <div class="lp-market-products-card d-inline-block w-100 lp-mp">
        <div class="d-flex">
            <div class="lp-mp-card-2">
                <h3>ИЩЕТЕ ГДЕ ОБСЛУЖИВАТЬ<br>ВАШ КОТЕЛ?</h3>
            </div>
            <div class="lp-mp-card-3">
                <img src="/images/lp/fix1.svg">
            </div>
        </div>
        <div class="lp-mp-hr-4 d-block">
            В официальном сервисном<br/> центре Ferroli
        </div>
        <div class="d-block">
            <a href="{{route('service-centers')}}" class="btn btn-ms lp-mp-card-btn">Выбрать сервисный центр</a>
        </div>
    </div>
</div>
--}}
@if($products->count())
@foreach($products as $product)


    <div class="col-12 col-md-6 mb-5" style="padding-right:24px;">
        <div class="lp-market-products-card d-inline-block w-100 lp-mp pl-1 pl-md-0">
               <div class="row d-block d-sm-none">
                    <div class="col-12 pl-5">
                         <h4><a target="_blank" href="{{route('products.show',$product)}}">{{ $product->name }}</a></h4>
                    </div>
               </div>
               <div class="row">
               <div class="col-6 pr-0"><img class="d-block w-100 lp-mp-card-img"
                                         src="{{ $product->image()->src() }}"
                                         alt="{{$product->name}}">

                                    
                                </div>
                                
        <div class="col-6 pl-0">
            <div class="row lp-market-products-card-descr"><div class="col-12">
                <h4 class="d-none d-sm-block mb-3" ><a target="_blank" href="{{route('products.show',$product)}}">{{ $product->name }}</a></h4>
            {!!$product->equipment ? $product->equipment->annotation : ''!!}

                <div class="row mt-3">
                @if($product->hasPrice &&  $product->showRetPrice())
                                    <div class="col-12 col-md-4 mr-0 pr-0">
                                        @if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                                   <span class="mt-1 old-price-gray"><s>{{ Site::format($product->price->value) }}</s></span>
                                        @else

                                        @endif
                                    </div>
                                    <div class="col-12  col-md-5 h3 text-md-right pr-0">
                                        @if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                            
                                           {{ Site::format($product->pricepromo->value) }}
                                        @elseif($product->price->value)
                                            {{ Site::format($product->price->value) }}


                                        @else
                                            <small>@lang('site::price.help.price')</small>
                                        @endif

                                    </div>
                    @endif
                            </div>
            </div></div>
            <div class="row mt-0">
                @if($product->forsale & $product->for_preorder && $product->group && in_array($product->group->type->id, ['1','3']))

                                    <div class="col-12 text-center mt-1">@include('site::cart.buy.large_lp')</div>

                                        @endif



                </div>
                </div>
                </div>
            </div>
            </div>
@endforeach
@else
    <div class="col-6 pr-5 pl-0 mb-5 error-card" >
        По выбранным параметрам нет подходящих котлов. <br />Пожалуйста, измените параметры отбора.
    </div>


@endif