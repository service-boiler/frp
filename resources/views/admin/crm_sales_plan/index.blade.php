@extends('layouts.app')
@section('title') @lang('site::admin.crm_sales_plan.index') @endsection
@section('content')
    <div class="container" style="max-width: 1600px;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.crm_sales_plan.index')
            </li>
            <div class="ml-md-auto">
                <li class="breadcrumb-item">
                    <button form="repository-form"
                            type="submit"
                            name="excel"
                            class="d-inline-block mr-0 mr-sm-1 mb-0 mb-sm-0 mt-0 btn btn-sm btn-primary">
                        <i class="fa fa-upload"></i>
                        <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
                    </button>


                </li>

            </div>
        </ol>

        @alert()@endalert()
        @filter(['repository' => $repository])@endfilter
<a class="mr-3" href="{{route('admin.crm-sales-plan.index',['year'=>$year-1])}}">{{$year-1}}</a>
<a class="mr-3" href="{{route('admin.crm-sales-plan.index',['year'=>$year+1])}}">{{$year+1}}</a>
        @for($num_month=1; $num_month<=12; $num_month++)
        <a href="javascript:void(0);" class=" @if($num_month<$cur_month || $num_month>$cur_month+1) gray @endif  stat-col-show mr-3"
           data-column="{{$num_month}}">
            <i class="fa fa-check"></i>@lang('site::messages.months_cl.' .$num_month)</a>
        @endfor
