@extends('layouts.app')
@section('title') Продажи дистрибьюторов @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::distributor_sales.h1')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::storehouse.icon')"></i> @lang('site::distributor_sales.h1')
        </h1>
        <div class="row border p-3 mb-2">
       <button form="repository-form" type="submit" name="filter" class="d-none"> <!-- Для того, чтобы по Enter фильтр применялся, а не другие кнопки -->
                <i class="fa fa-search"></i>
            </button>
            <div class="col-sm-6">
                <button form="repository-form"
                        type="submit"
                        name="excel"
                        class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                    <i class="fa fa-upload"></i>
                    <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
                </button>
            </div> 
            
            <div class="col-sm-6 text-sm-right">
                <a target="_blank" href="/up/man/distributor-sales.pdf" class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-green">
                    <i class="fa fa-question-circle-o"></i> @lang('site::distributor_sales.help_pdf')
                </a>
            </div> 
        </div>
        
     
        
        @alert()@endalert()
        
        @filter(['repository' => $repository])@endfilter
     
        {{$distributorSales->render()}}
        
        
        
        
        
              @for($i=0;$i<12;$i++)
      <h5> @lang('site::messages.months.' .date("m", strtotime("-$i month"))) </h5>
       
           
           <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>@lang('site::product.sku')</th>
                            <th>@lang('site::product.name')</th>
                            <th class="text-right">@lang('site::messages.sold')</th>
                        </tr>
                        </thead>
                        <tbody>
           
    @foreach($users as $user)      
        @if(($user->distributorSalesMonth(date("m", strtotime("-$i month")),$distributorSales))->count()>0)
        <tr>
            <td colspan="3"  class="pl-4" style="font-weight: 600; padding-top: 20px;">{{$user->name}}</td> 
        </tr>
            @foreach($user->distributorSalesMonth(date("m", strtotime("-$i month")),$distributorSales) as $distributor_sale)
                <tr>
                    <td class="pl-5" >{{$distributor_sale['sku']}}</td>
                    <td>{{$distributor_sale['product_name']}}</td>
                    <td class="text-right">{{number_format($distributor_sale['quantity'], 0, '.', ' ')}}</td>
                </tr>
            @endforeach
        @endif
    @endforeach
                            <tr>
                                <td colspan='3' class="pt-3"><b>Продажи по неделям</b></td>
                                
                            </tr>
         
           @foreach($allweeks->where('month',date("m", strtotime("-$i month")))->where('year',date("Y", strtotime("-$i year")))->all() as $week)
                            
                            <tr>
                                <td colspan='3' class="pt-3"> с {{$week->date_from}} по {{$week->date_to}}</td>
                                
                            </tr>
                        @foreach($users as $user)
                        @if($user->distributorSalesWeek($week->id,$distributorSales)->count() > 0)
                          <td colspan="3"  class="pl-4" style="font-weight: 600; padding-top: 20px;">{{$user->name}}</td> </tr>
                            @foreach($user->distributorSalesWeek($week->id,$distributorSales) as $distributor_sale)
         
                            <tr>
                                <td class="pl-5" >{{$distributor_sale['sku']}}</td>
                                <td>{{$distributor_sale['product_name']}}</td>
                                <td class="text-right">{{number_format($distributor_sale['quantity'], 0, '.', ' ')}}</td>
                            </tr>
                             @endforeach
                         @endif
                        @endforeach
                            
           @endforeach
           </tbody>
            </table>
 @endfor  
        
        
        
      <h5> @lang('site::messages.mounts.' .date("m")) </h5>
       Всего единиц за месяц:
      {{$distributorSales->where('date_sale', '>=', date("Y-m-d", strtotime(date("Y-m" ."-01"))))->sum('quantity')}}
           @for($i=0;$i<365;$i++)
          @if($distributorSales->where('date_sale', date("Y-m-d", strtotime("-$i day")))->sum('quantity') != 0)
            <div class="card my-2">
                   <div class="card-header with-elements">
                    
                    <div class="card-header-elements">
                      <h6>{{date("Y-m-d", strtotime("-$i day"))}}</h6> &nbsp;
                       Всего единиц за день:
                      {{$distributorSales->where('date_sale', date("Y-m-d", strtotime("-$i day")))->sum('quantity')}}
                    
                    <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th class="text-muted" width="100px">@lang('site::product.sku')</th>
                            <th class="text-muted" width="250px">@lang('site::product.name')</th>
                            <th class="text-right text-muted" width="50px">@lang('site::messages.sold')</th>
                        </tr>
                        </thead>
                        <tbody>
                        
                        @foreach($distributorSales->where('date_sale', date("Y-m-d", strtotime("-$i day")))->groupBy('user_id') as $distributor)
                            <tr>
                                <td colspan="3" style="font-weight: 600; padding-top: 20px;">{{$distributor[0]->user->name}}</td> </tr>
                            @foreach($distributor as $distributor_sale)
                            <tr>
                                <td width="100px">{{$distributor_sale->product->sku}}</td>
                                <td width="250px">{{$distributor_sale->product->name}}</td>
                                <td class="text-right" width="50px">{{number_format($distributor_sale->quantity, 0, '.', ' ')}}</td>
                            </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>        
                       
                    </div>
                </div>
               
            </div>
             @endif
            @if(strtotime(date("Y-m", strtotime("-$i day"))) == strtotime(date("Y-m-d", strtotime("-$i day"))))
             <h5> @lang('site::messages.mounts.' .date("m",strtotime("-$i day -1 month")))
             
             </h5>
             Всего единиц:
             {{$distributorSales->where('date_sale', '<' , date("Y-m-d", strtotime(date("Y-m-d", strtotime("-$i day  -1 day")))))->where('date_sale', '>=' , date("Y-m-d", strtotime(date("Y-m", strtotime("-$i day  -1 month")))))->sum('quantity')}}
           
            @endif
            @endfor
    </div>
@endsection
