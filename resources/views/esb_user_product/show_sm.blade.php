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
        
        @if(auth()->user()->type_id != 4)
        <div class="card mb-2">
            <div class="card-body">
                        Даные о владельце обородования не отображаются, т.к. нет связи конечного потребителя с Вашим сервисным центром.<br />
                        Для привязки потребитель должен выбрать Ваш сервисный центр. 
                        <br />Отправьте ему ссылку на свой сервисный центр: 
                         <a target="_blank" href="{{route('public-user-card',auth()->user()->company())}}" >
                            {{route('public-user-card',auth()->user()->company())}}
                            <span class="text-success"><i class="fa fa-external-link"></i></span></a><br />
                            <a class="btn btn-sm btn-ms mt-2" href="javascript:void(0);" id="send-invite"><i class="fa fa-envelope"></i> Отправить</a>
                <div id="invite-sended">
                </div>
            </div>
        </div>
        @endif
            
        <div class="row justify-content-center mb-5">
            <div class="col">
                    <div class="card mb-2">
                        <div class="card-body">
                        
                        @if($esbUserProduct->product && $esbUserProduct->product->warranty=='1' && $esbUserProduct->product->equipment_id)
                           
                                <h4 class="mt-0 mb-2">Электронный гарантийный талон</h4>
                                @if($esbUserProduct->next_maintenance < \Carbon\Carbon::now()->addMonth() || !$esbUserProduct->launch())
                                <p class="text-success mb-2">@lang('site::user.esb_user_product.warranty_requred_asc')
                                </p>
                                @endif
                        На ваше оборудование распространяется гарантия:
                        <br />- стандартная гарантия производителя @if($esbUserProduct->product->warranty_time_month)({{$esbUserProduct->product->warranty_time_month/12}} года)@endif, условия которой обозначены в тексте <a href="/up/warranty-blank.pdf">гарантийного талона <i class="fa fa-external-link"></i></a>
                        @if($esbUserProduct->product->extended_warranty)
                            <br />- дополнительная гарантия ООО «ФерролиРус», условия которой обозначены 
                                <a href="/up/warranty-add-blank.pdf">здесь <i class="fa fa-external-link"></i></a>
                        @endif   
                                @endif
                            <div class="form-row mb-3">
                                <div class="col-sm-9">
                                   <h4>{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</h4>
                                   
                                   <dl class="row mb-0"> 
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.serial')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->serial}}</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_sale')</dt>
                                            <dd class="col-sm-9">{!!$esbUserProduct->date_sale ? $esbUserProduct->date_sale->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.address_id')</dt>
                                        <dd class="col-sm-9">{{$esbUserProduct->address ? $esbUserProduct->address->locality : ''}}</dd>
                                       
                                    </dl>
                                @if($esbUserProduct->product && $esbUserProduct->product->type_id == 1)
                                            
                                            <hr>
                                            
                                            @if($esbUserProduct->warranty['type'] == 'mainextended')
                                            <h4>Действует стандартная (до {{$esbUserProduct->warranty_main_before}})
                                            <br />и дополнительная гарантия (до {{$esbUserProduct->warranty_extended_before}})</h4>
                                             
                                            @elseif($esbUserProduct->warranty['type'] == 'extended')
                                            <h4>Действует дополнительная гарантия до {{$esbUserProduct->warranty_extended_before}}</h4>
                                             @lang('site::user.esb_user_product.warranty_year_name.'.$esbUserProduct->up_time_year)
                                             <div class="d-none b-block">
                                             @lang('site::user.esb_user_product.warranty_year_text.'.$esbUserProduct->up_time_year)
                                             </div>
                                            <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).next().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary d-none  b-block">
                                                    <b>Скрыть</b>
                                            </a>
                                            <a href="javascript:void(0);" onclick="$(this).prev().prev().toggleClass('d-none');$(this).prev().toggleClass('d-none');$(this).toggleClass('d-none')" 
                                            class="btn btn-sm btn-secondary">
                                                    <b>Подробнее</b>
                                            </a>
                                            @elseif($esbUserProduct->warranty['type'] == 'standart')
                                            <h4>Действует стандартная гарантия до {{$esbUserProduct->warranty_main_before}}</h4>
                                             @lang('site::user.esb_user_product.warranty_year_text.'.$esbUserProduct->up_time_year)
                                            
                                            @elseif($esbUserProduct->product->warranty && $esbUserProduct->product->extended_warranty)
                                            <h4>Дополнительная гарантия не активирована</h4>
                                            {{$esbUserProduct->warranty['error']}}
                                            @elseif($esbUserProduct->warranty['type'] == 'main')
                                            <h4>Действует стандартная гарантия</h4>
                                           
                                            @else
                                            <h4>Нет данных о гарантии</h4>
                                            {{$esbUserProduct->warranty['error']}} 
                                            {{$esbUserProduct->unbroken_maintenance}}
                                            @endif
                                           
                                            <hr>
                                   
                                   <h5>@lang('site::user.esb_user_product.launches_info')</h5>
                                        <dl class="row mb-0">
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.date_launch')</dt>
                                            <dd class="col-sm-9">{!!$launch ? $launch->date_launch->format('d.m.Y') : '<span class="text-danger">Нет данных</span>'!!}</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.asc')</dt>
                                            <dd class="col-sm-9">
                                                {{$launch ? $launch->service->name : ''}}</dd>
                                           
                                       </dl>
                                         <hr />
                                   <h5>@lang('site::user.esb_user_product.maintenance_info')</h5>
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
                                                <dd class="col-sm-9">{{$maintenance->date->format('d.m.Y')}}</dd>
                                                <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.asc')</dt>
                                                <dd class="col-sm-9"><a target="_blank" href="{{route('public-user-card',$maintenance->service)}}">
                                                    {{$maintenance->service->name}}</a></dd>
                                                
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
                                
                                        @if($esbUserProduct->esbClaim)
                                        

                                        <dl class="row">
                   
                                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_claim.launch_comment')</dt>
                                        <dd class="col-sm-8">{{$esbUserProduct->esbClaim->launch_comment}}</dd>
                                         
                                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_claim.heat_system')</dt>
                                        <dd class="col-sm-8">{{$esbUserProduct->esbClaim->heat_system}}</dd>
                                         
                                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_claim.electric_system')</dt>
                                        <dd class="col-sm-8">{{$esbUserProduct->esbClaim->electric_system}}</dd>
                                         
                                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_claim.combustor_type')</dt>
                                        <dd class="col-sm-8">@lang('site::user.esb_claim.combustor_types.'.$esbUserProduct->esbClaim->smokestack_type)</dd>
                                         
                                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::user.esb_claim.smokestack')</dt>
                                        <dd class="col-sm-8">{{$esbUserProduct->esbClaim->smokestack}}</dd>
                                        </dl>
                                        
                                        @endif
                                </div>
                                <div class="col-sm-3">
                                    @if($esbUserProduct->product)
                                        <img style="height:150px" src="{{$esbUserProduct->product->images()->first()->src()}}">
                                    @endif
                                </div>
                                
                            </div>
                            
                            
                         
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
               
                $(document)
                        .on('click', '#send-invite', (function(I){
                        $('#send-invite').toggleClass('d-none');
                        var client_user_id = {{$esbUserProduct->user_id}};
                        let field = $('#invite-sended');
                            axios
                                .post("/api/esb-user-invite", {"client_user_id" : client_user_id})
                                .then((response) => {
                                    field.html(response.data);
                                    
                                })
                                .catch((error) => {
                                    this.status = 'Error:' + error;
                                });
                        
                        }))
                        
    
    });} catch (e) {
        console.log(e);
    }

</script>
@endpush