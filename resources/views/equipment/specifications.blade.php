@if($screenwidth < 800)
@foreach($equipment->availableProducts()->get() as $product)
    <div class="row">
        <div class="col-12 rngl text-wrap">{{$product->name}}</div>
    </div>

    @foreach($specs as $spec)
        <div class="row">
        <div class="col-7 tabl text-wrap">{{$spec->name_for_site}}@if(!empty($spec->unit)), {{$spec->unit}} @endif</div>
        <div class="col-5 tabc" style="display: flex;"><div style="margin: auto;">
        @if(!empty($product->specRelations()->where('product_spec_id',$spec->id)->first()))
        {{$product->specRelations()->where('product_spec_id',$spec->id)->first()->spec_value}}
        @else
        -
        @endif</div></div>
        
        </div>
    @endforeach
        <div class="row mb-3">
        <div class="col-7 tabl text-wrap">Артикул</div>
        <div class="col-5 tabc" style="display: flex;"><div style="margin: auto;">{{$product->sku}}</div></div>
        
            @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby']))    
                <div class="col-12">        
                @include('site::cart.buy.large', $product->toCart())
                </div>
            @endif
        </div>
@endforeach

@else


@foreach($equipment->availableProducts()->get()->chunk($cols) as $chunk)
    <div class="row">
        <div class="col-4 rngl"><div style="margin: auto; font-weight: 600; font-family: helvetica;">Модель</div></div>

    @foreach($chunk as $pkey=>$product)
    <div class="col-sm rngc text-wrap"><div style="margin: auto; font-weight: 600; font-family: helvetica;">{{$product->name}}</div></div>
    @endforeach
    </div>
    
    @foreach($specs as $spec)
    <div class="row">
       <div class="col-4 tabl text-wrap">{{$spec->name_for_site}}@if(!empty($spec->unit)), {{$spec->unit}} @endif</div>
        @foreach($chunk as $pkey=>$product)
            <div class="col-sm tabc text-wrap">
            @if(!empty($chunk[$pkey]->specRelations()->where('product_spec_id',$spec->id)->first()))
            {{$chunk[$pkey]->specRelations()->where('product_spec_id',$spec->id)->first()->spec_value}}
            @else
            -
            @endif
            </div>
        @endforeach
    </div>    
    @endforeach
    <div class="row mb-3">
        <div class="col-4 tabl text-wrap">Артикул</div>
        @foreach($chunk as $pkey=>$product)
            <div class="col-sm tabc text-wrap"><div style="margin: auto;">{{$product->sku}}</div></div>
        @endforeach
    </div>
    
    
    
@endforeach

@endif