<div class="mb-5">
        @foreach($bizRegions as $bizRegion)
            <span class="h3 rng mb-2 mt-4 mr-3">{{$bizRegion->name}}</span>

        @foreach($bizRegion->managers()->get() as $manager) <span class="badge text-normal badge-pill badge-secondary mb-1">{{$manager->name}}</span> @endforeach

            <div class="trow mb-0 tr-nw">
                <div class="col-160 htl">
                    Дистрибьютор
                </div>
                @for($num_month=1; $num_month<=12; $num_month++)
                <div class="col-201 @if($num_month<$cur_month || $num_month>$cur_month+1) d-none @endif stat-col stat-col-{{$num_month}}">
                    <div class="trow">
                        <div class="col-12 ht1 text-center">@lang('site::messages.months.' .$num_month)</div>
                    </div>
                    <div class="trow">
                        <div class="col-4 ht1 st2 no-br text-center">
                            План<br/>(€ без НДС)
                        </div>
                        <div class="col-4 ht1 st2 no-br text-center">
                            Факт<br/>(€ без НДС)
                        </div>
                        <div class="col-4 ht1 st2 text-center">
                            % <br />выполн.
                        </div>

                    </div>


                </div>
                @endfor

                        @for($num_month=1; $num_month<=12; $num_month++)
                        <div class="col col-140 @if($num_month<$cur_month || $num_month>$cur_month+1) d-none @endif stat-col stat-col-{{$num_month}}">
                            <div class="row">
                                <div class="col-12 ht1 text-center">@lang('site::messages.months_cl.' .$num_month) - прогноз</div>
                            </div>
                            <div class="row">
                                <div class="col-6 ht1 st2 no-br text-center">
                                    Общий
                                </div>
                                <div class="col-6 ht1 st2">
                                    <span>Объекты</span>
                                </div>

                            </div>


                        </div>
                        @endfor
                        <div class="col col-120">
                            <div class="row">
                                <div class="col-12 ht1 st3 no-br text-center">
                                    Не отгруженные<br/>заказы<br/>(€ без НДС)
                                </div>
                            </div>
                        </div>
                        <div class="col col-120">
                            <div class="row">
                                <div class="col-12 ht1 st3 no-br text-center">
                                    Годовой план<br/>(€ без НДС)
                                </div>
                            </div>
                        </div>
                        <div class="col col-120">
                            <div class="row">
                                <div class="col-12 ht1 st3 no-br text-center">
                                    Годовой факт<br/>(€ без НДС)
                                </div>
                            </div>
                        </div>
                        <div class="col col-120">
                            <div class="row">
                                <div class="col-12 ht1 st3 no-br text-center">
                                    Процент<br />выполнения<br />годового плана
                                </div>
                            </div>
                        </div>

            </div>
            @foreach($bizRegion->distributors() as $user)
                <div class="trow hl mb-0 tr-nw">
                    <div class="col col-160 clt no-bt">
                        <a target="_blank" class="black" href="{{route('admin.users.show',$user)}}">{{$user->name_short}}</a>
                    </div>
                            @for($num_month=1; $num_month<=12; $num_month++)
                                <div class="col-201 @if($num_month<$cur_month || $num_month>$cur_month+1) d-none @endif stat-col stat-col-{{$num_month}}">
                                    <div class="trow">

                                        <div class="col-4 ct1 no-bt text-center cell-plan-{{$user->id}}-{{$num_month}}">
                                            @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                            <a href="javascript:void(0);" class="click_edit "
                                               id="cell-plan-{{$user->id}}-{{$num_month}}"
                                               data-cell="plan-{{$user->id}}-{{$num_month}}">
                                                <span @if($user->crmSalesPlan('month',$num_month,$year) && $user->crmSalesPlan('month',$num_month,$year)->enabled!="1")
                                                        class="text-warning"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      title="План внесен менеджером и еще не утвержден"
                                                      @endif
                                                >{{$user->crmSalesPlan('month',$num_month,$year) ? moneyFormatEuro($user->crmSalesPlan('month',$num_month,$year)->value) :'-----'}}</span>
                                            </a>
                                            @else
                                                {{$user->crmSalesPlan('month',$num_month,$year) ? moneyFormatEuro($user->crmSalesPlan('month',$num_month,$year)->value) :'-----'}}
                                            @endif
                                        </div>
                                        <div class="col-4 ct1 no-bt no-bl text-center cell-plan-{{$user->id}}-{{$num_month}}">

                                            {{$user->result1cSale('sales_contragent_month_eur',$year, 'month', $num_month) ? moneyFormatEuro($user->result1cSale('sales_contragent_month_eur',$year, 'month', $num_month)->value) : '-----'}}
                                        </div>
                                        <div class="col-4 ct1 no-bt no-bl text-center cell-plan-{{$user->id}}-{{$num_month}}">

                                            {{($user->crmSalesPlan('month',$num_month,$year) && $user->crmSalesPlan('month',$num_month,$year)->value) ? round((optional($user->result1cSale('sales_contragent_month_eur',$year, 'month', $num_month))->value)/$user->crmSalesPlan('month',$num_month,$year)->value  * 100, 1) .' %':'-----'}}
                                        </div>
                                    @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                        <div class="col-4 ct1 no-bt no-bl text-center transparent-form form-plan-{{$user->id}}-{{$num_month}} d-none" >
                                            <input type="number" name="plan-{{$user->id}}-{{$num_month}}" class="form-control ft1"
                                                   id="input-plan-{{$user->id}}-{{$num_month}}"
                                                   data-user="{{$user->id}}"
                                                   data-period-num="{{$num_month}}"
                                                   data-period="month"
                                                   data-year="{{$year}}"
                                                   data-table="plans"
                                                   value="{{$user->crmSalesPlan('month',$num_month,$year) ? round($user->crmSalesPlan('month',$num_month,$year)->value,0) :'0'}}">
                                        </div>
                                        <div class="col-4 ct1 no-bt no-bl text-center transparent-form form-plan-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_save"
                                               data-cell="plan-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-check"></i></a>
                                        </div>
                                        <div class="col-4 ct1 no-bt no-bl text-center transparent-form form-plan-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_cancel"
                                               data-cell="plan-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-close"></i></a>
                                        </div>
                                    @endif


                                    </div>
                                </div>
                            @endfor

                            @for($num_month=1; $num_month<=12; $num_month++)
                                <div class="col col-140 @if($num_month<$cur_month || $num_month>$cur_month+1) d-none @endif stat-col stat-col-{{$num_month}}">
                                <div class="row">

                                    <div class="col-6 ct1 no-bt no-br text-center cell-predict-tender-{{$user->id}}-{{$num_month}} cell-predict-all-{{$user->id}}-{{$num_month}}">
                                        @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                            <a href="javascript:void(0);" class="click_edit black"
                                               id="cell-predict-all-{{$user->id}}-{{$num_month}}"
                                               data-cell="predict-all-{{$user->id}}-{{$num_month}}">
                                                <span @if($user->crmSalesPredict('month',$num_month,'all',$year) && $user->crmSalesPredict('month',$num_month,'all',$year)->enabled!="1")
                                                      class="text-warning"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      title="Прогноз внесен менеджером и еще не утвержден"
                                                      @endif >
                                                {{$user->crmSalesPredict('month',$num_month,'all',$year) ? moneyFormatEuro($user->crmSalesPredict('month',$num_month,'all',$year)->value) :'-----'}}
                                            </a>
                                        @else
                                            {{$user->crmSalesPredict('month',$num_month,'all',$year) ? moneyFormatEuro($user->crmSalesPredict('month',$num_month,'all',$year)->value) :'-----'}}
                                        @endif



                                    </div>
                                    @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                        <div class="col-6 ct1 no-bt no-bl text-center transparent-form form-predict-all-{{$user->id}}-{{$num_month}} d-none" >
                                            <input type="number" name="predict-all-{{$user->id}}-{{$num_month}}" class="form-control ft1"
                                                   id="input-predict-all-{{$user->id}}-{{$num_month}}"
                                                   data-user="{{$user->id}}"
                                                   data-period-num="{{$num_month}}"
                                                   data-period="month"
                                                   data-year="{{$year}}"
                                                   data-type="all"
                                                   data-table="predicts"
                                                   value="{{$user->crmSalesPredict('month',$num_month,'all',$year) ? round($user->crmSalesPredict('month',$num_month,'all',$year)->value) :'0'}}">
                                        </div>
                                        <div class="col-3 ct1 no-bt no-bl text-center transparent-form form-predict-all-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_save"
                                               data-cell="predict-all-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-check"></i></a>
                                        </div>
                                        <div class="col-3 ct1 no-bt no-bl text-center transparent-form form-predict-all-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_cancel"
                                               data-cell="predict-all-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-close"></i></a>
                                        </div>
                                    @endif

                                    <div class="col-6 ct1 no-bt text-center cell-predict-all-{{$user->id}}-{{$num_month}} cell-predict-tender-{{$user->id}}-{{$num_month}}">
                                        @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                            <a href="javascript:void(0);" class="click_edit black"
                                               id="cell-predict-tender-{{$user->id}}-{{$num_month}}"
                                               data-cell="predict-tender-{{$user->id}}-{{$num_month}}">
                                                <span @if($user->crmSalesPredict('month',$num_month,'tender',$year) && $user->crmSalesPredict('month',$num_month,'tender',$year)->enabled!="1")
                                                      class="text-warning"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      title="Прогноз внесен менеджером и еще не утвержден"
                                                      @endif >
                                                {{$user->crmSalesPredict('month',$num_month,'tender',$year) ? moneyFormatEuro($user->crmSalesPredict('month',$num_month,'tender',$year)->value) :'-----'}}
                                            </a>
                                        @else
                                            {{$user->crmSalesPredict('month',$num_month,'tender',$year) ? moneyFormatEuro($user->crmSalesPredict('month',$num_month,'tender',$year)->value) :'-----'}}
                                        @endif



                                    </div>
                                    @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                        <div class="col-3 ct1 no-bt no-bl text-center transparent-form form-predict-tender-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_save"
                                               data-cell="predict-tender-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-check"></i></a>
                                        </div>
                                        <div class="col-3 ct1 no-bt no-bl text-center transparent-form form-predict-tender-{{$user->id}}-{{$num_month}} d-none">
                                            <a href="javascript:void(0);" class="rng click_cancel"
                                               data-cell="predict-tender-{{$user->id}}-{{$num_month}}">
                                                <i class="fa fa-close"></i></a>
                                        </div>
                                        <div class="col-6 ct1 no-bt no-bl text-center transparent-form form-predict-tender-{{$user->id}}-{{$num_month}} d-none" >
                                            <input type="number" name="predict-tender-{{$user->id}}-{{$num_month}}" class="form-control ft1"
                                                   id="input-predict-tender-{{$user->id}}-{{$num_month}}"
                                                   data-user="{{$user->id}}"
                                                   data-period-num="{{$num_month}}"
                                                   data-period="month"
                                                   data-year="{{$year}}"
                                                   data-type="tender"
                                                   data-table="predicts"
                                                   value="{{$user->crmSalesPredict('month',$num_month,'tender',$year) ? round($user->crmSalesPredict('month',$num_month,'tender',$year)->value) :'0'}}">
                                        </div>
                                    @endif


                                </div>
                                </div>
                            @endfor
                                <div class="col col-120">
                                    <div class="row">

                                        <div class="col-12 ct1 no-bt no-br text-center" id="form-predict-{{$user->id}}-{{$num_month}}">
                                            {{$user->result1cSale('not_ship_orders_contragent_month_eur') ? moneyFormatEuro($user->result1cSale('not_ship_orders_contragent_month_eur')->value) : '-----'}}
                                        </div>

                                    </div>
                                </div>
                                <div class="col col-120 cell-plan-year-{{$user->id}}-{{$num_month}}">
                                    <div class="row">
                                        <div class="col-12 ct1 no-bt no-br text-center">
                                        @if(auth()->user()->hasPermission('admin_plan_confirm') || $num_month>=$cur_month)
                                            <a href="javascript:void(0);" class="click_edit black"
                                               id="cell-plan-year-{{$user->id}}-{{$num_month}}"
                                               data-cell="plan-year-{{$user->id}}-{{$num_month}}">
                                                <span @if($user->crmSalesPlan('year', null, $year) && $user->crmSalesPlan('year', null, $year)->enabled!="1")
                                                      class="text-warning"
                                                      data-toggle="tooltip"
                                                      data-placement="top"
                                                      title="План внесен менеджером и еще не утвержден"
                                                      @endif
                                                >{{$user->crmSalesPlan('year', null, $year) ? moneyFormatEuro($user->crmSalesPlan('year', null, $year)->value) :'-----'}}</span>
                                            </a>
                                        @else
                                            {{$user->crmSalesPlan('year', null, $year) ? moneyFormatEuro($user->crmSalesPlan('year', null, $year)->value) :'-----'}}
                                        @endif

                                        </div>
                                    </div>

                                    </div>

                                    <div class="col col-120 form-plan-year-{{$user->id}}-{{$num_month}} d-none">
                                        <div class="row">
                                            <div class="col-12 ct1 no-bt no-br text-center transparent-form " >
                                                <input type="number" name="plan-year-{{$user->id}}-{{$num_month}}" class="form-control ft1"
                                                       id="input-plan-year-{{$user->id}}-{{$num_month}}"
                                                       data-user="{{$user->id}}"
                                                       data-period-num="{{$year}}"
                                                       data-period="year"
                                                       data-year="{{$year}}"
                                                       data-table="plans"
                                                       value="{{$user->crmSalesPlan('year', null, $year) ? round($user->crmSalesPlan('year', null, $year)->value,0) :'0'}}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col col-120 form-plan-year-{{$user->id}}-{{$num_month}} d-none">
                                        <div class="row">
                                            <div class="col-6 ct1 no-bt no-bl no-br text-center transparent-form form-plan-year-{{$user->id}}-{{$num_month}} d-none">
                                                <a href="javascript:void(0);" class="rng click_save"
                                                   data-cell="plan-year-{{$user->id}}-{{$num_month}}">
                                                    <i class="fa fa-check"></i></a>
                                            </div>
                                            <div class="col-6 ct1 no-bt no-bl text-center transparent-form form-plan-year-{{$user->id}}-{{$num_month}} d-none">
                                                <a href="javascript:void(0);" class="rng click_cancel"
                                                   data-cell="plan-year-{{$user->id}}-{{$num_month}}">
                                                    <i class="fa fa-close"></i></a>
                                            </div>
                                        </div>
                                    </div>


                                <div class="col col-120 cell-plan-year-{{$user->id}}-{{$num_month}}">
                                    <div class="row">

                                        <div class="col-12 ct1 no-bt no-br text-center">
                                            {{$user->result1cSale('sales_contragent_year_eur',$year,'year') ? moneyFormatEuro($user->result1cSale('sales_contragent_year_eur',$year,'year')->value) : '-----'}}
                                        </div>

                                    </div>
                                </div>
                                <div class="col col-120">
                                    <div class="row">

                                        <div class="col-12 ct1 no-bt text-center">
                                            {{$user->crmSalesPlan('year', null,$year,'year')? round((optional($user->result1cSale('sales_contragent_year_eur',$year,'year'))->value)/$user->crmSalesPlan('year', null, $year)->value  * 100, 1) .' %' : '-----'}}
                                        </div>

                                    </div>
                                </div>

                </div>
            @endforeach
        @endforeach
