@extends('layouts.app')
@section('title')Адрес {{$address->name}} {{$address->id}} @endsection
@push('scripts')
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
@endpush

@push('scripts')
<script>
    let myMap;

    ymaps.ready(init);

    function init() {
        myMap = new ymaps.Map('address-map', {
            center: [{{$address->geo}}],
            zoom: 12,
            controls: ['zoomControl', 'typeSelector', 'fullscreenControl']
        }, {
            searchControlProvider: 'yandex#search'
        });
        myMap.geoObjects
            .add(new ymaps.Placemark([{{$address->geo}}], {
                balloonContent: '{{$address->full}}'
            }, {
                preset: 'islands#blackIcon',
                iconColor: '#0095b6'
            }))
    }
</script>
@endpush
@section('content')
    <div class="container">
        <ol class="breadcrumb"> 
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.addresses.index') }}">@lang('site::address.addresses')</a>
            </li>
            <li class="breadcrumb-item active">{{ $address->name }}</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::address.icon')"></i> {{ $address->name }}
        </h1>
        @alert()@endalert()
        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.addresses.edit', $address) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::address.address')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.addresses.phones.create', $address) }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::phone.phone')</span>
            </a>
            @if($address->canEditRegions)
                <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
                   href="{{ route('admin.addresses.regions.index', $address) }}"
                   role="button">
                    <i class="fa fa-map-marker"></i>
                    <span>@lang('site::address.regions') <span
                                class="badge badge-light">{{$address->regions->count()}}</span></span>
                </a>
            @endif
				
            <a href="{{ route('admin.addresses.images.index', $address) }}"
               class="d-block mr-0 mr-sm-1 mb-1 mb-sm-0 d-sm-inline btn btn-ms-red">
                <i class="fa fa-image"></i>
                <span>@lang('site::image.images')</span>
                <span class="badge badge-light">{{$address->images()->count()}}</span>
            </a>
				
            <a href="{{ route('admin.addresses.index') }}"
               class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
        </div>
        @if(!empty($address->card_warnings))
        <div class="alert alert-danger">
            
            @foreach($address->card_warnings as $warning)
                @lang('site::messages.card_warnings.' .$warning)
            @endforeach
            
        </div>
        @endif
        <div class="card mb-2">
            <div class="card-body">
                <h4 class="card-title">{{$address->type->name}}</h4>
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.approved')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->approved])@endbool</dd>
						  <hr>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.addressable.'.$address->addressable_type)</dt>
                    <dd class="col-sm-8">
                        @if($address->addressable->admin)
                            {{ $address->addressable->name }}
                        @else
                            <a href="{{ route('admin.'.$address->addressable_type.'.show', $address->addressable) }}">{{ $address->addressable->name }}</a>
                        @endif

                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.name')</dt>
                    <dd class="col-sm-8"> {{ $address->name }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.full')</dt>
                    <dd class="col-sm-8"> {{ $address->postal }}, {{ $address->full }}</dd>
<hr>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.show_ferroli')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->show_ferroli])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.show_lamborghini')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->show_lamborghini])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.show_market_ru')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->show_market_ru])@endbool</dd>

