@extends('layouts.app')
@section('title')  @lang($user->type_id==4 ? 'site::user.esb_user_visit.esb_user_visits' : 'site::user.esb_user_visit.esb_user_visits_srv') @endsection
@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active"><a href="{{route('esb-visits.index')}}">
            @lang($user->type_id==4 ? 'site::user.esb_user_visit.esb_user_visits' : 'site::user.esb_user_visit.esb_user_visits_srv')</li>
            </a>
                    <div class=" ml-md-auto">
                       <a href="{{ route('esb-user-products.index') }}"
                           class="py-2 justify-content-between align-items-left">
                            <span>
                                <i class="fa fa-barcode"></i>
                                @lang('site::user.esb_user_product.index')
                            </span>
                        </a>
            
                    </div>
        </ol>

        @alert()@endalert()
        @if(auth()->user()->type_id==4)
        <div class="card my-2 mb-3">
        <div class="card-body h5">
            Подтвердите запланированную дату выезда специалиста авторизованного сервисного центра.
            <br />Или укажите удобную для Вас дату.
        </div>
        </div>
        @endif
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbUserVisits])@endpagination
        {{$esbUserVisits->render()}}
        @foreach($esbUserVisits as $esbUserVisit)
       
            <div class="card my-2 mb-5" id="esbUserVisit-{{$esbUserVisit->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        @lang('site::user.esb_user_visit.visit') № {{$esbUserVisit->id}} {{$esbUserVisit->type ? $esbUserVisit->type->name : null}}
                        
                        &nbsp;<span class="badge text-normal badge-pill text-white" style="background-color: {{ $esbUserVisit->status->color }}">
                            <i class="fa fa-{{ $esbUserVisit->status }}"></i> {{ $esbUserVisit->status->name }}
                        </span>
                    </div>

                </div>
                <div class="row">
                @if(auth()->user()->type_id!=4)
                    <div class="col-sm-4">
                    
                        
                    
                    
                        <div class="row">
                            <div class="col-7">
                                <dl class="dl-horizontal mt-1 mb-1">
                                    <dt class="col-12">@lang('site::user.esb_user_visit.date_planned')</dt>
                                    <dd class="col-12">{!!$esbUserVisit->date_planned ? $esbUserVisit->date_planned->format('d.m.Y H:i') 
                                                            .' '. trans('site::date.week_sm.' .$esbUserVisit->date_planned->dayOfWeek): 'не указана'!!}
                                                        
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-5">
                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary mt-2" id="planned-date-button"
                                        onclick="document.getElementById('planned-date-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('planned-date-button').classList.toggle('d-none'); 
                                                 document.getElementById('status-form-{{$esbUserVisit->id}}').classList.toggle('d-none')">
                                        Изменить дату</a>
                            </div>
                            
                                        
                        </div>
                        <div class="d-none row" id="planned-date-form-{{$esbUserVisit->id}}">
                                        
                                        
                            <div class="input-group date datetimepickerfull col-sm-7 ml-2" id="datetimepicker_date"
                                                     data-target-input="nearest">
                                                    <input type="text" form="request-{{$esbUserVisit->id}}"
                                                           name="datetime"
                                                           id="planned_date"
                                                           placeholder="@lang('site::admin.webinar.datetime_ph')"
                                                           data-target="#datetimepicker_date"
                                                           data-toggle="datetimepicker"
                                                           class="datetimepicker-input form-control{{ $errors->has('datetime') ? ' is-invalid' : '' }}"
                                                           value="{{ old('datetime',$esbUserVisit->date_planned ? $esbUserVisit->date_planned->format('d.m.Y H:i') : null)}}">
                                                    <div class="input-group-append"
                                                         data-target="#datetimepicker_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                            </div>
                            
                            <button form="request-{{$esbUserVisit->id}}" type="submit" name="changedate" value="1"
                                 class="btn btn-sm btn-primary">
                                    Сохранить дату @if(auth()->user()->type_id==4)и отправить в СЦ @endif
                            </button>
                        </div>
                        
                        
                        
                        <div class="row">
                            <div class="col-7">
                                <dl class="dl-horizontal mt-0 mb-1">
                                    <dt class="col-12">@lang('site::user.esb_user_visit.cost_sm')</dt>
                                    <dd class="col-12">{!!moneyFormatRub($esbUserVisit->cost_planned)!!}
                                                        
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-5">
                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary mt-2" id="planned-date-button"
                                        onclick="document.getElementById('cost-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('cost-button').classList.toggle('d-none');">
                                        Изменить стоимость</a>
                            </div>
                            
                                        
                        </div>
                        <div class="d-none row" id="cost-form-{{$esbUserVisit->id}}">
                                        
                                        
                            <div class="input-group col-sm-7 ml-2">
                                                    <input type="text" form="request-{{$esbUserVisit->id}}"
                                                           name="cost_planned"
                                                           class="form-control"
                                                           value="{{ old('cost_planned',$esbUserVisit->cost_planned)}}">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-money"></i>
                                                        </div>
                                                    </div>
                            </div>
                            
                            <button form="request-{{$esbUserVisit->id}}" type="submit" name="changecost" value="1"
                                 class="btn btn-sm btn-primary">
                                    Сохранить
                            </button>
                        </div>
                        
                      
                    </div>
                    <div class="col-sm-5">
                        <div class="row">
                            <div class="col-7">
                                <dl class="dl-horizontal mt-1 mb-1">
                                    <dt class="col-12">@lang('site::user.esb_user_visit.type_id')</dt>
                                    <dd class="col-12">
                                        {{$esbUserVisit->type ? $esbUserVisit->type->name : 'не указан'}}
                            
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-5">
                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary mt-2" id="type-change-button"
                                        onclick="document.getElementById('type-change-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('type-change-button').classList.toggle('d-none'); ">
                                        Сменить тип</a>
                            </div>
                            
                                        
                        </div>
                        <div class="d-none row" id="type-change-form-{{$esbUserVisit->id}}">
                            <div class="input-group col-sm-7 ml-2">
                                                    
                                <select class="form-control{{  $errors->has('engineer_id') ? ' is-invalid' : '' }}" id="engineer_id"
                                    name="type_id" form="request-{{$esbUserVisit->id}}">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($types as $type)
                                    <option
                                            @if(old('type_id',$esbUserVisit->type_id) == $type->id)
                                            selected
                                            @endif
                                            value="{{ $type->id }}">{{ $type->name }}
                                    </option>
                                @endforeach
                                </select>
                                
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-wrench"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <button form="request-{{$esbUserVisit->id}}" type="submit" name="changetype" value="1"
                                 class="btn btn-sm btn-primary">
                                    Сохранить
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-7">
                                <dl class="dl-horizontal mt-0 mb-1">
                                    <dt class="col-12">@lang('site::user.esb_user_visit.engineer_id')</dt>
                                    <dd class="col-12">
                                {{$esbUserVisit->engineer_id ? $esbUserVisit->engineer->getPublicNameAttribute() : 'не указан'}}
                            
                                    </dd>
                                </dl>
                            </div>
                            <div class="col-5">
                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary mt-2" id="engineer-change-button"
                                        onclick="document.getElementById('engineer-change-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('engineer-change-button').classList.toggle('d-none'); ">
                                        Сменить инженера</a>
                            </div>
                            
                                        
                        </div>
                        <div class="d-none row" id="engineer-change-form-{{$esbUserVisit->id}}">
                            <div class="input-group col-sm-7 ml-2" id="engineer_id_row">
                                                    
                                <select class="form-control{{  $errors->has('engineer_id') ? ' is-invalid' : '' }}" id="engineer_id"
                                    name="engineer_id" form="request-{{$esbUserVisit->id}}">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($engineers as $engineer)
                                    <option
                                            @if(old('engineer_id',$esbUserVisit->engineer_id) == $engineer->id)
                                            selected
                                            @endif
                                            value="{{ $engineer->id }}">{{ $engineer->name }}
                                    </option>
                                @endforeach
                                </select>
                                
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <i class="fa fa-wrench"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <button form="request-{{$esbUserVisit->id}}" type="submit" name="changeengineer" value="1"
                                 class="btn btn-sm btn-primary">
                                    Сохранить
                            </button>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                           
                            
                            <dt class="col-12">@lang('site::user.esb_user_visit.product_sm')</dt>
                            <dd class="col-12">@if($esbUserVisit->esbUserProduct && $esbUserVisit->esbUserProduct->product)
                                                    <a href="{{route('esb-user-products.show',$esbUserVisit->esbUserProduct)}}">{{$esbUserVisit->esbUserProduct->product->name}}</a>
                                               @endif
                            </dd>
                            
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$esbUserVisit->created_at->format('d.m.Y')}}</dd>
                        </dl>
                    </div> 
                    <div class="col-4">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Контакты</dt>
                            <dd class="col-12">{{$esbUserVisit->phone ? $esbUserVisit->phone : $esbUserVisit->esbUser->phone}} <b>{{$esbUserVisit->esbUser->name_filtred}}</b></dd>
                        </dl>
                    </div>
                    <div class="col-8">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Адрес</dt>
                            <dd class="col-12">{{$esbUserVisit->esbUserProduct ? $esbUserVisit->esbUserProduct->address_filtred : null}}</dd>
                        </dl>
                    </div>
                @else
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_user_visit.date_planned')</dt>
                            <dd class="col-12">{!!$esbUserVisit->date_planned ? $esbUserVisit->date_planned->format('d.m.Y H:i') .' '. trans('site::date.week.' .$esbUserVisit->date_planned->dayOfWeek): 'не указана'!!}</dd>
                            <dd class="col-12">
                                <a href="javascript:void(0);" class="btn btn-sm btn-secondary" id="planned-date-button"
                                        onclick="document.getElementById('planned-date-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('planned-date-button').classList.toggle('d-none'); 
                                                 document.getElementById('status-form-{{$esbUserVisit->id}}').classList.toggle('d-none')">
                                        Изменить дату</a>
                            </dd>
                        </dl>
                        <div class="d-none row" id="planned-date-form-{{$esbUserVisit->id}}">
                                        
                                        
                            <div class="col-12 ml-2 mb-2">
                            <button form="request-{{$esbUserVisit->id}}" type="submit" name="changedate" value="1"
                                 class="btn btn-sm btn-primary">
                                    Сохранить дату @if(auth()->user()->type_id==4)и отправить в СЦ @endif
                            </button>
                            </div>
                            <div class="input-group date datetimepickerfull col-12 ml-2" id="datetimepicker_date"
                                                     data-target-input="nearest">
                                                    <input type="text" form="request-{{$esbUserVisit->id}}"
                                                           name="datetime"
                                                           id="planned_date"
                                                           placeholder="@lang('site::admin.webinar.datetime_ph')"
                                                           data-target="#datetimepicker_date"
                                                           data-toggle="datetimepicker"
                                                           class="datetimepicker-input form-control{{ $errors->has('datetime') ? ' is-invalid' : '' }}"
                                                           value="{{ old('datetime',$esbUserVisit->date_planned ? $esbUserVisit->date_planned->format('d.m.Y H:i') : null)}}">
                                                    <div class="input-group-append"
                                                         data-target="#datetimepicker_date"
                                                         data-toggle="datetimepicker">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_user_visit.service')</dt>
                            <dd class="col-12">
                            <a target="_blank" href="{{route('public-user-card',$esbUserVisit->service)}}">
                            {{$esbUserVisit->service->public_name}}</a>
                            </dd>
                           
                           
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            
                            <dt class="col-12">@lang('site::user.esb_user_visit.product_sm')</dt>
                            <dd class="col-12">@if($esbUserVisit->esbUserProduct && $esbUserVisit->esbUserProduct->product)
                                                    <a href="{{route('esb-user-products.show',$esbUserVisit->esbUserProduct)}}">{{$esbUserVisit->esbUserProduct->product->name}}</a>
                                               @endif
                            </dd>
                        </dl>
                    </div>   

                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                           <dt class="col-12">Стоимость ТО</dt>
                            <dd class="col-12">{!!moneyFormatRub($esbUserVisit->cost_planned)!!}</dd>
                            
                        </dl>
                    </div>                    
                @endif
                </div>
                
                
                     
             
                @if($esbUserVisit->statuses()->exists())
                <div class="card-header with-elements pl-2">
                
                    @if(in_array($esbUserVisit->status_id,['1','2','3','5']))
                        <div class="card-header-elements mr-md-auto">   
                     
                        </div>
                        @endif
                        <div class="card-header-elements ml-md-auto" id="status-form-{{$esbUserVisit->id}}">        
                        
                         Сменить статус:
                             @foreach($esbUserVisit->statuses()->get() as $status)
                             <button form="request-{{$esbUserVisit->id}}" type="submit" name="esbUserVisit[status_id]" value="{{$status->id}}" 
                             class="btn btn-sm text-normal text-white m-2" style="background-color: {{ $status->color }}">
                                {{ $status->button }}
                             </button>
                             @endforeach
                             
                        </div>
                    
                </div>
                @endif
                <form id="request-{{$esbUserVisit->id}}" action="{{route('esb_user_visits.status', $esbUserVisit)}}" method="POST">
                        @csrf
                        @method('PUT')
                </form>
                <div class="row">
                    <div class="col-12 ml-3" id="comments"> 
                        Комментарии:
                        <br />
                        {{$esbUserVisit->comments}}
                               <br /><a href="javascript:void(0);" id="comment-button"
                                        onclick="document.getElementById('comment-form-{{$esbUserVisit->id}}').classList.toggle('d-none'); 
                                                 document.getElementById('comments').classList.toggle('d-none')">
                                       Добавить изменить / комментарии и замечания по работе оборудования</a>             
                    </div>
                    <div class="col-11 ml-3 mt-2 d-none" id="comment-form-{{$esbUserVisit->id}}"> 
                        
                        <textarea form="request-{{$esbUserVisit->id}}" name="comments" class="form-control">{{$esbUserVisit->comments}}</textarea>
                        <button class="btn btn-sm btn-secondary m-2" type="submit" form="request-{{$esbUserVisit->id}}">Сохранить</button>
                        
                    </div>
                </div>        
                
                        @if($esbUserVisit->esbUserProduct && $esbUserVisit->esbUserProduct->product->esbMaintenanceProductGroup())
                  <div class="row">
                        <div class="col ml-3"><small>                  
                                                    Рекомендованная Ferroli цена технического обслуживания  разового:
                                                    
                                                         {!!moneyFormatRub($esbUserVisit->esbUserProduct->product->esbMaintenanceProductGroup()->cost_single * auth()->user()->region->maintenance_factor) !!},
                                                          годового обслуживания:
                                                         {!!moneyFormatRub($esbUserVisit->esbUserProduct->product->esbMaintenanceProductGroup()->cost_year * auth()->user()->region->maintenance_factor) !!}
                                                          (без учета транспортных расходов)</small>
                                                       
                     </div>
                     </div>
                     @endif
            </div>
        @endforeach
        
        {{$esbUserVisits->render()}}
    </div>
@endsection
