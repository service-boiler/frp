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
            <li class="breadcrumb-item">
                <a href="{{ route('acts.index') }}">@lang('site::act.acts')</a>
            </li>
            <li class="breadcrumb-item active">№ {{$act->id}}</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::act.header.act') № {{$act->id}}</h1>
        @alert()@endalert()
        <div class=" border p-3 mb-2">
            <a href="{{ route('acts.edit', $act) }}"
               class="@cannot('update', $act) disabled @endcannot d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-ms">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit')</span>
            </a>
            <a href="{{ route('acts.pdf', $act) }}"
               class="@cannot('pdf', $act) disabled @endcannot d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-print"></i>
                <span>@lang('site::messages.print')</span>
            </a>
            <a href="{{ route('acts.index') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::act.help.back_list')</span>
            </a>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::act.header.info')</span>
                    </h6>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::act.created_at'):</span>&nbsp;
                            <span class="text-dark">{{$act->created_at->format('d.m.Y H:i' )}}</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::act.user.received'):</span>&nbsp;
                            <span class="text-dark">@bool(['bool' => $act->received])@endbool</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::act.user.paid'):</span>&nbsp;
                            <span class="text-dark">@bool(['bool' => $act->paid])@endbool</span>
                        </div>
                        <div class="mb-2">
                            <span class="text-muted">@lang('site::act.number'):</span>&nbsp;
                            <span class="text-dark">{{ $act->number }}</span>
                        </div>
                    </div>
                </div>
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::repair.header.payment')</span>
                    </h6>
                    <div class="card-body">
                        <dl class="row">
                            @include('site::act.show.'.$act->type->alias)
                        </dl>
                    </div>
                    <div class="card-footer">
                        <b>@lang('site::act.help.total')</b>: {{ Site::formatBack($act->total) }}
                    </div>
                </div>
            </div>
            <div class="col-xl-8">
                <div class="card mb-2">
                    <h6 class="card-header with-elements">
                        <span class="card-header-title">@lang('site::repair.repairs')</span>
                    </h6>
                    <div class="card-body">
                        @foreach($act->contents as $repair)
                            <div class="row border-bottom">
                                <div class="col"><a href="{{route($act->type->alias.'.show', $repair)}}">№ {{$repair->id}}</a></div>
                                <div class="col">{{$repair->created_at->format('d.m.Y')}}</div>
                                <div class="col">
                                    <a href="{{route('products.show', $repair->product)}}">{{$repair->product->name}}</a>
                                </div>
                                <div class="col">
                                    {{Site::formatBack($repair->total)}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card mb-2">
                    <h6 class="card-header">@lang('site::act.user.detail_1')</h6>
                    <div class="card-body">
                        @php $detail = $act->details()->whereOur(1)->first() @endphp
                        @include('site::admin.act.show.detail')
                    </div>
                </div>
                <div class="card mb-4">
                    <h6 class="card-header">@lang('site::act.user.detail_0')</h6>
                    <div class="card-body">
                        @php $detail = $act->details()->whereOur(0)->first() @endphp
                        @include('site::admin.act.show.detail')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
