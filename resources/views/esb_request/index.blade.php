@extends('layouts.app')
@section('title') @lang('site::user.esb_request.esb_requests') @endsection
@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_request.esb_requests')</li>
        </ol>

        @alert()@endalert()
        @if($user->type_id==4)
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
                                    <a class="btn btn-primary w-100  mb-3" style="white-space: normal;" href="{{route('service-centers').'?filter[region_id]='.$user->region_id .'#sc_list'}}">
                                        Я выберу один сервис с которым заключу договор на обслуживание. 
                                        </a>
                                </div>
                                <div class="col-sm-6">
                                <a class="btn btn-secondary w-100  mb-3" style="white-space: normal;" href="{{route('esb-requests.create',['multiple'=>'1'])}}">
                                    Я хочу отправить заявки в несколько сервисных центров. 
                                    </a>
                                </div>
                            </div>

                    </div>
                </div>
        @endif
        {{-- @filter(['repository' => $repository])@endfilter --}}
        @pagination(['pagination' => $esbUserRequests])@endpagination
        {{$esbUserRequests->render()}}
        @foreach($esbUserRequests as $esbUserRequest)
       
            <div class="card my-2 mb-5" id="esbUserRequest-{{$esbUserRequest->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        @lang('site::user.esb_request.request') № {{$esbUserRequest->id}} {{$esbUserRequest->type->name}}
                        
                        &nbsp;<span class="badge text-normal badge-pill text-white" style="background-color: {{ $esbUserRequest->status->color }}">
                            <i class="fa fa-{{ $esbUserRequest->status }}"></i> {{ $esbUserRequest->status->name }}
                        </span>
                    </div>

                </div>
                <div class="row">
                    <div class="col-xl-2">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$esbUserRequest->created_at->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::user.esb_request.date_planned')</dt>
                            <dd class="col-12">{{$esbUserRequest->date_planned ? $esbUserRequest->date_planned->format('d.m.Y') : 'не указана'}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-3">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::user.esb_request.service')</dt>
                            <dd class="col-12">{{$esbUserRequest->service->name}}</dd>
                            <dt class="col-12">@lang('site::user.esb_request.product_sm')</dt>
                            <dd class="col-12">{{$esbUserRequest->esbUserProduct ? ($esbUserRequest->esbUserProduct->product ? $esbUserRequest->esbUserProduct->product->name: '' ) : ''}}</dd>
                            
                        </dl>
                    </div>
                    <div class="col-xl-6 col-sm-7">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Контакты и комментарии</dt>
                            <dd class="col-12">{{$esbUserRequest->phone ? $esbUserRequest->phone : $esbUserRequest->esbUser->phone}}</dd>
                            <dd class="col-12">{{$esbUserRequest->contact_name}}</dd>
                            <dd class="col-12">{{$esbUserRequest->comments}}</dd>
                            
                            
                        </dl>
                    </div>
                </div>
             
                @if($esbUserRequest->statuses()->exists())
                <div class="card-footer with-elements">
                    <form id="request-{{$esbUserRequest->id}}" action="{{route('esb_requests.status', $esbUserRequest)}}" method="POST">
                        @csrf
                        @method('PUT')
                                
                        <div class="card-footer-elements ml-md-auto"> Сменить статус заявки:
                             @foreach($esbUserRequest->statuses()->get() as $status)
                             <button form="request-{{$esbUserRequest->id}}" type="submit" name="esbUserRequest[status_id]" value="{{$status->id}}" 
                             class="btn btn-sm text-normal text-white m-2" style="background-color: {{ $status->color }}">
                                {{ $status->button }}
                             </a>
                             @endforeach
                        </div>
                    </form>
                </div>
                @endif
            </div>
        @endforeach
        {{$esbUserRequests->render()}}
    </div>
@endsection
