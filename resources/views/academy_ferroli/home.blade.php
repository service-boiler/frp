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
            <li class="breadcrumb-item active">@lang('site::home.academy')</li>
        </ol>
        @alert()@endalert
        <div class="row">
            <div class="col-xl-5">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="media">
                            <img id="user-logo" src="{{$user->logo}}" style="width:100px!important;height: 100px"
                                 class="rounded-circle">
                            <div class="media-body pt-2 ml-3">
                                <h5 class="mb-2">{{ $user->name }}</h5>
                                @if($user->UserMotivationStatus)<strong class="rng">@lang('site::user.motivation_status.' .$user->UserMotivationStatus)</strong>@endif
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
                    
                </div>
     @if(in_array(env('MIRROR_CONFIG'),['sfru']))
                @if($user->type_id=='3' || $user->hasRole(['montage_fl']) || $user->hasRole(['service_fl']))     
                    <div class="card mb-4">
                    <div class="card-body">
                    <h5 class="card-title">Ваши сертификаты:</h5>
                        @if($user->mountingCertificates()->exists())
                            <a class="btn btn-ms" href="{{route('certificates.show', $user->mountingCertificates()->first())}}" target="_blank">
                                <i class="fa fa-download"></i> Монтажник {{$user->mountingCertificates()->first()->id}}
                            </a> 
                        @endif
                
                    @if($user->serviceCertificates()->exists())
                        @if(!empty($user->acceptedParents->first()) && ($user->parents->first()->hasRole('csc') || $user->parents->first()->hasRole('asc')) && ($user->hasRole('service_fl') || $user->hasRole('asc'))) 
                                            <a class="btn btn-ms-blue"   href="{{route('certificates.show', $user->serviceCertificates()->first())}}" target="_blank">
                                            <i class="fa fa-download"></i> Сервис {{$user->serviceCertificates()->first()->id}}
                                            </a> 
                        @else
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Чтобы скачать сертификат сервисного инженера Ваш пользователь должен быть привязан к компании - сервисному центру">                   
                                            <a class="btn btn-ms-blue disabled"   href="#">
                                            <i class="fa fa-download"></i> Сервис
                                            </a>
                                            </span>
                                            
                        @endif
                    @endif
                    @if($user->saleCertificates()->exists())
                        @if(!empty($user->acceptedParents->first()) && ($user->parents->first()->hasRole('dealer') || $user->parents->first()->hasRole('dealer')) && ($user->hasRole('sale_fl') || $user->hasRole('dealer'))) 
                                            <a class="btn btn-ms-blue"   href="{{route('certificates.show', $user->saleCertificates()->first())}}" target="_blank">
                                            <i class="fa fa-download"></i> Продажи {{$user->saleCertificates()->first()->id}}
                                            </a> 
                        @else
                                            <span data-toggle="tooltip" data-placement="top" data-original-title="Чтобы скачать менеджера по продажам Ваш пользователь должен быть привязан к компании - дилеру">                   
                                            <a class="btn btn-ms-blue disabled"   href="#">
                                            <i class="fa fa-download"></i> Продажи
                                            </a>
                                            </span>
                                            
                        @endif
                    @endif
                
                    @if(!$user->userCertificates()->exists())
                    Для получения сертификатов Вам необходимо пройти обучение и успешно выполнить задания тестов.
                    @endif
                    @if($user->acceptedParents()->exists())
                        @if(!$user->acceptedParents()->first()->hasRole('asc'))
                        <p class="text-success">Ваша компания {{$user->acceptedParents()->first()->name}} не имеет статуса Авторизованного СЦ.</p>
                        @endif
                    @endif
                
                    </div>
                    </div>
                    <div class="card mb-4">
                        <div class="card-body">
                    <h5 class="card-title mt-2">Ваш статус в программе мотивации: </h5>
                    
                    @if($user->hasRole('montage_fl'))
                        <h5 class="card-title mt-2"><strong class="rng">@lang('site::user.motivation_status.' .$user->UserMotivationStatus)</strong></h5>
                            @lang('site::user.motivation_status_next.' .$user->UserMotivationStatus)
                    @endif
                    @if($user->hasRole('sale_fl'))
                        <h5 class="card-title mt-2"><strong class="rng">@lang('site::user.motivation_status.' .$user->UserMotivationStatus)</strong></h5>
                            @lang('site::user.motivation_sale_status_next.' .$user->UserMotivationSaleStatus)
                    @endif
                    
                    </div>
                    </div>
                    @permission('user-relations')
                    @if($user->type_id == 3)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">@lang('site::user.position_fl')</h5>
                            
                           <p><b>@if(!empty($parent)) {{$parent->name}} : @endif 
                            @if($user->hasRole('sale_fl')) Продавец/Менеджер @endif
                            @if($user->hasRole('service_fl')) Сервисный инженер @endif
                            @if($user->hasRole('montage_fl')) Монтажник @endif
                            
                            </b></p>
                            @if(empty($parent))<p>В профиле Вашего пользователя не указана компания.</p> @endif
                            <a href="{{route('user_relations.index')}}"
                                   class="btn btn-ms">Изменить компанию или добавить должность.</a>
                                   
                               
                                   
                            
                        </div>
                    </div>
                    @endif
                    @endpermission()
                
                @else
                
                
                <div class="card mb-4">
                    <div class="card-body">
                    <h5>Ваши сертификаты сервисного центра:</h5>
                <p class="card-text">
                @if($user->active && $user->hasRole('asc') && !empty($user->contragents()->whereNotNull('contract')->orderByDesc('created_at')->first()->contract))
                <a class="btn btn-ms mb-2" target="_blank" href="{{route('certificate_sc','sc_ferroli')}}">Серитификат Ferroli Сервис</a>
                <a class="btn btn-danger" target="_blank" href="{{route('certificate_sc','sc_lambo')}}">Серитификат Lamborgini Сервис</a>
                @elseif($user->active && $user->hasRole('asc'))
                Для Вашего юридического лица не внесен номер договора на сервис. Обратитесь к Вашему менеджеру через <a href="{{route('messages.index')}}">сайт</a>, либо по почте service@ferroli.ru
                @else
                Вы не авторизованы как сервисный центр Ferroli
                @endif
                </p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                <p class="card-text">
                                 Для прохождения обучения, получения сертификатов инженера и начисления баллов Ваши сотрудники должны быть зарегистрированы отдельно на сайте как физические лица с отдельным адресом электронной почты.
                                 <br />Если у Вас нет других сотрудников, пожалуйста, напишите на адрес service@ferroli.ru запрос о изменении настроек Вашего личного кабинета.
                            </p>
                    </div>
                </div>
                @endif
    @endif
            
            </div>
            <div class="col">
 @if(in_array(env('MIRROR_CONFIG'),['sfru']))    
            @if(!empty($user->userRelationChilds->where('enabled','1')->where('accepted','0')->count()))
                <div class="card mb-4">
                    <div class="card-body">
                    <h5 class="card-title">Заявки от пользователей на привязку к Вашей компании:</h5>
                         <table class="table table-bordered bg-white table-sm table-hover mt-2"><thead>
                            
                            </thead>
                            <tbody>
                                @foreach($user->userRelationChilds->where('enabled','1')->where('accepted','0') as $userRelationChild)
                                <tr id="relation-{{$userRelationChild->id}}">
                                    
                                      <td class="align-middle"> {{$userRelationChild->child->name}}</td>
                                           
                                          <td class="align-middle"> <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::user.relation_accepted_help')"> @if($userRelationChild->accepted)
                                            @lang('site::user.relation_accepted')
                                            @else
                                            @lang('site::user.relation_not_accepted')
                                            @endif
                                            @bool(['bool' => $userRelationChild->accepted])@endbool </span>
                                           </td>            
                                        
                                           <td class="align-middle"> 
                                          @if(!$userRelationChild->accepted) 
                                            <form class="d-sm-inline-block" action="{{route('user-relations.update',$userRelationChild)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="userRelation[accepted]" value="1"/>
                                            <button class="btn btn-primary" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить связь">@lang('site::user.relation_accept')</button>
                                        
                                            </form>
                                           @endif
                                           <a class="btn btn-danger btn-row-delete"
                                               title="@lang('site::messages.delete')"
                                               href="javascript:void(0);"
                                               data-form="#relation-delete-form-{{$userRelationChild->id}}"
                                               data-btn-delete="@lang('site::messages.delete')"
                                               data-btn-cancel="@lang('site::messages.cancel')"
                                               data-label="@lang('site::messages.delete_confirm')"
                                               data-message="@lang('site::messages.delete_sure') {{ $userRelationChild->child->name }}?"
                                               data-toggle="modal"
                                               data-target="#form-modal">
                                                <i class="fa fa-close"></i>
                                                @lang('site::messages.delete')
                                            </a>
                                            <form id="relation-delete-form-{{$userRelationChild->id}}"
                                                  action="{{route('user-relations.destroy', $userRelationChild)}}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            </td>
                                    
                                 </tr>   
                                @endforeach
                                </tbody>
                            </table> 
                    </div>
                    </div>
                    
                @endif 
                
                
               
                @if($user->type_id=='3' || $user->hasRole(['montage_fl']) || $user->hasRole(['service_fl']))        
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Пройти обучение и тестирование OnLine</h5>
                            <p class="card-text">@lang('site::authorization.learn.text')</p>
                            
                            <p><a href="{{route('academy_ferroli.programs.show', '1')}}" target="_blank"
                                class="btn btn-ms blink-t">Монтаж оборудования Ferroli</a>
                            
                            @if($user->hasRole(['service_fl']))        
                                <a href="{{route('academy_ferroli.programs.show', '2')}}" target="_blank"
                                    class="btn btn-ms-blue blink-t">Сервис оборудования Ferroli</a>
                            @endif
                            @if($user->hasRole(['sale_fl']))        
                                <a href="{{route('academy_ferroli.programs.show', '3')}}" target="_blank"
                                    class="btn btn-ms-blue blink-t">Продажи оборудования Ferroli</a>
                            @endif
                            </p>
                            
                          
                        
                        </div>
                    </div>
                    
                @else
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Пройти обучение и тестирование OnLine</h5>
                            <p class="card-text">
                                 Для прохождения обучения, получения сертификатов и начисления баллов Ваши сотрудники должны быть зарегистрированы отдельно на сайте как физические лица с отдельным адресом электронной почты.
                                 <br />Если у Вас нет других сотрудников, пожалуйста, напишите на адрес service@ferroli.ru запрос о изменении настроек Вашего личного кабинета.
                            </p>
                        
                        </div>
                    </div>
                 
                @endif
