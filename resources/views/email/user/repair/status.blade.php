@extends('layouts.email')

@section('title')
    @lang('site::repair.email.status_change.title')
@endsection

@section('h1')
    @lang('site::repair.email.status_change.title')
@endsection

@section('body')
    <p><b>@lang('site::repair.id')</b>: {{$repair->id }}</p>
    <p><b>@lang('site::repair.status_id')</b>: {{$repair->status->name }}</p>
    @if($repair->status->id == '5')
    <p><b>Напоминаем, что для получения компенсации за выполненные гарантийные ремонты Вам необходимо прислать в адрес Ферроли Рус 
          <br />сводный ежемесячный отчет на работы по гарантийному обслуживанию за прошедший календарный месяц.
    <br />А также прислать оригиналы документов: 
    <ul>
        <li>счет, </li>
        <li>акт приема-передачи работ (2 экз), </li>
        <li>бланк отчета выполненного ремонта (скачать из бланка заполненного отчета по ремонту в ЛК), </li>
        <li>отрезной гарантийный талон (либо заверенную копию талона старого образца).</li>
        </ul>
    Все документы должны быть с подписями и печатями.
    Адрес:  @lang('site::feedback.post_address')
    (Раздел №6 договора)</b>
    @endif
    <p>
        <a class="btn btn-ms btn-lg" href="{{ route('repairs.show', $repair) }}">
            &#128194; @lang('site::messages.open') @lang('site::repair.repair') {{ route('repairs.show', $repair) }}</a>
    </p>
@endsection