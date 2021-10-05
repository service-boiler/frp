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
            <li class="breadcrumb-item active">@lang('site::admin.dashboard.ferroli-plus')</li>
        </ol>
        
        @alert()@endalert
        
        
        
<h2 class="mb-0">Регистрации в прогамме мотивации</h2>

                <table class="table-bordered bg-white table-sm table-report">
                    @foreach($ferroliManagers->get() as $manager)
                    
                    <tr><td colspan="36" class="trngrb">{{$manager->name}}</td></tr>
                    <tr>
                        <th>Регион</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="7">Кол-во монтажникови продавцов</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="14">Монтажники (зарегистрировано / <span class="text-success">с отчетами</span>)</th>
                        <th style="border-left: solid; border-bottom: solid;" colspan="14">Продавцы (зарегистрировано / <span class="text-success">с отчетами</span>)</th>
                        
                        

                    </tr>
                    <tr>
                                    <td class="ttaby" rowspan="2">По всем регионам менеджера</td>
                                    <td style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">Тек.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">Прош.кв.:</td>
                                    <td style="border-left: solid #CCC;" class="ttaby">Все:</td>
                                    
                                    <td colspan="2" style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Тек.кв.:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Прош.кв.:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Все:</td>
                                    
                                    <td colspan="2" style="border-left: solid;" class="ttaby">Неделя:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['corrent_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['last_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">@lang('site::messages.months.' .date("m", strtotime($periods['sub_last_month']))):</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Тек.кв.:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Прош.кв.:</td>
                                    <td colspan="2" style="border-left: solid #CCC;" class="ttaby">Все:</td>
                                    </tr>
                    <tr>
                                    <td style="border-left: solid;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>                 
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>                 
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>                
                                    <td style="border-left: solid #CCC;" class="ttaby">{{$users->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->count()}}
                                         
                                    </td>
                                    
                                    
                                    <td style="border-left: solid;" class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>   
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>     

                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td> 
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>   

                                     
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td> 
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td> 

                                     
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>

                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td> 
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td> 

                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>              
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>

                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$montage_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->count()}}
                                         
                                    </td>              
                                    <td class="ttaby text-success">{{$montage_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->count()}}
                                         
                                    </td>
                                    
                                    
                                    
                                    
                                    
                                    
                                    <td style="border-left: solid;" style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>  
                                    <td class="ttaby text-success">{{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>  

                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>               
                                    <td class="ttaby text-success">{{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>    

                                     
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>              
                                    <td class="ttaby text-success">{{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>     
                                     
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>               
                                    <td class="ttaby text-success">{{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>                
                                    <td class="ttaby text-success">{{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                       </td>             
                                    <td class="ttaby text-success">
                                         {{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>
                                    
                                    <td style="border-left: solid #CCC;"  class="ttaby">{{$sale_fl->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->count()}}
                                       </td>             
                                    <td class="ttaby text-success">
                                         {{$sale_fl_plus->whereIn('region_id',$manager->notifiRegions()->pluck('regions.id'))
                                                        ->count()}}
                                         
                                    </td>
                                   
                                        
                                   
                                    
                    </tr> 
                        
                        @foreach($manager->notifiRegions()->whereHas('addresses', function ($q){$q->where('active',1);})->get() as $key=>$region)
                       
                        <tr>
                                    <td @if($key%2==1)class="ttabr"@endif>{{$region->name}}</td>
                                    
                                    <td style="border-left: solid;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>                 
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>                 
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>                 
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>               
                                    <td style="border-left: solid #CCC;" class="@if($key%2==1)ttabr @endif">{{$users->whereIn('region_id',$region->id)
                                                       ->count()}}
                                         
                                    </td>
                                    
                                    
                                    <td style="border-left: solid;" class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>   
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>     

                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td> 
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>   

                                     
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td> 
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td> 

                                     
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>

                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td> 
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td> 

                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>              
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>
                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$montage_fl->whereIn('region_id',$region->id)
                                                        ->count()}}
                                         
                                    </td>              
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$montage_fl_plus->whereIn('region_id',$region->id)
                                                        ->count()}}
                                         
                                    </td>
                                    
                                    
                                    <td style="border-left: solid;" style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>  
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_7'])->count()}}
                                    </td>  

                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>               
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['corrent_month'])->count()}}
                                     </td>    

                                     
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>              
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_month'])->where('created_at','<=',$periods['corrent_month'])->count()}}
                                     </td>     
                                     
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>               
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['sub_last_month'])->where('created_at','<=',$periods['last_month'])->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>                
                                    <td class="@if($key%2==1)ttabr @endif text-success">{{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['current_quart'])->count()}}
                                    </td>  
                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                       </td>             
                                    <td class="@if($key%2==1)ttabr @endif text-success">
                                         {{$sale_fl_plus->whereIn('region_id',$region->id)
                                                        ->where('created_at','>=',$periods['last_quart'])->where('created_at','<=',$periods['current_quart'])->count()}}
                                         
                                    </td>
                                    
                                    <td style="border-left: solid #CCC;"  class="@if($key%2==1)ttabr @endif">{{$sale_fl->whereIn('region_id',$region->id)
                                                        ->count()}}
                                       </td>             
                                    <td class="@if($key%2==1)ttabr @endif text-success">
                                         {{$sale_fl_plus->whereIn('region_id',$region->id)
                                                       ->count()}}
                                         
                                    </td>
                                    
                                    
                                    
                                    
                                </tr>    
                        
                        @endforeach
                        
                    @endforeach
                 </table>  








@endsection
