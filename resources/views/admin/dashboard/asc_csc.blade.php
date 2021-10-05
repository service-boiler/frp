@extends('layouts.app')
</div>
@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboards.index') }}">@lang('site::messages.dashboards')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.dashboard.asc_csc')</li>
        </ol>
        
        @alert()@endalert
        
        
        <div class="card mt-1 mb-1">
            <div class="card-body">
            <form method="get" action="{{route('admin.storehouses.index')}}" id="storehouses-form">
            </form>
            <form id="head-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.dashboards.asc_csc_year') }}">
                @csrf
               <div class="form-row">
               <label class="control-label" for="year">Выберите год:</label>
               </div>
               <div class="form-row">
                    <div class="col-md-2">
                        <div class="form-row required">
                            <div class="col">
                                    
                                <select class="form-control" name="year" required  id="year">
                                    <option value="">@lang('site::messages.select_from_list')</option>
                                    @for($y=$years[0];$y<=$years[1];$y++)
                                        <option
                                                value="{{$y}}">{{$y}}
                                        </option>
                                       @endfor
                                </select>

                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-8">
                        <div class="form-row required">
                                
                            <button form="head-form" type="submit" name="excel" class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                                <i class="fa fa-upload"></i> <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
                            </button>
                            <button form="head-form" type="submit" class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-green">
                                <i class="fa fa-search"></i>
                                <span>Показать</span>
                            </button>
                            <button form="storehouses-form"
                                    type="submit"
                                    name="excel"
                                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-secondary">
                                <i class="fa fa-upload"></i>
                                <span>Остатки на складах</span>
                            </button>
                        </div>
                    </div>
                </div>
                <span class="text-success">Период учитывается только для документов и событий (АГР, АВР, Обучения). Склады и организации всегда выводятся на текущую дату</span>
            </form>
            
            </div>
        </div>
        <div class="row mb-2"><div class="col">
         <h5>Заявки на авторизацию, ожидающие одобрения:<a target="_blank" href="/admin/authorizations?filter[status_id]=1&filter[role]=1"><i class="fa fa-external-link"></i>{{$yearResults['count_authorizations_wait']}}</a></h5>
        </div> </div>                
            
                <table class="table-bordered bg-white table-sm table-report">
                    <tr>
                        <th>Кол-во АСЦ <br>на сегодня</th>
                        <th style="white-space: nowrap">Кол-во<br />АСЦ с дог.</th>
                        
                        <th>Кол-во инженеров <br>на сегодня</th>
                        <th>Кол-во инженеров <br>привязанных</th>
                        <th>Кол-во инженеров с<br>сертификатами</th>
                        <th>АГР прислано за год</th>
                        <th>АГР отклоненных {{$year}}</th>
                        <th>АГР удаленных {{$year}}</th>
                        <th>АГР в ожидании и новых {{$year}}</th>
                        <th>АГР одобрено {{$year}}</th>
                        <th>АГР оплачено {{$year}}</th>
                        <th width="140px">Сумма опл. АГР {{$year}}</th>
                        <th>Кол-во складов на сегодня ZIP</th>
                        <th>Себестоимость остатков (EUR) <br>на сегодня</th>
                        <th>Обучений за 1 квартал {{$year}}</th>
                        <th>Обучений за 2 квартал {{$year}}</th>
                        <th>Обучений за 3 квартал {{$year}}</th>
                        <th>Обучений за 4 квартал {{$year}}</th>
                        <th>Обучений за год {{$year}}</th>

                    </tr>
                    
                    <tr>
                        <td> {{$yearResults['count_asc']}}
                        
                        <br />нед: <span
                        @if(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_7'])) > 0)
                            class="text-nowrap">+@elseif(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_7'])) < 0)
                            class="text-nowrap">@endif{{$yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_7'])}}
                        </span>
                        <br />мес:<span
                        @if(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_30'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_30'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['last_30'])}}
                        </span>
                        
                        <br />нг:<span
                        @if(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_asc'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])}}
                        </span>
                        
                        
                        </td>
                        
                        <td> {{$yearResults['count_ascs_with_contract']}}
                        
                        <br />нед:
                        <span
                        @if(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])}}
                        </span>
                        <br />мес:
                        <span
                        @if(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])}}
                        </span>
                        <br />нг:
                        <span
                        @if(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_ascs_with_contract'] - $dashboard->resultSumAllRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])}}
                        </span>
                        
                        </td>
                        
                        <td> {{$yearResults['count_engeneers']}}
                        <br />нед:
                        <span
                        @if(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])}}
                        </span>
                        <br />мес:
                        <span
                        @if(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])) < 0)
                        class="text-nowrap">-@endif{{$yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])}}
                        </span>
                        <br />нг:
                        <span
                        @if(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])}}
                        </span>
                        </td>
                        
                        
                        
                        <td> {{$yearResults['count_engeneers_attached']}}
                        <br />нед:
                        <span
                        @if(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])}}
                        </span>
                        <br />мес:
                        <span
                        @if(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])}}
                        </span>
                        <br />нг:
                        <span
                        @if(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_attached'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])}}
                        </span>
                        </td>
                        
                        
                        
                        <td class="text-nowrap"> {{$yearResults['count_engeneers_cert']}}
                        
                        <br />нед:
                        <span
                        @if(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])}}
                        </span>
                        
                        <br />мес:
                        <span
                        @if(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])}}
                        </span>
                        
                        <br />нг:
                        <span
                        @if(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])) > 0)
                        class="text-nowrap">+@elseif(($yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])) < 0)
                        class="text-nowrap">@endif{{$yearResults['count_engeneers_cert'] - $dashboard->resultSumAllRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])}}
                        </span>
                        
                        </td>
                        
                        
                        
                        <td> год: {{$dashboard->repairsToDate($year, $periods['today'])->count()}} 
                        <br /> нед:
                        <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'])->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_7'])->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'])->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_7'])->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'])->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_7'])->count()}}
                                    </span> 
                        
                        
                        <br /> мес: <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'])->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_30'])->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'])->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_30'])->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'])->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_30'])->count()}}
                                    </span> 
                        <br /> {{!empty($dashboard->repairsYear($year-1)->count()) ? ($year-1) .': ' .$dashboard->repairsYear($year-1)->count() : 0}}
                        
                        </td>
                        
                        
                        
                        @foreach([4,6,[1,2,3],5] as $t_status_id)
                        
                        
                        <td>год: {{$dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count()}} 
                        <br />нед:
                        <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_7'],$t_status_id)->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_7'],$t_status_id)->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_7'],$t_status_id)->count()}}
                                    </span> 
                        
                        
                        <br />мес: <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_30'],$t_status_id)->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_30'],$t_status_id)->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'],$t_status_id)->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_30'],$t_status_id)->count()}}
                                    </span> 
                        <br />{{$year-1}}: {{!empty($dashboard->repairsYear($year-1,$t_status_id)->count()) ? $dashboard->repairsYear($year-1,$t_status_id)->count() : 0}}
                        
                        </td>
                        @endforeach
                        
                        <!--<td> {{$yearResults['repairs_declined']}} / <span class="text-muted">{{$dashboard->repairsYear($year-1,4)->count()}}</span></td> 
                        <td> {{$yearResults['repairs_deleted']}} / <span class="text-muted">{{$dashboard->repairsYear($year-1,6)->count()}}</span></td>
                        <td> {{$yearResults['repairs_wait']}} / <span class="text-muted">{{$dashboard->repairsYear($year-1,['1','2','3'])->count()}}</span></td>
                        <td> {{$yearResults['repairs_approved']}} / <span class="text-muted">{{$dashboard->repairsYear($year-1,5)->count()}}</span></td>-->
                        <td> год: {{$yearResults['repairs_paid']}}
                        <br />нед:
                        <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'],5,1)->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_7'],5,1)->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'],5,1)->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_7'],5,1)->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'],5,1)->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_7'],5,1)->count()}}
                                    </span> 
                        
                        
                        <br />мес: <span
                                    @if(($dashboard->repairsToDate($year, $periods['today'],5,1)->count() - 
                                                    $dashboard->repairsToDate($year, $periods['last_30'],5,1)->count()) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsToDate($year, $periods['today'],5,1)->count() 
                                                                                        - $dashboard->repairsToDate($year, $periods['last_30'],5,1)->count()) < 0)
                                    class="text-nowrap">@endif{{$dashboard->repairsToDate($year, $periods['today'],5,1)->count() 
                                                                                    - $dashboard->repairsToDate($year, $periods['last_30'],5,1)->count()}}
                                    </span> 
                        <br />{{$year-1}}: {{$dashboard->repairsYear($year-1,5,1)->count()}}</span></td>
                        
                        
                        <td class="text-nowrap"> год:{!!moneyFormatRub($dashboard->repairsPaidSummToDate($year, $periods['today']))!!}
                        <br />нед:
                        <span
                                    @if(($dashboard->repairsPaidSummToDate($year, $periods['today']) - 
                                                    $dashboard->repairsPaidSummToDate($year, $periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsPaidSummToDate($year, $periods['today']) 
                                                                                        - $dashboard->repairsPaidSummToDate($year, $periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{!!moneyFormatRub($dashboard->repairsPaidSummToDate($year, $periods['today']) 
                                                                                    - $dashboard->repairsPaidSummToDate($year, $periods['last_7']))!!}
                                    </span> 
                        
                        
                        <br />мес: 
                        <span
                                    @if(($dashboard->repairsPaidSummToDate($year, $periods['today']) - 
                                                    $dashboard->repairsPaidSummToDate($year, $periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($dashboard->repairsPaidSummToDate($year, $periods['today']) 
                                                                                        - $dashboard->repairsPaidSummToDate($year, $periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{!!moneyFormatRub($dashboard->repairsPaidSummToDate($year, $periods['today']) 
                                                                                    - $dashboard->repairsPaidSummToDate($year, $periods['last_30']))!!}
                                    </span> 
                        <br />{{$year-1}}: {!!moneyFormatRub($dashboard->repairsPaidSummToDate($year-1, $periods['first_day_year']))!!}
                        
                        
                        </td>
                        <td> {{$storehouses_csc->count()}}</td>
                        <td> ALL {{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_all_summ',$periods['today']))}}
                        <br />ZIP {{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['today']))}}
                        <br />EQ {{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_equipment_summ',$periods['today']))}}
                        <br />AC {{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_accessories_summ',$periods['today']))}}
                        
                        
                        </td>
                        <td> {{$yearResults['events_1q']}}</td>
                        <td> {{$yearResults['events_2q']}}</td>
                        <td> {{$yearResults['events_3q']}}</td>
                        <td> {{$yearResults['events_4q']}}</td>
                        <td> {{$yearResults['events_1q']+$yearResults['events_2q']+$yearResults['events_3q']+$yearResults['events_4q']}}</td>
                        
                    </tr> 
                    
                </table>  

<h2 class="mb-0">АСЦ и склады запчастей по регионам</h2>
<div class="row mb-2"><div class="col">
<span class="text-success mb-2">Регионы, в которых нет АСЦ не отображаются.</span>
</div></div>
                <table class="table-bordered bg-white table-sm table-report">
                    @foreach($ferroliManagers->get() as $user)
                    
                    <tr><td colspan="16" class="trngrb">{{$user->name}}</td></tr>
                    <tr>
                    <td></td><td colspan="12" class="text-center font-weight-bold">На сегодня<span class="text-muted"> / на начало года {{$year}}.01.01</span></td><td colspan="2"></td>
                    </tr>
                    <tr>
                        <th>Регион</th>
                        <th>Кол-во АСЦ</th>
                        <th>АСЦ с договором</th>
                        <th>Кол-во инженеров</th>
                        <th>Кол-во инженеров <br>привязанных</th>
                        <th>Кол-во инж. с<br>серт.</th>
                        <th>АГР прислано</th>
                        <th>АГР откл.</th>
                        <th>АГР удал.</th>
                        <th>АГР в ожидании и новых</th>
                        <th>АГР одобр</th>
                        <th>АГР оплач</th>
                        <th>Сумма опл. АГР</th>
                        <th>Кол-во складов ZIP на сегодня</th>
                        <th>Себестоимость остатков (EUR) <br>на сегодня</th>
                        

                    </tr>
                    <tr>
                                    <td class="ttaby">По всем регионам менеджера</td>
                                    <td class="ttaby">
                                        {{$user->notifiRegions()->get()->sum('count_asc_today')}}
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) - 
                                                    $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_7'])}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['last_30'])}}
                                    </span>    
                                    <br />нг:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_count',$periods['first_day_year'])}}
                                    </span>
                                        
                                        </td>
                                        
                                        
                                    <td class="ttaby">
                                        {{$user->notifiRegions()->get()->sum('count_asc_contract_today')}}
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) - 
                                                    $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_7'])}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['last_30'])}}
                                    </span>    
                                    <br />нг:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_asc_contract_count',$periods['first_day_year'])}}
                                    </span>
                                        
            
            
            
                                        </td>
                                    <td class="ttaby">
                                    {{$user->notifiRegions()->get()->sum('count_engeneers_today')}}
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) - 
                                                    $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_7'])}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['last_30'])}}
                                    </span>    
                                    <br />нг:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_count',$periods['first_day_year'])}}
                                    </span>
                                        
                                    </td>
                                    <td class="ttaby">
                                        {{$user->notifiRegions()->get()->sum('count_engeneers_attached_today')}}
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) - 
                                                    $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_7'])}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['last_30'])}}
                                    </span>    
                                    <br />нг:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_attached_count',$periods['first_day_year'])}}
                                    </span>
                                        
                                    </td>
                                    <td class="ttaby">
                                        {{$user->notifiRegions()->get()->sum('count_engeneers_cert_today')}}
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) - 
                                                    $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_7'])}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                        - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['last_30'])}}
                                    </span>    
                                    <br />нг:
                                    <span
                                    @if(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])) > 0)
                                    class="text-nowrap">+@elseif(($user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])) < 0)
                                    class="text-nowrap">@endif{{$user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['today']) 
                                                                                    - $user->resultSumNotifiRegionsTypeToDate('reg_engeneers_cert_count',$periods['first_day_year'])}}
                                    </span>
                                    </td>
                                    
                                    
                                    
                                    
                                    
                                    <td class="ttaby">
                                    год: {{!empty($user->managerRegionsRepairs($year)) ? $user->managerRegionsRepairs($year)->count() : 0}}
                                    
                                        
                                    <br />нед:
                                    <span
                                    @if(($user->managerRegionsToDayRepairs($year, $periods['today'])->count() - 
                                                    $user->managerRegionsToDayRepairs($year, $periods['last_7'])->count()) > 0)
                                    class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'])->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_7'])->count()) < 0)
                                    class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'])->count() 
                                                                                    - $user->managerRegionsToDayRepairs($year, $periods['last_7'])->count()}}
                                    </span> 
                                    <br />мес:
                                    <span
                                    @if(($user->managerRegionsToDayRepairs($year, $periods['today'])->count() - 
                                                    $user->managerRegionsToDayRepairs($year, $periods['last_30'])->count()) > 0)
                                    class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'])->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_30'])->count()) < 0)
                                    class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'])->count() 
                                                                                    - $user->managerRegionsToDayRepairs($year, $periods['last_30'])->count()}}
                                    </span> 
                                    <br />{{!empty($user->managerRegionsRepairs($year-1)) ? ($year-1) .': ' .$user->managerRegionsRepairs($year-1)->count() : 0}}</span>
                                    </td>
                                    
                                    @foreach([4,6,[1,2,3],5] as $t_status_id)
                                    
                                        <td class="ttaby">
                                        год: {{!empty($user->managerRegionsRepairs($year,$t_status_id)) ? $user->managerRegionsRepairs($year,$t_status_id)->count() : 0}}
                                        
                                            
                                        <br />нед:
                                        <span
                                        @if(($user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() - 
                                                        $user->managerRegionsToDayRepairs($year, $periods['last_7'],$t_status_id)->count()) > 0)
                                        class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() 
                                                                                            - $user->managerRegionsToDayRepairs($year, $periods['last_7'],$t_status_id)->count()) < 0)
                                        class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_7'],$t_status_id)->count()}}
                                        </span> 
                                        <br />мес:
                                        <span
                                        @if(($user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() - 
                                                        $user->managerRegionsToDayRepairs($year, $periods['last_30'],$t_status_id)->count()) > 0)
                                        class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() 
                                                                                            - $user->managerRegionsToDayRepairs($year, $periods['last_30'],$t_status_id)->count()) < 0)
                                        class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'],$t_status_id)->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_30'],$t_status_id)->count()}}
                                        </span> 
                                        <br />{{!empty($user->managerRegionsRepairs($year-1,$t_status_id)) ? ($year-1) .': ' .$user->managerRegionsRepairs($year-1,$t_status_id)->count() : 0}}</span>
                                        </td>
                                    @endforeach
                                    
                                    
                                    {{--
                                    <td class="ttaby">
                                    {{!empty($user->managerRegionsRepairs($year,4)) ? $user->managerRegionsRepairs($year,4)->count() : 0}} /
                                    <span class="text-muted">{{!empty($user->managerRegionsRepairs($year-1,4)) ? $user->managerRegionsRepairs($year-1,4)->count() : 0}}</span>
                                    </td>
                                    <td class="ttaby">
                                    {{!empty($user->managerRegionsRepairs($year,6)) ? $user->managerRegionsRepairs($year,6)->count() : 0}} /
                                    <span class="text-muted">{{!empty($user->managerRegionsRepairs($year-1,6)) ? $user->managerRegionsRepairs($year-1,6)->count() : 0}}</span>
                                    </td>
                                    <td class="ttaby">
                                    {{!empty($user->managerRegionsRepairs($year,['1','2','3'])) ? $user->managerRegionsRepairs($year,['1','2','3'])->count() : 0}} /
                                    <span class="text-muted">{{!empty($user->managerRegionsRepairs($year-1,['1','2','3'])) ? $user->managerRegionsRepairs($year-1,['1','2','3'])->count() : 0}}</span>
                                    </td>
                                    <td class="ttaby">
                                    {{!empty($user->managerRegionsRepairs($year,5)) ? $user->managerRegionsRepairs($year,5)->count() : 0}} /
                                    <span class="text-muted">{{!empty($user->managerRegionsRepairs($year-1,5)) ? $user->managerRegionsRepairs($year-1,5)->count() : 0}} </span>
                                    </td> --}}
                                    <td class="ttaby">
                                    год: {{!empty($user->managerRegionsRepairs($year,5,1)) ? $user->managerRegionsRepairs($year,5,1)->count() : 0}} 
                                    {{--   
                                    <br />нед:
                                        <span
                                        @if(($user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() - 
                                                        $user->managerRegionsToDayRepairs($year, $periods['last_7'],5,1)->count()) > 0)
                                        class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() 
                                                                                            - $user->managerRegionsToDayRepairs($year, $periods['last_7'],5,1)->count()) < 0)
                                        class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_7'],5,1)->count()}}
                                        </span> 
                                    
                                     
                                    <br />мес:
                                        <span
                                        @if(($user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() - 
                                                        $user->managerRegionsToDayRepairs($year, $periods['last_30'],5,1)->count()) > 0)
                                        class="text-nowrap">+@elseif(($user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() 
                                                                                            - $user->managerRegionsToDayRepairs($year, $periods['last_30'],5,1)->count()) < 0)
                                        class="text-nowrap">@endif{{$user->managerRegionsToDayRepairs($year, $periods['today'],5,1)->count() 
                                                                                        - $user->managerRegionsToDayRepairs($year, $periods['last_30'],5,1)->count()}}
                                        </span> 
                                    
                                    --}}
                                    <br />{{$year-1}}: {{!empty($user->managerRegionsRepairs($year-1,5,1)) ? $user->managerRegionsRepairs($year-1,5,1)->count() : 0}} 
                                    </td>
                                    <td class="ttaby">
                                    год: {!!moneyFormatRub($user->managerRegionsToDayRepairsSum($year, $periods['today']))!!}
                                    
                                    {{--
                                    <br />нед:<span class="text-nowrap">+{!! moneyFormatRub($user->managerRegionsToDayRepairsSumDiff($periods['last_7'], $periods['today'])) !!}</span> 
                                    <br />мес:<span class="text-nowrap">+{!! moneyFormatRub($user->managerRegionsToDayRepairsSumDiff($periods['last_30'], $periods['today'])) !!}</span> 
                                    --}}
                                    <br />
                                    {{$year-1}}: {!!moneyFormatRub($user->managerRegionsToDayRepairsSum($year-1, $periods['first_day_year']) )!!}
                                    
                                    </td>
                                    <td class="ttaby">
                                    {{$user->managerRegionsStorehousesCsc()->count()}}
                                    </td>
                                    <td class="ttaby">
                                    
                                    @if($user->managerRegionsStorehousesCsc()->get()->sum('result_total_cost_all_today') != $user->managerRegionsStorehousesCsc()->get()->sum('result_total_cost_zip_today'))
                                    ALL {!!moneyFormatEuro($user->managerRegionsStorehousesCsc()->get()->sum('result_total_cost_all_today'))!!}<br />
                                    @endif
                                    ZIP {!!moneyFormatEuro($user->managerRegionsStorehousesCsc()->get()->sum('result_total_cost_zip_today'))!!}
                                   
                                    </td>
                                   
                                    
                                </tr> 
                        
                        @foreach($user->notifiRegions()->get() as $key=>$region)
                        @if($region->result_count_type_to_date('reg_asc_count',$periods['today'])>0)
                        <tr>
                                    <td @if($key%2==1)class="ttabr"@endif>{{$region->name}}</td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->result_count_type_to_date('reg_asc_count',$periods['today'])}}
            <span class="text-muted"> / {{ $region->result_count_type_to_date('reg_asc_count',$periods['first_day_year'])}}</span>
                                        
                                        </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->result_count_type_to_date('reg_asc_contract_count',$periods['today'])}}
            <span class="text-muted"> / {{ $region->result_count_type_to_date('reg_asc_contract_count',$periods['first_day_year'])}}
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->engeneers()->count()}}
            <span class="text-muted"> / {{ $region->result_count_type_to_date('reg_engeneers_count',$periods['first_day_year'])}}
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->engeneers_attached()->count()}}
            <span class="text-muted"> / {{ $region->result_count_type_to_date('reg_engeneers_attached_count',$periods['first_day_year'])}}
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->engeneers_cert()->count()}}
            <span class="text-muted"> / {{ $region->result_count_type_to_date('reg_engeneers_cert_count',$periods['first_day_year'])}}
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year)->count()}} / 
                                   <span class="text-muted"> {{$region->repairs($year-1)->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year,4)->count()}} /
                                    <span class="text-muted">{{$region->repairs($year-1,4)->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year,6)->count()}} /
                                    <span class="text-muted">{{$region->repairs($year-1,6)->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year,['1','2','3'])->count()}} / 
                                    <span class="text-muted">{{$region->repairs($year-1,['1','2','3'])->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year,5)->count()}} /
                                    <span class="text-muted">{{$region->repairs($year-1,5)->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {{$region->repairs($year,5)->whereHas('act', function ($query) {$query->where('paid','1');})->count()}} /
                                    <span class="text-muted">{{$region->repairs($year-1,5)->whereHas('act', function ($query) {$query->where('paid','1');})->count()}}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    {!!moneyFormatRub(
                                    $region->repairs($year,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_difficulty_cost')
                                    +$region->repairs($year,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_distance_cost')
                                    +$region->repairs($year,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_cost_parts') )!!}
                                     / 
                                    <span class="text-muted">{!!moneyFormatRub(
                                    $region->repairs($year-1,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_difficulty_cost')
                                    +$region->repairs($year-1,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_distance_cost')
                                    +$region->repairs($year-1,5)->whereHas('act', function ($query) {$query->where('paid','1');})->get()->sum('total_cost_parts') )!!}</span>
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                        {{$region->storehouses_csc()->count()}}
                                    </td>
                                    <td @if($key%2==1)class="ttabr"@endif>
                                    @if($region->storehouses_csc()->get()->sum('result_total_cost_all_today') != $region->storehouses_csc()->get()->sum('result_total_cost_zip_today'))
                                    ALL {!!moneyFormatEuro($region->storehouses_csc()->get()->sum('result_total_cost_all_today'))!!}<br />
                                    @endif
                                    ZIP {!!moneyFormatEuro($region->storehouses_csc()->get()->sum('result_total_cost_zip_today'))!!}
                              
                                    </td>
                                    
                                </tr>    
                        @endif
                        @endforeach
                        <tr><td colspan="16" class="pt-3"></td></tr>
                    @endforeach
                 </table>  








<h2 class="mb-0">Остатки запчастей на региональных складах</h2>
<div class="row mb-2"><div class="col">
<span class="text-success mb-2">Остатки только пользоватлей со статусом "склад запчастей" (без дистрибьюторов)</span>
</div></div>

                
            <table class="table-bordered bg-white table-sm table-report">
                    <tr>
                        <th rowspan="2">Склады</th>
                        <th colspan="7">Остатки запчастей по ценам закупки для склада</th>
                        
                    </tr>
                    <tr>
                        <th>На сегодня</th>
                        <th>На вчера</th>
                        <th>На понедельник <br />{{$periods['monday']}}</th>
                        <th>На начало месяца<br />{{$periods['first_day_month']}}</th>
                        <th>Месяц назад<br />{{$periods['last_30']}}</th>
                        <th>Два месяца назад<br />{{$periods['last_60']}}</th>
                        <th>На начало года<br />{{$periods['first_day_year']}}</th>
                       

                    </tr>
                    
                    <tr>
                        <td>Все склады</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['today']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['yesterday']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['monday']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['first_day_month']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['last_30']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['last_60']))}}</td>
                        <td>{{moneyFormatEuro($storehouses[0]->cost_products_all_storehouses_type_to_date('storehouses_zip_summ',$periods['first_day_year']))}}</td>
                    </tr>
                    <tr>
                        <td colspan="8"></td>
                    </tr>    
            
            @foreach($ferroliManagers->get() as $user)
            <tr><td colspan="8" class="ttabr">{{$user->name}}</td></tr>
                @foreach($user->ferroliManagerStorehousesZip()->get() as $storehouse)
                <tr>
                            <td><a href="{{route('admin.storehouses.show',$storehouse)}}">{{$storehouse->name}}</a><span class="text-small"><br />{{$storehouse->user->name}} ({{$storehouse->user->region->name}})</span></td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['today']))}}
                            <br /><span style="font-size: 10px;">{{$storehouse->uploaded_at ? $storehouse->uploaded_at->format('d.m.Y') : '-'}}</span>
                            </td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['yesterday']))}}</td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['monday']))}}</td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['first_day_month']))}}</td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['last_30']))}}</td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['last_60']))}}</td>
                            <td>{{moneyFormatEuro($storehouse->cost_products_type_to_date('storehouse_zip_summ',$periods['first_day_year']))}}</td>
                        </tr>    
                
                @endforeach
            @endforeach
        </table> 
        <div class="card">
        <div class="card-body">
        <h3> АСЦ с ценами на запчасти не "Монтажник"</h3>
        @foreach($asc_not_discount as $asc)
        
            <div class="row mb-3"><div class="col">
                <a target="_blank" href="{{route('admin.users.show', $asc)}}">{{$asc->name}} </a>{{$asc->prices()->where('product_type_id', 8)->first() ? $asc->prices()->where('product_type_id', 8)->first()->priceType->name : 'РРЦ'}}
            
            </div></div>
        @endforeach
        </div>
        </div>
        <div class="card mt-3">
        <div class="card-body">
        <h3> АСЦ не отображаемые</h3>
        @foreach($asc_no_show as $asc)
        
            <div class="row mb-0 mt-3"><div class="col">
                <a target="_blank" href="{{route('admin.users.show', $asc)}}">{{$asc->name}} </a> ; &nbsp; &nbsp; &nbsp; &nbsp;{{$asc->region->manager->name}}
            
            </div></div>
            
                    @if(is_array($asc->show_map_service))
                    @foreach($asc->show_map_service as $key=>$error)
                    <p class="ml-1 mb-1">@lang('site::messages.user_show_map_errors.' .$error)</p>
                    @endforeach
                    @endif
        @endforeach
        </div>
        </div>
    </div>
@endsection
