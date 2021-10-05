@extends('layouts.app')
@section('title') Адреса @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::address.addresses')</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::address.icon')"></i> @lang('site::address.addresses')
        </h1>
        @alert()@endalert
        <div class="border p-3 mb-2">
            <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $addresses])@endpagination
        {{$addresses->render()}}

        @foreach($addresses as $address)
            <div class="card my-2" id="address-{{$address->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a href="{{route('admin.addresses.show', $address)}}" class="text-big mr-3" target="_blank">
                            {{$address->name ? $address->name : $address->full}}
                        </a>
                        
                    </div>
                    <div class="card-header-elements">
                    {{$address->type->name}}
                    </div>
                    <div class="card-header-elements ml-md-auto">
                      @if(!empty($address->addressable->name))
                        <a href="{{route('admin.users.show', $address->addressable)}}">
                            {{$address->addressable->name}}
                        </a>
                      @else
                         Хозяин адреса не существует.
                      @endif
                    </div>
                </div>

                <div class="row">
                <div class="col-xl-11 col-sm-6">
                <dl class="dl-horizontal my-sm-2 my-0">
                
                <dd class="col-11 @if($address->type->id==2) bold @endif">{{$address->full}}</dd>
                </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal my-sm-2 my-0">
                            <dd class="col-11"> @bool(['bool' => $address->approved])@endbool  @lang('site::messages.approved')</dd>
                            <dd class="col-11"> @bool(['bool' => $address->show_ferroli])@endbool  @lang('site::messages.show_ferroli')</dd>
                            <dd class="col-11"> @bool(['bool' => $address->show_lamborghini])@endbool  @lang('site::messages.show_lamborghini')</dd>
                            <dd class="col-11"> @bool(['bool' => $address->show_market_ru])@endbool  @lang('site::messages.show_market_ru')</dd>
                            
                        </dl>
                    </div>
                    
                   
                    
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal my-sm-2 my-0">
                            <dd class="col-11">@bool(['bool' => $address->is_shop])@endbool @lang('site::address.is_shop')</dd>
                            <dd class="col-11">@bool(['bool' => $address->is_eshop])@endbool @lang('site::address.is_eshop')</dd>
                            <dd class="col-11">@bool(['bool' => $address->is_service])@endbool @lang('site::address.is_asc')</dd>
                            <dd class="col-11">@bool(['bool' => $address->is_mounter])@endbool @lang('site::address.is_mounter')</dd>
                            
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        @if($address->product_group_types()->exists())
                            <dl class="dl-horizontal my-sm-2 my-0">
                                @foreach($address->product_group_types as $product_group_type)
                                    <dd class="col-11"><i class="fa fa-check text-success"></i> {{$product_group_type->name}}</dd>
                                @endforeach
                            </dl>
                        @endif
                    </div>
                    
                    <div class="col-xl-3 col-sm-12">
                        <dl class="dl-horizontal my-sm-2 my-0">
                            @if($address->email)
                                <dt class="col-12">@lang('site::user.email')</dt>
                                <dd class="col-12">{{$address->email}}</dd>
                            @endif
                                     
                                     @if($address->phones()->exists())
                                        <dt class="col-12">@lang('site::phone.phones'):</dt>
                                            
                                            <dd class="col-12">
                                                <ul class="list-group"></ul>
                                                @foreach ($address->phones()->with('country')->get() as $phone)
                                                    <li class="list-group-item border-0 p-0">
                                                        {{$phone->country->phone}}
                                                        {{$phone->number}}
                                                        @if($phone->extra)
                                                            (@lang('site::phone.help.extra') {{$phone->extra}})
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </dd>
                                        
                                    @endif
                            
                            @if($address->web)
                                <dt class="col-12">@lang('site::address.web')</dt>
                                <dd class="col-12">{{$address->web}}</dd>
                            @endif
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$addresses->render()}}
    </div>
@endsection
