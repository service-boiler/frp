@extends('layouts.email')

@section('title')
    'Срок поставки по тендерам истекает'
@endsection

@section('h1')
    
            <b>Истекает срок поставки товара по тендерам</b>
@endsection

    @section('body')
        
@foreach($tenders as $tender)



<p><b>Планируемая дата закупки: </b> {{ $tender->planned_purchase_year }} @lang('site::messages.months_cl.' .$tender->planned_purchase_month) 
<br /><b>Тендер №</b> {{$tender->id }} ({{$tender->status->name }})
<br /><b>Менеджер:</b> {{$tender->user->name }}
<br /><b>Дистрибьютор:</b> {{$tender->distributor->name }}
<br /><b>Адрес:</b> {{$tender->address }}
<br /><b>Объект:</b> {{$tender->address_name }}
<br /><b>Оборуование:</b> <br />@foreach($tender->tenderProducts as $item)
                        <li><b>{{$item->product->name}}</b>, Количество: {{$item->count}} шт, 
                        РРЦ: {{ $item->product->tenderPrice->type->currency->symbol_left }} {{$item->product->retailPriceEur->valueRaw}}, 
                        Запрашиваемая скидка: {{$item->discount}}%, 
                        Цена со скидкой: 
                        @if($item->approved_status == '1')
                        {{ $item->product->tenderPrice->type->currency->symbol_left }} {{$item->cost}}
                        @else
                        {{ $item->product->tenderPrice->type->currency->symbol_left }} {{$item->product->retailPriceEur->valueRaw*(100 - $item->discount)/100}}
                        @endif
                        </li><br />
                        
                        @endforeach
        
<hr />        
@endforeach
    
    @endsection

