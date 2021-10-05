@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::mounting.mountings')</li>
        </ol>
        @alert()@endalert()

        <div class=" border p-3 mb-2">
            <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $mountings])@endpagination
        {{$mountings->render()}}
        @foreach($mountings as $mounting)
            <div class="card my-4" id="mounting-{{$mounting->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a href="{{route('admin.mountings.show', $mounting)}}" class="mr-2 ml-0">
                            @lang('site::mounting.header.mounting') № {{$mounting->id}}
                        </a>
                        <span class="badge text-normal badge-pill badge-{{ $mounting->status->color }} mr-3 ml-0">
                            <i class="fa fa-{{ $mounting->status->icon }}"></i> {{ $mounting->status->name }}
                        </span>
		@if($mounting->digiftBonus)
		@if($mounting->digiftBonus->sended) <span class="badge text-normal badge-pill badge-success mr-3 ml-0">{{$mounting->digiftBonus->operationValue}} отправлено в Digift</span>
		@endif
		@if($mounting->digiftBonus->blocked) <span class="badge text-normal badge-pill badge-danger mr-3 ml-0">Бонус блокирован</span>
		@endif
		@endif
                    </div>

                    <div class="card-header-elements ml-md-auto">
                        <a href="{{route('admin.users.show', $mounting->user)}}" class="mr-3 ml-0">
                            <img id="user-logo"
                                 src="{{$mounting->user->logo}}"
                                 style="width:25px!important;height: 25px"
                                 class="rounded-circle mr-2">{{$mounting->user->name}}
                        </a>
                        @if( $mounting->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $mounting->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounting.created_at')</dt>
                            <dd class="col-12">{{$mounting->created_at->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::mounting.date_trade')</dt>
                            <dd class="col-12">{{$mounting->date_trade->format('d.m.Y')}}</dd>
                            <dt class="col-12">@lang('site::mounting.date_mounting')</dt>
                            <dd class="col-12">{{$mounting->date_mounting->format('d.m.Y')}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounting.product_id')</dt>
                            <dd class="col-12">{{$mounting->product->name}}</dd>
                            <dt class="col-12">@lang('site::mounting.serial_id')</dt>
                            <dd class="col-12">
                            <dd class="col-12">
                                @if($mounting->serial_id)
                                    <div class="bg-light p-2">
				   @if($mounting->duplicates_serial->isNotEmpty())
                                        {{$mounting->serial_id}}
                                        <a href="{{route('admin.mountings.index', ['filter[search_act]' => $mounting->serial_id])}}">
                                         <span data-toggle="tooltip" data-placement="top" title="@lang('site::mounting.help.duplicates')" class="badge text-normal badge-pill badge-danger">
                                        <i class="fa fa-copy"></i> {{$mounting->duplicates_serial->count()}}
                                        </span></a>
                                        <br /><span class="bg-danger text-white px-2">@lang('site::mounting.error.serial_dup')</span>
                                @else
					{{$mounting->serial_id}}
				@endif
				</div>
                                    @if($mounting->serial()->exists())
                                        <div class="text-muted">{{$mounting->serial->product->name}}</div>
                                        <div class="text-muted">{{$mounting->serial->comment}}</div>
                                    @else
                                        <span class="bg-danger text-white px-2">@lang('site::serial.error.not_found')</span>
                                    @endif
                                @else
                                    <span class="bg-danger text-white px-2">@lang('site::serial.error.not_exist')</span>
                                @endif
                            </dd>
                            <dt class="col-12">@lang('site::mounting.source_id')</dt>
                            <dd class="col-12">
                                @if($mounting->source_id == 4)
                                    {{ $mounting->source_other }}
                                @else
                                    {{ $mounting->source->name }}
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounting.client')</dt>
                            <dd class="col-12">{{$mounting->client}}</dd>
                            <dt class="col-12">@lang('site::mounting.address')</dt>
                            <dd class="col-12">{{$mounting->address}}</dd>
	                    <dt class="col-12">@lang('site::repair.phone_primary')</dt>
                            <dd class="col-12">{{$mounting->phone_primary}}</dd>


			    @if($mounting->comment)
                                <dt class="col-12">@lang('site::mounting.comment')</dt>
                                <dd class="col-12">{{$mounting->comment}}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::mounting.bonus')</dt>
                            <dd class="col-12">
                                {{number_format($mounting->bonus, 0, '.', ' ')}}
                                {{ $mounting->user->currency->symbol_right }}
                            </dd>
                           <!-- 
                            <dt class="col-12">@lang('site::mounting.social_bonus')</dt>
                            <dd class="col-12">
                                {{number_format($mounting->enabled_social_bonus, 0, '.', ' ')}}
                                {{ $mounting->user->currency->symbol_right }}
                            </dd> 
                            @if($mounting->social_url)
                                <dt class="col-12">@lang('site::mounting.social_url')</dt>
                                <dd class="col-12"><a target="_blank" href="{{$mounting->social_url}}">{{$mounting->social_url}}</a></dd>
                            @endif
                            -->
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$mountings->render()}}
    </div>
@endsection
