@extends('layouts.app')
@section('title') Тендеры @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::tender.tenders')</li>
        </ol>

        @alert()@endalert()
        <div class="p-2 mb-3">
        <a href="{{route('tenders.create')}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1  btn btn-ms p-2">
                    <i class="fa fa-pencil"></i>
                    <span>Новая тендерная заявка</span>
                </a>
                
            {{--
                <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            --}}
</div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $tenders])@endpagination
        {{$tenders->render()}}

        
        @foreach($tenders as $tender)
            <div class="card my-4" id="tender-{{$tender->id}}">

                <div class="card-header with-elements">

                    <div class="card-header-elements">
                        <a href="{{route('tenders.show', $tender)}}" class="mr-3 text-big">
                            @lang('site::tender.tender') № {{$tender->id}}
                        </a>
                        <span class="badge text-normal badge-pill text-white" style="background-color: {{ $tender->status->color }}">
                            <i class="fa fa-{{ $tender->status->icon }}"></i> {{ $tender->status->name }} @if(in_array($tender->status->id,config('site.tender_sub_head_statuses'))){{$tender->subHead()->name}}@endif
                        </span>
                        

                    </div>

                    <div class="card-header-elements ml-md-auto">

                            {{$tender->user->name}}

                        @if( $tender->messages()->exists())
                            <span class="badge badge-secondary text-normal badge-pill">
                                <i class="fa fa-comment"></i> {{ $tender->messages()->count() }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$tender->created_at->format('d.m.Y H:i')}}</dd>
                            @if($tender->approved)
                            <dt class="col-12">@lang('site::tender.director_approved_date_short')</dt>
                            <dd class="col-12">{{$tender->director_approved_date->format('d.m.Y H:i')}}</dd>
                            @endif
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::tender.products') ({{$tender->tenderProducts()->count()}})</dt>
                            <dd class="col-12">
                                <ul class="list-group">
                                    @foreach($tender->tenderProducts()->with('product')->get() as $item)
                                        <li class="list-group-item btender-0 px-0 py-1">
                                            <a href="{{route('products.show', $item->product)}}">
                                                {!!$item->product->name!!} 
                                            </a>

                                            x {{$item->count}} {{$item->product->unit}}  Скидка: <span style="background-color:@lang('site::tender.tender_price_color.' .$item->approved_status); font-weight: 600;">
                                                {{$item->discount}}% (€ {{ round($item->product->retailPriceEur->valueRaw*(100 - $item->discount)/100,2)}})</span>
                                        </li>
                                        @if($tender->tenderProducts()->count() > 3 && $loop->iteration == 3)
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                                @if($tender->tenderProducts()->count() > 3)
                                    <ul class="list-group collapse" id="collapse-tender-{{$tender->id}}">
                                        @foreach($tender->tenderProducts()->with('product')->get() as $item)
                                            @if($loop->iteration > 3)
                                                <li class="list-group-item btender-0 px-0 py-1">
                                                    <a href="{{route('products.show', $item->product)}}">
                                                        {!!$item->product->name!!}
                                                    </a>
                                                    x {{$item->count}} {{$item->product->unit}}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <p class="mt-2">
                                        <a class="btn py-0 btn-sm btn-ms"
                                           data-toggle="collapse"
                                           href="#collapse-tender-{{$tender->id}}"
                                           role="button"
                                           aria-expanded="false"
                                           aria-controls="collapseExample">
                                            <i class="fa fa-chevron-down"></i>
                                            @lang('site::messages.show')
                                            @lang('site::messages.more')
                                            @if($tender->tenderProducts()){{$tender->tenderProducts()->count() - 3}}...@endif
                                        </a>
                                    </p>
                                @endif
                            </dd>
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::tender.distributor_id')</dt>
                            <dd class="col-12">{{$tender->distributor->name}}</a></dd>
                            <dt class="col-12">@lang('site::tender.region_id'), @lang('site::tender.city'), @lang('site::tender.address_name')</dt>
                            <dd class="col-12">{{$tender->region->name }}, {{$tender->city}}, {{$tender->address_name}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::tender.planned_purchase_date')</dt>
                            <dd class="col-12">
                                {{$tender->planned_purchase_year}} @lang('site::messages.months_cl.' .$tender->planned_purchase_month)
                            </dd>
                        </dl>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-12 col-sm-12">
                    <dt class="col-2">@lang('site::tender.result'):</dt>
                    <dd class="col-8">{!! $tender->result !!}</dd>
                    </div>
                </div>
            </div>
        @endforeach
        {{$tenders->render()}}
    </div>
@endsection
