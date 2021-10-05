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
        
        
        
<h2 class="mb-0">Бонусы Дигифт</h2>

                <table class="table-bordered bg-white table-sm table-report">
                
                <tr>
                        <th></th>
                        <th colspan="6">Начисление бонусов за монтажи</th>
                        <th style="border-left: solid;" colspan="6">Начисление бонусов за продажи</th>
                        <th style="border-left: solid;" colspan="6">Выведено денег из Дигифт</th>
                        
                        

                    </tr>
                    <tr>
                                    <td class="ttaby" rowspan="2">По всем</td>
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
                                    
                                    <td style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td class="ttaby">Тек.кв.:</td>
                                    <td class="ttaby">Прошлый кв.:</td>
                                    
                    </tr>
                     <tr>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_7'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['corrent_month'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_month'],$periods['corrent_month'],'mountings')->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['sub_last_month'],$periods['last_month'],'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['current_quart'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_quart'],$periods['current_quart'],'mountings')->sum('operationValue'))!!}
                                   </td> 
                                   
                                   
                                   <td style="border-left: solid;" class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_7'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['corrent_month'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_month'],$periods['corrent_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['sub_last_month'],$periods['last_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['current_quart'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonuses($periods['last_quart'],$periods['current_quart'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>     
                                   
                                   
                                   <td style="border-left: solid;" class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['last_7'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['last_month'],$periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['sub_last_month'],$periods['last_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['current_quart'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesPeriod($periods['last_quart'],$periods['current_quart'])->sum('operationValue'))!!}
                                   </td>       

                                              

                                    
                                
                                    
                    </tr> 
                    <tr><td>&nbsp;</td>
                    </tr>
                    
                    
                
                
                    @foreach($ferroliManagers->get() as $manager)
                    
                    <tr><td colspan="19" class="trngrb">{{$manager->name}}</td></tr>
                    <tr>
                        <th>Регион</th>
                        <th colspan="6">Начисление бонусов за монтажи</th>
                        <th style="border-left: solid;" colspan="6">Начисление бонусов за продажи</th>
                        <th style="border-left: solid;" colspan="6">Выведено денег из Дигифт</th>
                        
                        

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
                                    
                                    <td style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td class="ttaby">Тек.кв.:</td>
                                    <td class="ttaby">Прошлый кв.:</td>
                                    
                    </tr>
                    <tr>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_7'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['corrent_month'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_month'],$periods['corrent_month'],'mountings')->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['sub_last_month'],$periods['last_month'],'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['current_quart'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_quart'],$periods['current_quart'],'mountings')->sum('operationValue'))!!}
                                   </td> 
                                   
                                   
                                   <td style="border-left: solid;" class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_7'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['corrent_month'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_month'],$periods['corrent_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['sub_last_month'],$periods['last_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['current_quart'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftBonusesManager($manager,$periods['last_quart'],$periods['current_quart'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>     
                                   
                                   
                                   <td style="border-left: solid;" class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['last_7'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['last_month'],$periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['sub_last_month'],$periods['last_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['current_quart'])->sum('operationValue'))!!}
                                   </td>       
                                   <td class="ttaby">
                                        {!!moneyFormatRub($dashboard->digiftExpensesManager($manager,$periods['last_quart'],$periods['current_quart'])->sum('operationValue'))!!}
                                   </td>       

                                              

                                    
                                
                                    
                    </tr> 
                    @if($manager->notifiRegions()->exists())    
                        @foreach($manager->notifiRegions()->whereHas('addresses', function ($q){$q->where('active',1);})->get() as $key=>$region)
                       
                        <tr>
                            <td @if($key%2==1)class="ttabr"@endif>{{$region->name}}</td>
                             
                                    
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_7'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['corrent_month'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_month'],$periods['corrent_month'],'mountings')->sum('operationValue'))!!}
                                   </td>
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['sub_last_month'],$periods['last_month'],'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['current_quart'],null,'mountings')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_quart'],$periods['current_quart'],'mountings')->sum('operationValue'))!!}
                                   </td> 
                                   
                                   
                                   <td style="border-left: solid;" @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_7'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['corrent_month'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_month'],$periods['corrent_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['sub_last_month'],$periods['last_month'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['current_quart'],null,'retailSaleReports')->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftBonusesRegionId($region->id,$periods['last_quart'],$periods['current_quart'],'retailSaleReports')->sum('operationValue'))!!}
                                   </td>     
                                   
                                   
                                   <td style="border-left: solid;" @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['last_7'])->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['last_month'],$periods['corrent_month'])->sum('operationValue'))!!}
                                   </td>
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['sub_last_month'],$periods['last_month'])->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['current_quart'])->sum('operationValue'))!!}
                                   </td>       
                                   <td @if($key%2==1)class="ttabr"@endif>
                                        {!!moneyFormatRub($dashboard->digiftExpensesRegionId($region->id,$periods['last_quart'],$periods['current_quart'])->sum('operationValue'))!!}
                                   </td>   
                                </tr>    
                        
                        @endforeach
                        @endif
                    @endforeach
                 </table>  








@endsection
