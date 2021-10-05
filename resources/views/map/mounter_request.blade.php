@extends('layouts.app')
@section('title')@lang('site::map.mounter_request.title')@lang('site::messages.title_separator')@endsection
@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endpush

@section('header')
    @include('site::header.front',[
        'h1' => '<i class="fa fa-'.__('site::map.mounter_request.icon').'"></i> '
        .__('site::map.mounter_request.title'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::map.mounter_request.title')]
        ]
    ])
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="offset-sm-0 col-sm-12 offset-1 col-10">
                @alert()@endalert()
                <div class="row">
                    <div class="col-12">
                        <form method="POST" id="form" action="{{route('mounter-requests')}}">
                            @csrf
                            <div class="input-group mb-2">
                                <select class="form-control" name="filter[region_id]" title="">
                                    <option value="">@lang('site::region.help.select_all')</option>
                                    @foreach($regions as $region)
                                        <option @if($region_id == $region->id) selected @endif
                                        value="{{$region->id}}">{{$region->name}}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <button class="btn btn-ms" type="submit">@lang('site::messages.show')</button>
                                </div>
                            </div>
                            @if($authorization_types)

                                <div class="form-row">
                                    <div class="col">
                                        @foreach($authorization_types as $authorization_type)
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       @if(empty($selected_authorization_types) || in_array($authorization_type->id, $selected_authorization_types))
                                                       checked
                                                       @endif
                                                       name="filter[authorization_type][]"
                                                       value="{{$authorization_type->id}}"
                                                       class="custom-control-input"
                                                       id="at-{{$authorization_type->id}}">
                                                <label class="custom-control-label"
                                                       for="at-{{$authorization_type->id}}">
                                                    {{$authorization_type->name}} {{$authorization_type->brand->name}}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h4 class="py-0" id="row-count">
                            @lang('site::messages.data_load')
                        </h4>
                    </div>
                    <div class="col-sm-12 text-center" id="loading-data">
                        <img src="{{asset('images/loading.gif')}}">
                    </div>
                    <div class="col-sm-12">
                        <div class="addresses-map mb-5" id="addresses-map"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h3 id="addresses-list">@lang('site::map.mounter_request.header')</h3>
                        <div id="container-addresses" data-action="{{route('api.mounter-requests')}}"></div>
                    </div>
                </div>
                
                @foreach($mounters as $mounter)
                <div class="card mb-2">
                    
                        <div class="card-body">
                            <h4 class="card-title mb-1 gray">{{!empty($mounter->brand_name) ? $mounter->brand_name : $mounter->name}} </h4>
                            <div class="mb-2">
                            @if($mounter->hasRole('dealer'))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Официальный дилер</span>@endif
                            @if($mounter->hasRole('asc'))<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Авторизованный сервисный центр</span>@endif
                            </div>
                            @foreach($addresses->where('addressable_id',$mounter->id)->values()->all() as $num=>$address)
                            @if($num == 2)
                            <div class="d-none">
                            @endif
                            <div class="ml-1">
                            
                                <h5><a href="javascript:void(0);" 
                                data-form-action="{{ route('api.service-address', ['address' => $address->id]) }}" data-label="{{$address->name}}" 
                                class="dynamic-modal-form-card">{{$address->name}} &nbsp; &nbsp;<span class="text-success small">Подробная информация <i class="fa fa-external-link"></i></span>
                                </a></h5>
                                
                                <dl class="row ml-1">
                                        <dd class="col-12">{{$address->full}}
                                        
                                         @if(isset($address->is_shop) && $address->is_shop === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Продажи</span>@endif
                                        @if(isset($address->is_service) && $address->is_service === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">Сервис</span>@endif
                                        
                                        </dd>
                                        <dt class="col-sm-2">@lang('site::phone.phones')</dt>
                                        <dd class="col-sm-10">@foreach($address->phones as $phone)
                                                {{$phone->country->phone}} {{$phone->number}} &nbsp;
                                            @endforeach</dd>
                                        @if(!is_null($address->web))
                                        <dt class="col-sm-2">@lang('site::contact.web')</dt>
                                                @if(stristr($address->web,'http'))
                                                <dd class="col-sm-10"><a target="_blank" href="{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                                                @else
                                                <dd class="col-sm-10"><a target="_blank" href="http://{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                                                @endif
                                        @endif
                                        
                                        @if(!is_null($address->email))
                                        <dt class="col-sm-2">@lang('site::user.email')</dt><dd class="col-sm-10">{{$address->email}}</dd>
                                        @endif
                                        
                                        <a href="{{route('mounters.create', $address->id)}}" class="card-link btn btn-success">
                                            @lang('site::messages.leave')
                                            @lang('site::mounter.mounter')
                                        </a>
                                        
                                        
                                </dl>
                             </div> 
                                                        
                            @endforeach
                            
                            @if($addresses->where('addressable_id',$mounter->id)->count() > 2)
                            </div>
					<a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).addClass('d-none')" 
					class="align-text-bottom text-left">
                    <b>Еще адреса</b></a>
                            @endif
                        
                
                
                
                
                        </div>
                </div>
                @endforeach
                
                
                
                
            </div>
        </div>
    </div>
@endsection
