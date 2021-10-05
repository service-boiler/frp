@extends('layouts.app')
@section('title')@lang('site::messages.home')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container mt-1">
        @alert()@endalert
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <img id="user-logo" src="{{$user->logo}}" style="width:100px!important;height: 100px"
                                 class="rounded-circle">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ $user->name }}</h5>
                                <div class="mt-3">
                                    <form action="{{route('home.logo')}}" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="btn btn-ms btn-sm control-label" for="change-user-logo">
                                                @lang('site::messages.change') @lang('site::user.help.logo')
                                            </label>
                                            <input accept="image/jpeg" name="path" type="file"
                                                   class="d-none form-control-file" id="change-user-logo">
                                            <input type="hidden" name="storage" value="logo">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="border-light m-0">
                    <div class="card-body">
                        <div class="mb-2">
                            <span id="temp"></span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.created_at')
                                :</span>&nbsp;&nbsp;{{ $user->created_at->format('d.m.Y H:i') }}
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::region.region'):</span>&nbsp;
                            <span class="text-dark">{{ !empty($user->region) ? $user->region->name : 'Регион не задан!!'}}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.email'):</span>&nbsp;
                            <span class="text-dark">{{ $user->email }}</span>
                            @if(!$user->virified ?? $user->email)
                            <span class="text-danger">@lang('site::user.confirm_email_need')</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.phone_main'):</span>&nbsp;
                            <span class="text-dark">{{ $user->phone }}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::user.my_addresses'):</span>&nbsp;
                           @foreach($user->addressesActual as $address) <li><span class="text-dark">
                            {{$address->locality}}, {{$address->street}},  {{$address->building}},  {{$address->apartment}} 
                            @if($address->main)<i class="fa fa-check text-success" data-toggle="tooltip"
                                      data-placement="top"
                                      title="Основной адрес"></i>@endif
                            </span></li>@endforeach
                        </div>
                        <div class="mb-0">
                           <a class="btn btn-ms btn-sm mb-0 control-label" href="{{route('edit_profile_esb')}}">Редактировать профиль</a>
                        </div>
                    </div>
                </div>
               
                 
                    
              
              @if($user->esbProducts()->where('enabled',1)->exists())
                        @foreach($user->esbProducts->where('enabled',1) as $esbProduct)
                             <div class="card mb-4">
                                <div class="card-body">
                            <div class="row mb-2">
                                <div class="col-6">
                                   <a href="{{route('esb-user-products.manage',$esbProduct)}}"><span class="h5">{{$esbProduct->product->name}}</span></a>
                                   
                                </div>
                                <div class="col-6 h5">
                                  <span id="product_temp_870449"></span>&deg;C
                                  <span id="product_flame_870449" class="rng d-none"><i class="fa fa-fire"></i></span> 
                                  <span class="text-success"> / <i class="fa fa-cog"></i>
                                  <span id="product_prefferedTemp_870449"></span>&deg;C</span>
                                </div>
                                   
                                
                            </div>
                           </div>
                </div>
                        @endforeach
                    @endif
              
              
                <div class="card mb-4">
                    <div class="card-body">
                    
                        <div class="list-group list-group-flush">
                           
                            <a href="{{ route('retail-orders-esb.index') }}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa fa-shopping-cart"></i>
                                    @lang('site::user.esb_order.your_orders')
                                </span>
                                <span class="badge text-big badge-light">
                                   {{$user->esbRetailOrders()->whereIn('retail_orders.status_id',[1,2,3,4,5,6])->count()}}
                                </span>
                            </a>
                            
                            
                        </div>
                        <div class="list-group list-group-flush">
                           
                            <a href="{{ route('esb-requests.index') }}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa fa-shopping-cart"></i>
                                    @lang('site::user.esb_request.esb_requests')
                                </span>
                                <span class="badge text-big badge-light">
                                   {{$user->esbRequests()->whereIn('esb_user_requests.status_id',[1,2,3,4,5,6])->count()}}
                                </span>
                            </a>
                            
                        <a href="{{ route('esb-visits.index') }}"
                           class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <span>
                                <i class="fa fa-clock-o"></i>
                                @lang('site::user.esb_user_visit.index_client')
                            </span>
                        </a>
                            
                        </div>
                        <div class="list-group list-group-flush">
                            @permission('messages')
                            <a href="{{ route('messages.index') }}"
                               class="py-2 list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fa fa-@lang('site::message.icon')"></i>
                                    @lang('site::message.messages')
                                </span>
                                <span class="badge text-big @if($user->inbox()->exists()) badge-ferroli @else badge-light @endif">
                                    {{$user->inbox()->count()}}
                                </span>
                            </a>
                            @endpermission()
                            
                        </div>
                    </div>
                
                </div>
            </div>
            <div class="col">
                <div class="card mb-4">
                    <div class="card-body">
                        <a class="btn btn-ms w-100 mb-3" style="white-space: normal;" 
                            @if(!$user->esbServices()->exists())
                            href="javascript:void(0);" onclick="$(this).next().toggleClass('d-none');$(this).toggleClass('btn-secondary');$(this).toggleClass('btn-ms')"
                            @else
                            href="{{route('esb-requests.create',['multiple'=>'0'])}}"
                            @endif
                            >
                                Отправить заявку на обслуживание, ремонт или установку котла</a>
                            <div class="d-none row">
                                <div class="col-sm-6">
                                    <a class="btn btn-primary mb-3" style="white-space: normal;" href="{{route('service-centers').'?filter[region_id]='.$user->region_id .'#sc_list'}}">
                                        Я выберу один сервис с которым заключу договор на обслуживание. 
                                        </a>
                                </div>
                                <div class="col-sm-6">
                                <a class="btn btn-secondary mb-3" style="white-space: normal;" href="{{route('esb-requests.create',['multiple'=>'1'])}}">
                                    Я хочу отправить заявки в несколько сервисных центров. 
                                    </a>
                                </div>
                            </div>

                        
                        <a class="btn btn-green w-100" style="white-space: normal;" href="{{route('catalogs.index')}}">Заказть котел или дополнительное оборудование</a>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                    <h5 class="mt-1">Ваше оборудование:</h5>
                    @if($user->esbProducts()->where('enabled',1)->exists())
                        @foreach($user->esbProducts->where('enabled',1) as $esbProduct)
                            <div class="row mb-2">
                                <div class="col-8">
                                   <a href="{{route('esb-user-products.show',$esbProduct)}}"><span class="h5">{{$esbProduct->product->name}}</span>
                                   <i class="fa fa-external-link"></i> Данные о гарантии и история обслуживания</a>
                                </div>
                                @if($esbProduct->product && $esbProduct->product->type_id == 1)
                                <div class="col-4">
                                   @if(!$esbProduct->launches()->exists() && !$esbProduct->maintenances()->exists())
                                   <span class="text-danger h5"> Требуется пусконаладка!</span>
                                   @elseif($esbProduct->next_maintenance < \Carbon\Carbon::now()->addMonth())
                                  <span class="text-danger h5"> Требуется обслуживание!</span>
                                   @else
                                   Следующее ТО {{$esbProduct->next_maintenance ? $esbProduct->next_maintenance->format('d.m.Y') : ''}}
                                   @endif
                                </div>
                                @endif
                            </div>
                            <hr />
                        @endforeach
                    @endif
                    @if($user->esbProducts()->where('enabled',0)->count()>0)
                        <div class="row d-none mt-3">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        Архив Вашего оборудования
                                    </div>
                                </div>
                                @foreach($user->esbProducts()->where('enabled',0)->get() as $esbProduct)                               
                                <div class="row mb-2">
                                    <div class="col h5">
                                        {{$esbProduct->product->name}}
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <div class="row mt-4">
                        <div class="col">
                            <a href="{{route('esb-user-products.create')}}" class="btn btn-primary btn-sm">Добавить/зарегистрировать оборудование</a>
                            @if($user->esbProducts()->where('enabled',0)->count()>0)
                            <a href="javascript:void(0);" onclick="$(this).parent().parent().prev().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary">
                                                    <b>Показать архив Вашего оборудования</b>
                                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                    <h5 class="mt-1">Ваш сервисный центр:</h5>
                    
                    @if($user->esbServices()->exists())
                        <div class="row">
                            <div class="col"><a target="_blank" href="{{route('public_user_card',$user->esbServices()->first())}}" class="h5">
                            {{$user->esbServices()->wherePivot('enabled',1)->first()->name_for_site ? $user->esbServices()->wherePivot('enabled',1)->first()->name_for_site : $user->esbServices()->wherePivot('enabled',1)->first()->name}}
                            <span class="text-success"><i class="fa fa-external-link"></i></span></a>
                            </div>
                        </div>
                        @if($user->esbServices()->count()>1)
                        <div class="row d-none mt-3">
                            <div class="col">
                                <div class="row">
                                    <div class="col">
                                        Архив Ваших СЦ
                                    </div>
                                </div>
                                @foreach($user->esbServices()->wherePivot('enabled',0)->get() as $service)                               
                                <div class="row">
                                    <div class="col">
                                        <a class="text-muted h5" href="{{route('public_user_card',$service)}}">
                                            {{$service->name_for_site ? $service->name_for_site : $service->name}} <i class="fa fa-external-link"></i>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        <a href="{{route('service-centers').'?filter[region_id]='.$user->region_id .'#sc_list'}}" class="btn btn-green btn-sm mt-3">Сменить сервисный центр</a>
                        
                        @if($user->esbServices()->count()>1)
                            <a href="javascript:void(0);" onclick="$(this).prev().prev().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary mt-3">
                                                    <b>Показать архив Ваших СЦ</b>
                                            </a>
                        @endif
                    @else
            
                        <a href="{{route('service-centers').'?filter[region_id]='.$user->region_id .'#sc_list'}}" class="btn btn-green btn-sm">Выбрать сервисный центр</a>
                        
                     
                     @endif
                     
                    </div>
                        
                </div>
                
            </div>
        </div>
    </div>
@endsection




@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {
                
            
            function temperature(deviceId) {
            $.get("/api/mqtt/getstatusarc/"+deviceId,function(data){
                $('#product_temp_'+deviceId).html(data.temperature);
                $('#product_prefferedTemp_'+deviceId).html(data.prefferedTemp);
                
                if(data.flame==1) {
                    $('#product_flame_'+deviceId).removeClass('d-none');
                }else{
                $('#product_flame_'+deviceId).addClass('d-none');
                }
            });
            }
            
            temperature('870449');
            
            let timerId = setInterval(() => temperature('870449'), 5000);
            
            
                
        
        
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush


