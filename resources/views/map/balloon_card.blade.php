<div class="card mb-2">
<!-- map/balloon_card.blade.php -->
    <div class="card-body">
        <h4 class="card-title">{{$name}}</h4> 
        <dl class="row">
            <dd class="col-12">{{$address}}</dd>
            <dd class="col-12">
                @if(isset($is_shop) && $is_shop === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">@lang('site::address.is_shop')</span>@endif
                @if(isset($is_service) && $is_service === true)<span class="badge text-normal mb-0 mb-sm-1 badge-ferroli">@lang('site::address.is_service')</span>
                
            <a class="badge text-normal mb-0 mb-sm-1 badge-primary" href=""><i class="fa fa-check"></i> Выбрать этот сервисный центр</a>
            @endif
            </dd>
            <dt class="col-sm-4">@lang('site::phone.phones')</dt>
            <dd class="col-sm-8">
                @foreach($phones as $phone)
                    <div>{{$phone->country->phone}} {{$phone->number}}</div>
                @endforeach
            </dd>
            <dt class="col-sm-4">@lang('site::user.email')</dt>
            <dd class="col-sm-8"><a href="mailto:{{$email}}">{{$email}}</a></dd>
            @if(!is_null($web))
                <dt class="col-sm-4">@lang('site::contact.web')</dt>
		@if(stristr($web,'http'))
                <dd class="col-sm-8"><a target="_blank" href="{{$web}}" class="card-link">{{$web}}</a></dd>
                @else
                <dd class="col-sm-8"><a target="_blank" href="http://{{$web}}" class="card-link">{{$web}}</a></dd>
                @endif
            @endif
            @if(!is_null($description))
                <dd class="col-12">{{$description}}</dd>
            @endif
				@if(!is_null($image))
				<img src="{{$image->src()}}">
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
            @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby']) && $accepts->where('role_id','4')->count())
                <dt class="col-sm-12"><a class="btn btn-secondary mb-4" href="{{route('user_market_change',['address_id'=> $address_id, 'region_id'=>$region_id])}}"><i class="fa fa-cart-plus"></i> Заказать <span class="hide-on-mobile">оборудование</span> в этом магазине</a></dt>
            @endif
        </dl>
        @yield('link')
    </div>
</div>