</div>

    <div class="pb-3">
        <h1 class="text-dark">Не утвержденные планы дистрибьюторов</h1>
        @foreach($users->all()->sortBy('region_id')->sortBy('name') as $user)

        @if($user->crmSalesPlans()->where('enabled',0)->exists())
            <h5>{{$user->name}}</h5>
            @foreach($user->crmSalesPlans()->where('enabled',0)->orderBy('period_num')->get() as $plan)
            <div class="row mb-2">
                <div class="col-1">
                    {{moneyFormatEuro($plan->value)}}
                </div>
                <div class="col-2">
                    @lang('site::messages.' .$plan->period_type_id)
                    @if($plan->period_type_id=='month')
                    @lang('site::messages.months.' .$plan->period_num)
                    @endif
                    {{$plan->year}}
                </div>
            </div>

            @endforeach
        @endif
        @endforeach

    </div>
    <div class="pb-3">
        <h1 class="text-dark">Не утвержденные прогнозы дистрибьюторов</h1>
        @foreach($users->all()->sortBy('region_id')->sortBy('name') as $user)

        @if($user->crmSalesPredicts()->where('enabled',0)->exists())
            <h5>{{$user->name}}</h5>
            @foreach($user->crmSalesPredicts()->where('enabled',0)->orderBy('period_num')->get() as $plan)
            <div class="row mb-2">
                <div class="col-1">
                    {{moneyFormatEuro($plan->value)}}
                </div>
                <div class="col-1">
                    {{$plan->type->name}}
                </div>
                <div class="col-2">

                    @lang('site::messages.' .$plan->period_type_id)
                    @if($plan->period_type_id=='month')
                    @lang('site::messages.months.' .$plan->period_num)
                    @endif
                    {{$plan->year}}
                </div>
            </div>

            @endforeach
        @endif
        @endforeach

    </div>
        @if(auth()->user()->hasPermission('admin_plan_confirm'))
    <div class="pb-3">
        <form method="POST" id="plans_confirm" action="/api/users/set-plan">
            @csrf
            <button name="all_plans_confirm" value="all" class="btn btn-ms" type="submit">Утвердить все планы и прогнозы</button>
        </form>

    </div>
        @endif
    <div class="pb-3">
        <h5><a href="javascript:void(0);" onclick="($('#logs').toggleClass('d-none'))" >История измениний плана</a></h5>
        <div class="d-none" id="logs">
            @if(auth()->user()->hasPermission('admin_plan_confirm'))
            @foreach($logs as $log)
                 {{$log->created_at->format('Y.m.d H:m')}} {{$log->user->name}}   {{$log->text}} ({{$log->manager->name}})<br />
            @endforeach
            @else
                История доступна только пользователям с правами на утверждение плана.
            @endif
        </div>

    </div>



