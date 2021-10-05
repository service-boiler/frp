@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.index') }}">Клиенты</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.esb-clients.show',$user) }}">{{ $user->name }}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_user_product.index')</li>
            {{--
            <div class="ml-md-auto">
            <li class="breadcrumb-item">

               <a href="{{ route('esb-user-products-archive') }}">@lang('site::user.esb_user_product.index_archive')</a>
            
            </li>

            </div>--}}
        </ol>
        <div class=" border p-3 mb-2">
            <a href="{{ route('admin.esb-clients.esb-products.create', $user) }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 btn-ms">
                <i class="fa fa-plus"></i>
                <span>Добавить оборудование</span>
            </a>
        </div>
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
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbUserProducts])@endpagination
        {{$esbUserProducts->render()}}
        @foreach($esbUserProducts as $esbUserProduct)
            <div class="card my-4" id="product-{{$esbUserProduct->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-user-products.show', $esbUserProduct)}}" class="mr-3 ml-0">
                           {{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}
                        </a>
                    </div>
                    <div class="card-header-elements ml-md-auto">
                        @if($esbUserProduct->visits()->exists() && in_array($esbUserProduct->visits()->first()->status_id, [1,2,3,5]))
                        Запланирован выезд 
                        {{$esbUserProduct->visits()->first()->date_planned->format('d.m.Y H:i')}} 
                        @lang('site::date.week.' .$esbUserProduct->visits()->first()->date_planned->dayOfWeek). 
                        Статус выезда: &nbsp; &nbsp;
                        <a href="{{route('esb-visits.index',['filter[search_id]'=>$esbUserProduct->visits()->first()->id])}}" class="badge text-normal text-white" style="background-color: {{ $esbUserProduct->visits()->first()->status->color }}"
                         data-toggle="tooltip" data-placement="top" data-original-title="Запланирован - отправлено уведомление клиенту. 
                         Согласован с клиентом - клиент подтвердил дату и время">
                            <i class="fa fa-{{ $esbUserProduct->visits()->first()->status->icon }}"></i> {{ $esbUserProduct->visits()->first()->status->name }}
                        </a>
                        @elseif(auth()->user()->type_id!=4)  
                            @if($esbUserProduct->esbUserRequestActual())
                                <a class="btn btn-sm btn-ferroli mr-3 ml-0"
                                    href="{{route('esb-requests.index',['filter[search_id]'=>$esbUserProduct->esbUserRequestActual()->id])}}">
                                    Есть заявка от клиента! 
                                    {{optional($esbUserProduct->esbUserRequestActual()->date_planned)->format('d.m.Y')}}</a>
                            @endif          
                        <a href="{{route('esb-user-products.show', $esbUserProduct)}}#anc-visits" class="btn btn-sm btn-primary mr-3 ml-0">
                           <i class="fa fa-wrench">Запланировать выезд</i>
                        </a>
                        @endif
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_product_launch.date_launch')</dt>
                            <dd class="col-12">{{$esbUserProduct->launch() ? $esbUserProduct->launch()->date_launch->format('d.m.Y') : 'нет данных'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.date_sale')</dt>
                            <dd class="col-12">{{$esbUserProduct->date_sale ? $esbUserProduct->date_sale->format('d.m.Y') : 'не указана'}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-2">
                        <dl class="dl-horizontal mt-2">
                            
                            <dt class="col-12">@lang('site::user.esb_user_product.serial')</dt>
                            <dd class="col-12">{{$esbUserProduct->serial ? $esbUserProduct->serial : 'не указан'}}
                                
                            </dd>
                        </dl>
                    </div>
                    <div class="col-sm-5">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounter.address')</dt>
                            <dd class="col-12">{{$esbUserProduct->address ? $esbUserProduct->address_filtred : 'не указан'}}</dd>
                            <dt class="col-12">@lang('site::user.esb_product_launch.user')</dt>
                            <dd class="col-12">{{$esbUserProduct->user_filtred}}</dd>
                            <dd class="col-12">{{$esbUserProduct->phone_filtred}}</dd>
                        </dl>
                    </div>
                    <div class="col-sm-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_user_product.dates_maintenance')</dt>
                            <dd class="col-12">@if($esbUserProduct->maintenances()->exists())
                                        @foreach($esbUserProduct->maintenances as $maintenance)
                                           <p class="m-0">{{$maintenance->date->format('d.m.Y')}} <i class="fa fa-check text-success"></i></p>
                                        @endforeach
                                    @else
                                        Нет данных
                                    @endif
                            
                            </dd>
                            <dt class="col-12">@lang('site::user.esb_user_product.next_maintenance_sm') </dt>
                            <dd class="col-12">{!!$esbUserProduct->next_maintenance ? $esbUserProduct->next_maintenance->format('d.m.Y').' <i class="fa fa-cog text-warning"></i>' : 'Нет данных'!!} </dd>
                        
                            <dt class="col-12">@lang('site::user.esb_user_product.warranty_info') </dt>
                            <dd class="col-12">
                            
                            @if($esbUserProduct->warranty['type'] == 'mainextended')
                                            Cтандартная (до {{$esbUserProduct->warranty_main_before}})
                                            <br />и доп. (до {{$esbUserProduct->warranty_extended_before}})</h4>
                                             
                                            @elseif($esbUserProduct->warranty['type'] == 'extended')
                                            Доп. гарантия до {{$esbUserProduct->warranty_extended_before}}
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
                                            
                                            @elseif($esbUserProduct->product && $esbUserProduct->product->warranty && $esbUserProduct->product->warranty_extended)
                                            Гарантия не активирована
                                            {{$esbUserProduct->warranty['error']}}
                                            @elseif($esbUserProduct->warranty['type'] == 'main')
                                            Стандартная гарантия
                                           
                                            @else
                                            Нет данных о гарантии
                                            {{$esbUserProduct->warranty['error']}} 
                                            @endif
                            
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$esbUserProducts->render()}}
                                    
           
        
    </div>
@endsection