<hr>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.is_shop')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->is_shop])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.is_eshop')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->is_eshop])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.is_service')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->is_service])@endbool</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.is_mounter')</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->is_mounter])@endbool</dd>
<hr>
<hr>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.email-sm')</dt>
                    <dd class="col-sm-8"> {{ $address->email }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.web')</dt>
                    <dd class="col-sm-8"> {{ $address->web }}</dd>
<hr>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::phone.phones')</dt>
                    <dd class="col-sm-8">
                        @if($address->phones()->exists())
                            <ul class="list-group list-group-flush">
                                @foreach ($address->phones()->with('country')->get() as $phone)
                                    <li class="list-group-item">
                                        {{$phone->country->phone}}
                                        {{$phone->number}}
                                        @if($phone->extra)
                                            (@lang('site::phone.help.extra') {{$phone->extra}})
                                            @endif

                                            <button class="pull-right btn btn-sm btn-danger btn-row-delete py-0"
                                                    @cannot('delete', $phone) disabled @endcannot
                                                    data-form="#phone-delete-form-{{$phone->id}}"
                                                    data-btn-delete="@lang('site::messages.delete')"
                                                    data-btn-cancel="@lang('site::messages.cancel')"
                                                    data-label="@lang('site::messages.delete_confirm')"
                                                    data-message="@lang('site::messages.delete_sure') @lang('site::phone.phone')? "
                                                    data-toggle="modal" data-target="#form-modal"
                                                    title="@lang('site::messages.delete')">
                                                <i class="fa fa-close"></i>
                                                @lang('site::messages.delete')
                                            </button>
                                            <a href="{{route('admin.addresses.phones.edit', [$address, $phone])}}"
                                               class="pull-right btn btn-ms btn-sm py-0 mx-1">
                                                <i class="fa fa-pencil"></i>
                                                @lang('site::messages.edit')
                                            </a>
                                            <form id="phone-delete-form-{{$phone->id}}"
                                                  action="{{route('admin.addresses.phones.destroy', [$address, $phone])}}"
                                                  method="POST">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-tiny">@lang('site::messages.not_found')</p>
                        @endif

                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.storehouse_id')</dt>
                    <dd class="col-sm-8">
                        @if($address->storehouse)
                            <a href="{{route('admin.storehouses.show', $address->storehouse)}}">
                                {{ $address->storehouse->name }}
                            </a>
                            <table class="table table-sm">
                                <thead>
                                <tr>
                                    <th>@lang('site::storehouse.help.products')</th>
                                    <th>@lang('site::storehouse.uploaded_at')</th>
                                    <th>@lang('site::storehouse.enabled')</th>
                                    <th>@lang('site::storehouse.everyday')</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        @if($address->storehouse->products()->exists())
                                            <span class="col-12">{{ $address->storehouse->products()->count()}}</span>
                                        @else
                                            <span class="col-12 text-danger">@lang('site::messages.no')</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($address->storehouse->uploaded_at)
                                            <span class="col-12">{{$address->storehouse->uploaded_at->format('d.m.Y H:i')}}</span>
                                        @else
                                            <span class="col-12 text-danger">@lang('site::messages.never')</span>
                                        @endif
                                    </td>
                                    <td>@component('site::components.bool.pill', ['bool' => $address->storehouse->enabled])@endcomponent</td>
                                    <td>@component('site::components.bool.pill', ['bool' => $address->storehouse->everyday])@endcomponent</td>
                                </tbody>
                                </tr>
                            </table>
                        @endif
                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::product_group_type.product_group_types')</dt>
                    <dd class="col-sm-8">
                        @if($address->product_group_types()->exists())
                            <ul class="list-group">
                                @foreach($address->product_group_types as $product_group_type)
                                    <li class="list-group-item p-1">{{$product_group_type->name}}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-tiny">@lang('site::messages.not_found')</p>
                        @endif

                    </dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.regions')</dt>
                    <dd class="col-sm-8">
                        @if($address->regions()->exists())
                            <ul class="list-group">
                                @foreach($address->regions as $region)
                                    <li class="list-group-item p-1">{{$region->name}}</li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted text-tiny">@lang('site::messages.not_found')</p>
                        @endif
                    </dd>

                    
                    @if($address->type_id == 2 && $address->addressable_type == 'users')
                    
                    
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.user_authorizations')</dt>
                    <dd class="col-sm-8">
                    <table class="table bg-white table-sm table-bordered">
                            <thead class="thead-light">
                            <tr>
                                <th scope="col"></th>
                                @foreach($authorization_roles as $authorization_role)
                                    <th class="text-center" scope="col">{{$authorization_role->name}}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($authorization_types as $authorization_type)
                                <tr>
                                    <td class="text-right">{{$authorization_type->name}} {{$authorization_type->brand->name}}</td>
                                    @foreach($authorization_roles as $authorization_role)
                                        <td class="text-center">
                                            @if($authorization_accepts->contains(function ($accept) use ($authorization_role, $authorization_type) {
                                                return $accept->type_id == $authorization_type->id && $accept->role_id == $authorization_role->role_id;
                                            }))
                                                <span class="badge text-normal badge-success"><i
                                                            class="fa fa-check"></i></span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.user_roles')</dt>
                    <dd class="col-sm-8">
                    @foreach($roles as $role)
                            <span class="d-block text-normal @if($address->addressable->hasRole($role->name)) text-success @else text-danger @endif">
                                @if($address->addressable->hasRole($role->name)) ✔ @else ✖ @endif {{$role->title}}
                            </span>
                   @endforeach
                    </dd>
                    
                    @endif
                    <dt class="col-sm-4 text-left text-sm-right"></dt>
                    <dd class="col-sm-8">
                        <div id="address-map" style="height: 320px;width: 100%;"></div>
                    </dd>

                </dl>
            </div>
        </div>
    </div>
@endsection