</div>
@endsection



@push('styles')
    <link href="/css/temp.css" rel="stylesheet">
@endpush
@push('scripts')
    <script>
        try {
            window.addEventListener('load', function () {
                $(document)

                .on('click', '.click_edit', (function(I){
                    $('.cell-'+$(this)[0].dataset.cell).addClass('d-none');
                    $('.form-'+$(this)[0].dataset.cell).removeClass('d-none');


                    })
                )
                .on('click', '.click_save', (function(I){
                    console.log('#input-'+$(this)[0].dataset.cell);
                    $('.form-'+$(this)[0].dataset.cell).addClass('d-none');
                    $('.cell-'+$(this)[0].dataset.cell).removeClass('d-none');
                    let inputField=$('#input-'+$(this)[0].dataset.cell);
                    if(inputField[0].dataset.table == 'plans'){
                        axios

                            .post("/api/users/set-plan", {"value": inputField[0].value, "user_id": inputField[0].dataset.user,"period_type_id": inputField[0].dataset.period, "period_num": inputField[0].dataset.periodNum , "year": inputField[0].dataset.year})

                             .then((response) => {
                                console.log(response.data);
                               $('#cell-'+$(this)[0].dataset.cell).html(response.data);

                            })
                            .catch((error) => {
                                this.status = 'Error:' + error;
                            });
                    }
                    if(inputField[0].dataset.table == 'predicts'){
                        axios

                            .post("/api/users/set-predict", {"value": inputField[0].value, "user_id": inputField[0].dataset.user,"predict_type_id": inputField[0].dataset.type, "period_type_id": inputField[0].dataset.period, "period_num": inputField[0].dataset.periodNum , "year": inputField[0].dataset.year})

                             .then((response) => {
                                console.log(response.data);
                               $('#cell-'+$(this)[0].dataset.cell).html(response.data);

                            })
                            .catch((error) => {
                                this.status = 'Error:' + error;
                            });
                    }
                    })
                )
                .on('click', '.click_cancel', (function(I){
                    console.log($(this)[0].dataset.cell);
                    $('.form-'+$(this)[0].dataset.cell).addClass('d-none');
                    $('.cell-'+$(this)[0].dataset.cell).removeClass('d-none');


                    })
                )


                .on('click', '.stat-col-show', (function(I){
                        $(this).toggleClass('rng').toggleClass('gray');
                        $('.stat-col-'+$(this)[0].dataset.column).toggleClass('d-none');
                    })
                )



            });

        } catch (e) {
            console.log(e);
        }

    </script>
@endpush
