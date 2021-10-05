
@if(in_array($standOrder->status_id, array(1,6,7,8)) && in_array($standOrder->in_stock_type, array(1,2)))
    {{ $standOrder->total(978, false, true) }} ({{ $standOrder->total(643, false, true) }})
@else
    {{ $standOrder->total(643, true, true) }}
@endif