@endif

                <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Обучающие материалы</h5>
                            
                        @foreach($programs as $program)
                        
                        <strong>{{$program->name}}</strong>
                        <p>
                            @foreach($program->stages as $stage)
                           
                                @foreach($stage->presentations as $presentation)
                            
                                <p><i class="fa fa-photo"></i> <a href="{{route('home_academy_presentation',$presentation)}}">{{$presentation->name}}</a></p>
                            
                            
                                @endforeach
                                @foreach($stage->videos as $video)
                            
                                <p><i class="fa fa-youtube"></i> <a href="{{route('home_academy_video',$video)}}">{{$video->name}}</a></p>
                            
                            
                                @endforeach
                                
                            @endforeach
                        
                        </p>
                        
                        
                        @endforeach
                
                    </div>
                </div>
                @permission('manuals_for_sc')
                <div class="card mb-4">
                    <div class="card-body">
                        <a href="/storage/datasheets/static/service-divabel-vitabel-fortuna.pdf" class="btn btn-primary mt-2" target="_blank">
                            <i class="fa fa-upload"></i>
                            @lang('site::home.manuals_for_sc')
                        </a>
                        <a href="https://yadi.sk/i/jyhWj8SV78z8RA" class="btn btn-primary mt-2" target="_blank">
                            <i class="fa fa-upload"></i>
                            Инструкция по настройке параметров котлов Fortuna
                        </a>
                        <a href="https://yadi.sk/d/4CRXskOjGOatXA" class="btn btn-primary mt-2" target="_blank">
                            <i class="fa fa-upload"></i>
                            Инструкция по настройке газового клапана Divatech D
                        </a>
                        <a class="btn btn-green mt-2" href="{{ route('revision_parts.index') }}">
                            <i class="fa fa-info"></i> @lang('site::admin.revision_part.index')</a>
                            
                        </a>
                    </div>
                </div>
                @endpermission()
            </div>
        </div>
    </div>
@endsection
