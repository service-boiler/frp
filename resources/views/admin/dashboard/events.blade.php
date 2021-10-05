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
            <li class="breadcrumb-item active">@lang('site::admin.dashboard.events')</li>
        </ol>
        
        @alert()@endalert
        
        
        
<h2 class="mb-0">Участники мероприятий</h2>

                <table class="table-bordered bg-white table-sm table-report">
                    @foreach($ferroliManagers->get() as $manager)
                    
                    <tr><td colspan="72" class="trngrb">{{$manager->name}}</td></tr>
                    <tr>
                        <th>Регион</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="18">Кол-во участников по всем мероприятиям<br />(монтаж, сервис, продавец)</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="18">Технический семинар</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="18">Ferroli Day</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="18">Частные</th>
                        
                        

                    </tr>
                    <tr>
                                    <td class="ttaby" rowspan="3">По всем регионам менеджера</td>
                                    <td style="border-left: solid;" class="ttaby" colspan="3">Неделя:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Тек.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Прош.кв.:</td>
                                    
                                    <td style="border-left: solid;" class="ttaby" colspan="3">Неделя:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Тек.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Прош.кв.:</td>
                                    
                                    
                                    <td style="border-left: solid;" class="ttaby" colspan="3">Неделя:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Тек.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Прош.кв.:</td>
                                    
                                    
                                    <td style="border-left: solid;" class="ttaby" colspan="3">Неделя:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Тек.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">Прош.кв.:</td>
                                    
                                    
                                    </tr>
                    <tr>
                                    <td style="border-left: solid;" class="ttaby" colspan="3">{{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'])->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'])->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'])->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'])->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'])->count()}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'])->count()}}
                                    
                                    </td>  
                                    
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">{{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,1)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,1)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],1)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],1)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'])->count(),null,1}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],1)->count()}}
                                    
                                    </td>   
                                    
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">{{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,3)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,3)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],3)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],3)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'])->count(),null,3}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],3)->count()}}
                                    
                                    </td>   
                                   
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">{{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,6)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,6)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],6)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],6)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'])->count(),null,6}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],6)->count()}}
                                    
                                    </td>   
                                   
                                        
                                   
                                    
                    </tr> 
                        
                         <tr>
                                    <td style="border-left: solid;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,null,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,null,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,null,16)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,null,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,null,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,null,16)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],null,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],null,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],null,16)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],null,14)->count()}}
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],null,15)->count()}}
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],null,16)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,null,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,null,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,null,16)->count()}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],null,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],null,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],null,16)->count()}}
                                    
                                    </td>  
                                    
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,1,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,1,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,1,16)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,1,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,1,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,1,16)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],1,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],1,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],1,16)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],1,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],1,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],1,16)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,1,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,1,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,1,16)->count()}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],1,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],1,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],1,16)->count()}}
                                    
                                    </td>   
                                    
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,3,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,3,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,3,16)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,3,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,3,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,3,16)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],3,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],3,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],3,16)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],3,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],3,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],3,16)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,3,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,3,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,3,16)->count(),null,3,16}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],3,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],3,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],3,16)->count()}}
                                    
                                    </td>   
                                   
                 <!----- ////----->                   
                                    <td style="border-left: solid;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,6,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,6,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_7'],null,6,16)->count()}}
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,6,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,6,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['corrent_month'],null,6,16)->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],6,14)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],6,15)->count()}},
                                        {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_month'],$periods['corrent_month'],6,16)->count()}}
                                    </td> 
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],6,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],6,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['sub_last_month'],$periods['last_month'],6,16)->count()}}
                                    
                                    </td>  
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,6,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,6,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['current_quart'],null,6,16)->count()}}
                                    
                                    </td>   
                                    <td style="border-left: solid #CCC;" class="ttaby" colspan="3">
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],6,14)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],6,15)->count()}},
                                    {{$dashboard->eventsParticipantsManagerToPeriod($manager,$periods['last_quart'],$periods['current_quart'],6,16)->count()}}
                                    
                                    </td>   
                                   
                                        
                                   
                                    
                    </tr> 
                        
                      
                        
                    @endforeach
                 </table>  








@endsection
