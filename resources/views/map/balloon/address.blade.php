<!-- balloon/address.blade.php -->
<div class="modal-body text-center">
<div class="card mb-2">
    <div class="card-body">
       
        <dl class="row">
            <dd class="col-12">{{$address->full}}</dd>
            <dd class="col-12">    @if(isset($is_shop) && $is_shop === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">@lang('site::address.is_shop')</span>@endif
                @if(isset($is_service) && $is_service === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">@lang('site::address.is_service')</span>@endif
            </dd>
            <dt class="col-sm-4">@lang('site::phone.phones')</dt>
            <dd class="col-sm-8">
                @foreach($address->phones as $phone)
                    <div>{{$phone->country->phone}} {{$phone->number}}</div>
                @endforeach
            </dd>
            <dt class="col-sm-4">@lang('site::user.email')</dt>
            <dd class="col-sm-8"><a href="mailto:{{$address->email}}">{{$address->email}}</a></dd>
            @if(!is_null($address->web))
                <dt class="col-sm-4">@lang('site::contact.web')</dt>
		<!-- {{stristr($address->web,'http')}} -->
		@if(stristr($address->web,'http'))
                <dd class="col-sm-8"><a target="_blank" href="{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                @else
                <dd class="col-sm-8"><a target="_blank" href="http://{{$address->web}}" class="card-link">{{$address->web}}</a></dd>
                @endif
            @endif
				<dd class="col-12">{{$address->description}}</dd>
				@if(!is_null($address->image()))
				<div id="carouselEquipmentIndicators" class="carousel slide col-12 col-md-12 col-lg-12 p-0"
                         data-ride="carousel">
                        @if($address->images()->count() > 1)
                            <ol class="carousel-indicators">
                                @foreach($address->images as $key => $image)
                                    <li data-target="#carouselEquipmentIndicators" data-slide-to="{{$key}}"
                                        @if($key == 0) class="active" @endif></li>
                                @endforeach
                            </ol>
                        @endif
                        <div class="carousel-inner">
                            @foreach($address->images as $key => $image)
                                <div class="carousel-item @if($key == 0) active @endif">
                                    <img class="d-block w-100"
                                         src="{{ $image->src() }}"
                                         alt="{{$address->name}}">
                                </div>
                            @endforeach

                        </div>
                        @if($address->images()->count() > 1)
                            <a class="carousel-control-prev" href="#carouselEquipmentIndicators" role="button"
                               data-slide="prev">
                                <span class="carousel-control-prev-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.prev')</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselEquipmentIndicators" role="button"
                               data-slide="next">
                                <span class="carousel-control-next-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.next')</span>
                            </a>
                        @endif
                    </div>
				@endif
               
            @if(!is_null($accepts))
                <dd class="col-sm-12 text-left">
                @if($accepts->where('role_id','4')->count())
                <b>Продажи:</b>
                    @foreach($accepts->where('role_id','4') as $accept)
                        <div class="ml-2 px-2 text-left">{{$accept->type->name}} {{$accept->type->brand->name}}</div>
                    @endforeach
                @endif
                @if($accepts->where('role_id','3')->count())
                <b>Сервис:</b>
                    @foreach($accepts->where('role_id','3') as $accept)
                        <div class="ml-2 px-2 text-left">{{$accept->type->name}} {{$accept->type->brand->name}}</div>
                    @endforeach
                </dd>
                @endif
            @endif
        </dl>
        
    </div>
</div>
</div>
