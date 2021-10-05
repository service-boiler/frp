@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-user-products.index') }}">@lang('site::user.esb_user_product.index')</a>
            </li>
            <li class="breadcrumb-item active">{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</li>
        </ol>

        @alert()@endalert()
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row justify-content-center mb-5">
            <div class="col">
                    <div class="card mb-2">
                        <div class="card-body">
                        @if($esbUserProduct->product && $esbUserProduct->product->warranty=='1' && $esbUserProduct->product->equipment_id)
                           
                                <h4 class="mt-0 mb-2">Электронный гарантийный талон</h4>
                                @if($esbUserProduct->next_maintenance < \Carbon\Carbon::now()->addMonth() || !$esbUserProduct->launch())
                                <span class="text-success">
                                    Гарантия на котлы действует только при проведенных пусконаладочных работах и ежегодном техническом обслуживании котла!
                                    <br />Обратитесь в авторизованный сервисный центр Ferroli!
                                </span>
                                @endif
                           
                        @endif
                            <div class="form-row mb-3">
                                <div class="col-sm-9">
                                   <h4 id="product" data-device-id="{{$esbUserProduct->device_id}}">{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</h4>
                                   <dl class="row mb-0"> 
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->serial ? $esbUserProduct->serial : 'не указан'}}</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_sale')</dt>
                                            <dd class="col-sm-9">{!!$esbUserProduct->date_sale ? $esbUserProduct->date_sale->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user.name_owner')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->esbUser ? $esbUserProduct->esbUser->name_filtred: ''}}</dd>
                                         <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->address ? $esbUserProduct->address->full : ''}}</dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.product_second_data')</dt>
                                        <dd class="col-sm-9">{!!$esbUserProduct->product_no_cat!!}</dd>
                                        @if($esbUserProduct->device_id)
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.current_temp')</dt>
                                        <dd class="col-sm-9 h3"><span  id="product_temp_{{$esbUserProduct->device_id}}"></span>&deg;C</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.preffered_temp')</dt>
                                            <dd class="col-sm-9 h3"><span id="product_prefferedTemp_{{$esbUserProduct->device_id}}"></span>&deg;C</dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.flame')</dt>
                                        <dd class="col-sm-9 h3 rng d-none" id="product_flame_{{$esbUserProduct->device_id}}"><i class="fa fa-fire"></i> горелка включена</dd>
                                        <dd class="col-sm-9 h3" id="product_flame_off_{{$esbUserProduct->device_id}}"><i class="fa fa-fire"></i> в ожидании</dd>
                                        @endif
                                    </dl>
                                @if($esbUserProduct->product && $esbUserProduct->product->type_id == 1)
                                            
                                            <hr>
                                            
                                            @if($esbUserProduct->warranty['type'] == 'mainextended')
                                            <h4>Действует стандартная (до {{$esbUserProduct->warranty_main_before}})
                                            <br />и дополнительная гарантия (до {{$esbUserProduct->warranty_extended_before}})</h4>
                                             
                                            @elseif($esbUserProduct->warranty['type'] == 'extended')
                                            <h4>Действует дополнительная гарантия до {{$esbUserProduct->warranty_extended_before}}</h4>
                                             <div class="d-none b-block">
                                             @lang('site::user.esb_user_product.warranty_year_text.'.$esbUserProduct->up_time_year)
                                             </div>
                                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).next().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary d-none  b-block">
                                                    <b>Скрыть</b>
                                            </a>
                                            <a href="javascript:void(0);" onclick="$(this).prev().prev().toggleClass('d-none');$(this).prev().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary">
                                                    <b>Показать условия гарантии</b>
                                            </a>
                                            @elseif($esbUserProduct->warranty['type'] == 'standart')
                                            <h4>Действует стандартная гарантия до {{$esbUserProduct->warranty_main_before}}</h4>
                                             @lang('site::user.esb_user_product.warranty_year_text.'.$esbUserProduct->up_time_year)
                                            @elseif($esbUserProduct->product->warranty)
                                            <h4>Гарантия не действует</h4>
                                            {{$esbUserProduct->warranty['error']}}
                                            @else
                                            <h4>Нет данных о гарантии</h4>
                                            @endif
                                           
                                            <hr>
                                   <h5>Данные о пусконаладочных работах</h5>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_launch')</dt>
                                            <dd class="col-sm-9">{!!$launch ? $launch->date_launch->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.asc')</dt>
                                            <dd class="col-sm-9">
                                                {{$launch ? $launch->service->name : ''}}</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.maint_comment')</dt>
                                            <dd class="col-sm-9">{{$launch ? $launch->comments : ''}}</dd>
                                       
                                       </dl>
                                         <hr />
                                   <h5>Данные о сервисном обслуживании</h5>
                                   @if($esbUserProduct->next_maintenance < \Carbon\Carbon::now()->addMonth())
                                   <span class="text-danger h5">
                                   @else
                                   <span class="text-success h6">
                                   @endif
                                        @lang('site::user.esb_user_product.next_maintenance') :
                                        {{$esbUserProduct->next_maintenance ? $esbUserProduct->next_maintenance->format('d.m.Y') : 'Нет данных'}}
                                   </span>
                                   
                                   
                                   
                                   @if($esbUserProduct->maintenances()->exists())
                                        @foreach($esbUserProduct->maintenances as $maintenance)
                                           <dl class="row mb-0">
                                                <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_maintenance')</dt>
                                                <dd class="col-sm-9">{{$maintenance->date}}</dd>
                                                <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.asc')</dt>
                                                <dd class="col-sm-9"><a target="_blank" href="{{route('public_user_card',$maintenance->service)}}">
                                                    {{$maintenance->service->name}}</a></dd>
                                                <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.maint_comment')</dt>
                                                <dd class="col-sm-9">{{$maintenance->comments}}</dd>
                                            </dl>
                                            <hr>
                                        @endforeach
                                    @else
                                        <dl class="row mb-0">
                                                <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_maintenance')</dt>
                                                <dd class="col-sm-9">Нет данных</dd>
                                            </dl>
                                            <hr>
                                    @endif
                                @endif
                                </div>
                                <div class="col-sm-3">
                                    @if($esbUserProduct->product && $esbUserProduct->product->images()->exists())
                                        <img style="height:150px" src="{{$esbUserProduct->product->images()->first()->src()}}">
                                    @endif
                                </div>
                                
                            </div>
                            <div class="form-row mb-3">
                            <a class="btn btn-sm btn-secondary" href="{{route('esb-user-products.edit',$esbUserProduct)}}">Изменить данные оборудования</a>
                            @if(auth()->user()->type_id!=4)
                            <a class="btn btn-sm btn-primary ml-2" href="{{route('esb-product-maintenances.create',['esbUserProduct'=>$esbUserProduct])}}">Создать акт сервисного обслуживания</a>
                            <a class="btn btn-sm btn-green ml-2" href="{{route('esb-product-launches.create',['esbUserProduct'=>$esbUserProduct])}}">Создать акт пусконаладочных работ</a>
                            @endif
                            <button
                                class="d-block d-sm-inline-block btn btn-sm btn-danger btn-row-delete ml-5"
                                        data-form="#delete-form"
                                        data-btn-delete="@lang('site::messages.delete')"
                                        data-btn-cancel="@lang('site::messages.cancel')"
                                        data-label="@lang('site::messages.delete_confirm')"
                                        data-message="@lang('site::messages.delete_sure') @lang('site::user.esb_user_product.esb_product')? "
                                        data-toggle="modal" data-target="#form-modal"
                                        title="@lang('site::messages.delete')">
                                    <i class="fa fa-close"></i>
                                    @lang('site::messages.delete')
                                </button>
                                <form id="delete-form"
                                      method="POST"
                                      action="{{ route('esb-user-products.destroy',$esbUserProduct) }}">
                                    @csrf
                                    @method('DELETE')

                                </form>
                            
                            </div>
                            
                         
                        </div>
                    </div>
                @if(!auth()->user()->hasRole(config('site.supervisor_roles'),[]))
                    <div class="card mb-2">
                        <div class="card-body">


                            <div class="form-row mb-3">
                                <div class="col h5 text-center">
                                    Данные о пусконаладочных работах (пуске оборудования в эксплуатацию), сервисному обслуживанию и ремонту заносятся авторизованным сервисным центром.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                                    
              
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {
                
            let deviceId=$('#product')[0].dataset.deviceId;
            
            function temperature(deviceId) {
            $.get("/api/mqtt/getstatusarc/"+deviceId,function(data){
                $('#product_temp_'+deviceId).html(data.temperature);
                $('#product_prefferedTemp_'+deviceId).html(data.prefferedTemp);
                
                if(data.flame==1) {
                    $('#product_flame_'+deviceId).removeClass('d-none');
                    $('#product_flame_off_'+deviceId).addClass('d-none');
                }else{
                $('#product_flame_'+deviceId).addClass('d-none');
                    $('#product_flame_off_'+deviceId).removeClass('d-none');
                }
            });
            }
            function temperatureload(deviceId) {
            $.get("/api/mqtt/getstatusarc/"+deviceId,function(data){
                $('#product_temp_'+deviceId).html(data.temperature);
                $('#product_prefferedTemp_'+deviceId).html(data.prefferedTemp);
                $('#temp_set_'+deviceId).val(data.prefferedTemp);
                
                if(data.flame==1) {
                    $('#product_flame_'+deviceId).removeClass('d-none');
                    $('#product_flame_off_'+deviceId).addClass('d-none');
                }else{
                $('#product_flame_'+deviceId).addClass('d-none');
                    $('#product_flame_off_'+deviceId).removeClass('d-none');
                }
            });
            }

            if(deviceId) {
                temperatureload(deviceId);

                let timerId = setInterval(() => temperature(deviceId), 5000);
            }

            $(document).on('click', '.temp_sub', (function(I){
                let prefferedTemp=$('#temp_set_'+$(this)[0].dataset.deviceId).val();
                $.get("/api/mqtt/settemperature/"+deviceId,{ "temperature":prefferedTemp},function(data){
                    setTimeout(() => { temperature(deviceId); }, 5000);
                });
                $('#temp_set_help_'+deviceId).removeClass('d-none');
            }));             
        
        
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush