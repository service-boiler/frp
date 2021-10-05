@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::distributor_sales.h1')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::storehouse.icon')"></i> @lang('site::distributor_sales.h1')
        </h1>
        @alert()@endalert()
        <div class="row border p-3 mb-2">
        
            <div class="col-sm-1">
                <a href="{{ route('home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                </a>
            </div> 
            
            <form enctype="multipart/form-data"
                                          action="{{route('distributor_sales.excel.store', $user)}}" method="post" id="upload">
                                        @csrf
                                                                        
       
            <div class="form-group required" id="form-group-week_id">
                        
                        <div class="input-group">
                            <select class="form-control{{  $errors->has('week_id') ? ' is-invalid' : '' }}"
                                    name="week_id"
                                    id="week_id"
                                    required >
                                
                                
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($weeks as $week)
                                    <option @if(old('week_id')) selected @endif
                                    
                                            value="{{ $week->id }}">
                                        {{ $week->year }}  @lang('site::date.month.'.($week->month)) c {{ date('d', strtotime($week->date_from)) }} по {{ date('d', strtotime($week->date_to)) }} 
                                    </option>
                                @endforeach
                                
                            </select>
                            
                        </div>
                        <label class="control-label" for="week_id">@lang('site::distributor_sales.week_select')</label>
                        <span class="invalid-feedback">{{ $errors->first('test.week_id') }}</span>
                    </div>  
                    
             </form> 
                    
            <div class="col-sm-3 ml-2 required form-group">
                <input type="file"  form="upload"
                       name="path"
                       accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                       class="form-control-file{{  $errors->has('path') ? ' is-invalid' : '' }} d-sm-inline-block  mb-3"
                       id="path">
                
                <span class="invalid-feedback">{!! $errors->first('path') !!}</span>
                <label class="control-label" for="path">@lang('site::distributor_sales.file_select')</label>
           
            </div>  
            <div class="col-sm-3 mr-5">
                 <button type="submit" class="btn btn-ms  d-sm-inline-block mt-1" form="upload">
                        <i class="fa fa-download"></i>
                        <span>@lang('site::distributor_sales.load_from_excel')</span>
                </button>
            </div>  
            <div class="col-sm-2 text-sm-right text-sm-right">
                <a target="_blank" href="/up/man/distributor-sales.pdf" class="btn btn-primary mt-1 text-sm-right"><i class="fa fa-question-circle-o"></i> @lang('site::distributor_sales.help_pdf')</a>
            </div>                               
         <span id="usersHelp" class="d-block form-text text-success"> @lang('site::distributor_sales.upload_help')</span>  
         
         
        </div>
        <form enctype="multipart/form-data"
                                          action="{{route('distributor_sales.url_update', $user)}}" method="post" id="save_url">
                                        @csrf
        <div class="row border p-1 mb-1">
            <div class="col">
            <div class="row p-0 m-0">
            <div class="col-12 mb-0">
            <label class="control-label"
                       for="url">@lang('site::distributor_sales.upload_url')</label>
                       </div>
                       </div>
            <div class="row p-0 m-0">           
            <div class="col-5 mb-1">
                
                <input type="text"
                       name="url"
                       id="url"
                       class="mt-0 form-control{{ $errors->has('url') ? ' is-invalid' : '' }}"
                       placeholder="@lang('site::distributor_sales.url_placeholder')"
                       value="{{ old('url',Auth()->user()->distributorSaleUrl ? Auth()->user()->distributorSaleUrl->url: '') }}">
                <span class="invalid-feedback">
                            <strong>{!! $errors->first('url') !!}</strong>
                        </span>
            </div>
            <div class="col-4 mb-1 pt-0">
                <div class="custom-control custom-checkbox d-inline-block">
                                <input type="checkbox"
                                       @if(old('enabled_url', Auth()->user()->distributorSaleUrl ? Auth()->user()->distributorSaleUrl->enabled : null)) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('enabled') ? ' is-invalid' : '' }}"
                                       id="enabled"
                                       name="enabled">
                                <label class="custom-control-label"
                                       for="enabled">@lang('site::distributor_sales.enabled_url')</label>
                                <span class="invalid-feedback">{{ $errors->first('enabled') }}</span>
                            </div>
                            
                <button type="submit" class="btn btn-green  d-inline-block" form="save_url">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                </button>
            </div>
            </form>
            <div class="col-3 mb-1">
            
                <fieldset class="d-inline-block scheduler-border" @if(!Auth()->user()->distributorSaleUrl) disabled @endif>
                                    <form id="distributors-sales-url-form"
                                          enctype="multipart/form-data"
                                          method="post"
                                          action="{{route('distributor_sales.url.store', $user)}}">
                                        @csrf
                                        @if(Auth()->user()->distributorSaleUrl)<input type="hidden" name="url" value="{{Auth()->user()->distributorSaleUrl->url}}">@endif
                                        <button type="submit" class="btn btn-ms">
                                            <i class="fa fa-download"></i>
                                            <span>@lang('site::distributor_sales.load_from_url')</span>
                                        </button>
                                    </form>
                                </fieldset>
            </div>
            </div>
            </div>
        </div>
         
     
        
        
        
        @filter(['repository' => $repository])@endfilter
     
        {{$distributorSales->render()}}
      
  @for($i=0;$i<12;$i++)
      <h5> @lang('site::messages.months.' .date("m", strtotime("-$i month"))) {{$allweeks->where('month',date("m", strtotime("-$i month")))->first()->year}}</h5>
       
           
           <table class="table table-sm table-hover">
                        <thead>
                        <tr>
                            <th>@lang('site::product.sku')</th>
                            <th>@lang('site::product.name')</th>
                            <th class="text-right">@lang('site::messages.sold')</th>
                        </tr>
                        </thead>
                        <tbody>
           
          
         @foreach($user->distributorSalesMonth(date("m", strtotime("-$i month")),$distributorSales) as $distributor_sale)
         
         <tr>
                                <td>{{$distributor_sale['sku']}}</td>
                                <td>{{$distributor_sale['product_name']}}</td>
                                <td class="text-right">{{number_format($distributor_sale['quantity'], 0, '.', ' ')}}</td>
                            </tr>
         @endforeach
                            <tr>
                                <td colspan='3' class="pt-3"><b>Продажи по неделям</b></td>
                                
                            </tr>
         
           @foreach($allweeks->where('month',date("m", strtotime("-$i month")))->where('year',$allweeks->where('month',date("m", strtotime("-$i month")))->first()->year)->all() as $week)
                            
                            <tr>
                                <td colspan='3' class="pt-3">с {{$week->date_from}} по {{$week->date_to}}</td>
                                
                            </tr>
                            @foreach($user->distributorSalesWeek($week->id,$distributorSales) as $distributor_sale)
         
                            <tr>
                                <td>{{$distributor_sale['sku']}}</td>
                                <td>{{$distributor_sale['product_name']}}</td>
                                <td class="text-right">{{number_format($distributor_sale['quantity'], 0, '.', ' ')}}</td>
                            </tr>
                             @endforeach
                            
           @endforeach
           </tbody>
            </table>
 @endfor          
           
          
    </div>
@endsection
