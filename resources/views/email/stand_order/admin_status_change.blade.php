@extends('layouts.email')

@section('title')
    @lang('site::stand_order.email_status_change_title')
@endsection

@section('h1')
    @lang('site::stand_order.email_status_change_h1')
@endsection

@section('body')
    <p><b>@lang('site::stand_order.id')</b>: {{$standOrder->id }}</p>
    <p><b>@lang('site::stand_order.user_id')</b>: {{$standOrder->user->name }}</p>
    <p><b>@lang('site::stand_order.status_id')</b>: {{$standOrder->status->name }}</p>
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('admin.stand-orders.show', $standOrder) }}">
            &#128194; @lang('site::messages.open') @lang('site::stand_order.order') {{ route('admin.stand-orders.show', $standOrder) }}</a>
    </p>
    @if($standOrder->status->id == 14)
       <p> 
        Вам необходимо проверить и отправить заказ в работу бухгалтеру для начисления компенсации дистрибьютору.
        </p>
    @endif
    @if($standOrder->status->id == 12)
       <p> 
        Вам необходимо проверить и отправить заказ на проверку руководителю для начисления компенсации дистрибьютору.
        </p>
    @endif
    @if($standOrder->status->id == 13)
       <p> <b>
        Заказ проверен менеджером и руководителем отдела. Товар на витрине.
        Вам необходимо рассчитать компенсацию дистрибьютору по этому заказу и отправить финансовые документы на согласование директору.
        </b></p>
    @endif
@endsection