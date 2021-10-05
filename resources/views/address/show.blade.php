@extends('layouts.app')

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
                iconContent: '{{$address->name}}',
                balloonContent: '{{$address->full}}'
            }, {
                preset: 'islands#blackStretchyIcon',
                iconColor: '#0095b6'
            }))
    }
</script>
@endpush
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('addresses.index') }}">@lang('site::address.addresses')</a>
            </li>
            <li class="breadcrumb-item active">{{ $address->full }}</li>
        </ol>
        <h1 class="header-title mb-4">
            <i class="fa fa-@lang('site::address.icon')"></i> {{ $address->full }}
        </h1>
        @alert()@endalert()
        <div class="justify-content-start border p-3 mb-2">
            <a class="@cannot('edit', $address) disabled @endcannot btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('addresses.edit', $address) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::address.address')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('addresses.phones.create', $address) }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::phone.phone')</span>
            </a>
				
            <a href="{{ route('addresses.images.index', $address) }}"
               class="d-block mr-0 mr-sm-1 mb-1 mb-sm-0 d-sm-inline btn btn-ms-red">
                <i class="fa fa-image"></i>
                <span>@lang('site::image.images')</span>
                <span class="badge badge-light">{{$address->images()->count()}}</span>
            </a>
            <a href="{{ route('addresses.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
        </div>
        <div class="card mb-2">
            <div class="card-body">
                <h4 class="card-title">{{$address->type->name}}</h4>
                <dl class="row">

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::messages.approved') <br />(@lang('site::messages.approved_help'))</dt>
                    <dd class="col-sm-8">@bool(['bool' => $address->approved])@endbool</dd>
						  <dt class="col-sm-4 text-left text-sm-right"><hr></dt><dd class="col-sm-8"><hr></dd>
						  
						  <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.name')</dt>
                    <dd class="col-sm-8"> {{ $address->name }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.full')</dt>
                    <dd class="col-sm-8"> {{ $address->postal }} {{ $address->full }}</dd>
						<dt class="col-sm-4 text-left text-sm-right"><hr></dt><dd class="col-sm-8"><hr></dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.web')</dt>
                    <dd class="col-sm-8"> {{ $address->web }}</dd>
						  
						<dt class="col-sm-4 text-left text-sm-right"><hr></dt><dd class="col-sm-8"><hr></dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.email')</dt>
                    <dd class="col-sm-8"> {{ $address->email }}</dd>

						  <dt class="col-sm-4 text-left text-sm-right"><hr></dt><dd class="col-sm-8"><hr></dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::address.description')</dt>
                    <dd class="col-sm-8"> {{ $address->description }}</dd>
							<dt class="col-sm-4 text-left text-sm-right"><hr></dt><dd class="col-sm-8"><hr></dd>
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::phone.phones')</dt>
                    <dd class="col-sm-8">
                        <ul class="list-group list-group-flush"></ul>
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
                                <a href="{{route('addresses.phones.edit', [$address, $phone])}}"
                                   class="pull-right btn btn-ms btn-sm py-0 mx-1">
                                    <i class="fa fa-pencil"></i>
                                    @lang('site::messages.edit')
                                </a>
                                <form id="phone-delete-form-{{$phone->id}}"
                                      action="{{route('addresses.phones.destroy', [$address, $phone])}}"
                                      method="POST">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </li>
                        @endforeach
                    </dd>
					@if($address->type_id==6)
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
