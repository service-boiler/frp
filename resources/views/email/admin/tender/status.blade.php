@extends('layouts.email')

@section('title')
    'Изменен статус заявки на тендер'
@endsection
@section('h1')
    <a class="btn btn-ms" href="{{ route('admin.tenders.show', $tender) }}">
            <b>{{ route('admin.tenders.show', $tender) }}</b></a>
@endsection

@section('body')
    <p><b>Тендер №</b> {{$tender->id }} ({{$tender->status->name }})</p>
    <p><b>Менеджер:</b> {{$tender->user->name }}</p>
    <p><b>Дистрибьютор:</b> {{$tender->distributor->name }}</p>
    <p><b>Адрес:</b> {{$tender->address }}</p>
    <p><b>Объект:</b> {{$tender->address_name }}</p>
    <p><b>Оборуование:</b> <br />@foreach($tender->tenderProducts as $item)
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
                    
                    @endforeach</p>
    @if(in_array($tender->status_id,['2','3']))
    <p><b>Заявку на тендер Вам необходимо проверить и одобрить или отказать</b></p>
    @endif
    @if(in_array($tender->status_id,['4']))
    <p><b>Заявку на тендер Вам необходимо проверить еще раз и приступить к выполнению</b></p>
    @endif
    
    
    <p><b>Статус заявки:</b> {{$tender->status->name }}</p>
    
    <p><b>Полследний комментарий: </b><br /> @foreach($tender->messages()->orderByDesc('created_at')->take(2)->get() as $message) {{$message->text}} <br />@endforeach</p>
    
@endsection