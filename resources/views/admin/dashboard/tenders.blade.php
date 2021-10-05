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
            <li class="breadcrumb-item active">Тендеры</li>
        </ol>
        
        @alert()@endalert
        
        
        <div class="card mt-1 mb-1">
            <div class="card-body">
            <form id="head-form" method="POST" enctype="multipart/form-data" action="{{ route('admin.dashboards.tenders') }}">
                @csrf
               
                    
                    <div class="col-md-8">
                        <div class="form-row required">
                                
                            <button disabled form="head-form" type="submit" name="excel" class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                                <i class="fa fa-upload"></i> <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
                            </button>
                            <a class="btn btn-ms" href="{{ route('admin.tenders.index') }}">
                            <i class="fa fa-legal"></i> Тендеры
                        </a>
                        </div>
                    </div>
                </div>
                
            </form>
            
            </div>
        
        
            
                <table class="table-bordered bg-white table-sm table-report">
                    <tr>
                        <td class="trngrb">Менеджер</td>
                        <td class="trngrb">Согласованные<br />анкеты</td>
                        <td class="trngrb">Дистрибутор</td>
                        <td class="trngrb">Менеджер</td>
                        <td class="trngrb">Отгруженные<br />анкеты</td>
                        <td class="trngrb">План<br />/факт, %</td>
                        <td class="trngrb">Проигранные<br />анкеты</td>
                        <td class="trngrb">Актуальные<br />анкеты</td>
                        <td class="trngrb">Новые<br />анкеты <br />за @lang('site::messages.months.'.$periods['month'])</td>
                        <td class="trngrb">План отгрузки<br />на @lang('site::messages.months.'.$periods['month']), <br />евро БЕЗ НДС</td>
                        <td class="trngrb">Фактически<br />отгружено <br />за @lang('site::messages.months.'.$periods['month']),<br />евро БЕЗ НДС</td>
                        <td class="trngrb">План<br />/факт, %</td>
                        <td class="trngrb">План отгрузки<br /> на сл.месяц,  <br />евро БЕЗ НДС</td>

                    </tr>
                    @foreach($managers as $key=>$manager)
                    <tr>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->name}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders->whereIn('status_id',[4,5,6])->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders->whereIn('status_id',[4,5,6])->where('source_id',2)->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders->whereIn('status_id',[4,5,6])->where('source_id',1)->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders()->whereIn('status_id',[6])->count()}}
                            ({{$manager->tenders()->whereIn('status_id',[4,5,6])->whereHas('order')->count()}})
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            @if($manager->tenders()->whereIn('status_id',[4,5,6])->count()>0)
                            {{round(
                                $manager->tenders()->whereIn('status_id',[6])->count()
                                /
                                $manager->tenders->whereIn('status_id',[4,5,6])->count()
                                *100
                                ,0)
                            }} %
                            @else 
                            0 %
                            @endif
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders()->whereIn('status_id',[7,10])->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders()->whereIn('status_id',[1,2,3,4,5])->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {{$manager->tenders->where('created_at','>=',$periods['first_day_month'])->count()}}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {!!moneyFormatEuro($manager->tenders->where('planned_purchase_year',$periods['year'])->where('planned_purchase_month',$periods['month'])->sum('products_cost'))!!}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {!!moneyFormatEuro($manager->tenders->whereIn('status_id',[122])->where('planned_purchase_year',$periods['year'])->where('planned_purchase_month',$periods['month'])->sum('products_cost'))!!}
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            @if($manager->tenders->where('planned_purchase_year',$periods['year'])->where('planned_purchase_month',$periods['month'])->sum('products_cost')>0)
                            {{
                            $manager->tenders->whereIn('status_id',[122])->where('planned_purchase_year',$periods['year'])->where('planned_purchase_month',$periods['month'])->sum('products_cost') /
                            $manager->tenders->where('planned_purchase_year',$periods['year'])->where('planned_purchase_month',$periods['month'])->sum('products_cost')*100 }}
                            @endif
                        </td>
                        <td @if($key%2==1)class="ttabr"@endif>
                            {!!moneyFormatEuro($manager->tenders->where('planned_purchase_year',$periods['year'])
                                ->where('planned_purchase_month',$periods['month']+1)->sum('products_cost'))!!}
                        </td>
                        
                        
                    </tr> 
                    @endforeach
                </table>  


        
        
    </div>
@endsection
