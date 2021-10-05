@extends('layouts.app')
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
            <li class="breadcrumb-item active">@lang('site::admin.dashboard.ferroli-plus-reports')</li>
        </ol>
        
        @alert()@endalert
        
        
        
<h2 class="mb-0">Отчеты о монтаже Мастер+</h2>

                <table class="table-bordered bg-white table-sm table-report">
                    @foreach($ferroliManagers->get() as $manager)
                    
                    <tr><td colspan="14" class="trngrb">{{$manager->name}}</td></tr>
                    <tr>
                        <th>Регион</th>
                        <th colspan="6">Кол-во отчетов</th>
                        <th style="border-left: solid;" colspan="6">Начисление бонусов по отчетам</th>
                        
                        

                    </tr>
                    <tr>
                                    <td class="ttaby" rowspan="2">По всем регионам менеджера</td>
                                    <td class="ttaby">Неделя:</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td class="ttaby">Тек.кв.:</td>
                                    <td class="ttaby">Прошлый кв.:</td>
                                    
                                    <td style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td class="ttaby">Тек.кв.:</td>
                                    <td class="ttaby">Прошлый кв.:</td>
                                    
                    </tr>
                    <tr>
                                   <td class="ttaby">
                                        {{$dashboard->mountingsManagerToPeriod($manager,$periods['last_7'])->count()}}
                                   </td>       
                                   <td class="ttaby">
                                        {{$dashboard->mountingsManagerToPeriod($manager,$periods['corrent_month'])->count()}}
                                   </td>       
                                   <td class="ttaby">
                                        {{$dashboard->mountingsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'])->count()}}
                                   </td>
                                   <td class="ttaby">{{$dashboard->mountingsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'])->count()}}
                                   </td>       
                                   <td class="ttaby">{{$dashboard->mountingsManagerToPeriod($manager,$periods['current_quart'])->count()}}
                                   </td>       
                                   <td class="ttaby">{{$dashboard->mountingsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'])->count()}}
                                   </td>       

                                    
                                   <td style="border-left: solid;" class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['last_7'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['corrent_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['current_quart'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->mountingsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       

                                    
                                
                                    
                    </tr> 
                    @if($manager->notifiRegions()->exists())    
                        @foreach($manager->notifiRegions()->whereHas('addresses', function ($q){$q->where('active',1);})->get() as $key=>$region)
                       
                        <tr>
                            <td @if($key%2==1)class="ttabr"@endif>{{$region->name}}</td>
                             <td @if($key%2==1)class="ttabr"@endif>
                                        {{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_7'])->count()}}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['corrent_month'])->count()}}
                                        
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_month'],$periods['corrent_month'])->count()}} 
                                   </td>
                                   <td @if($key%2==1)class="ttabr"@endif>{{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['sub_last_month'],$periods['last_month'])->count()}}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>{{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['current_quart'])->count()}} 
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>{{$dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_quart'],$periods['current_quart'])->count()}}
                                   </td>       

                                    
                                   <td style="border-left: solid;" @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_7'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['corrent_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_month'],$periods['corrent_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['sub_last_month'],$periods['last_month'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['current_quart'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->mountingsRegionIdToPeriodBonusable($region->id,$periods['last_quart'],$periods['current_quart'])->whereHas('digiftBonus')->sum('bonus'))!!}
                                   </td>    
                                    
                                    
                                </tr>    
                        
                        @endforeach
                        @endif
                    @endforeach
                 </table>  
        




@endsection
