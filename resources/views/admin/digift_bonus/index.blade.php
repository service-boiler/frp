@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::digift_bonus.digift_bonuses')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::digift_bonus.icon')"></i> @lang('site::digift_bonus.digift_bonuses')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
			<button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        <div class="row mb-2 text-center">
            <div class="col-sm-4">
                <div class="card text-white bg-success">
                    <div class="card-body p-2">
                        <h5 class="card-title">@lang('site::digift.total.bonuses')</h5>
                        <h4 class="card-text text-white">+ {{Site::format($bonuses)}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-danger">
                    <div class="card-body p-2">
                        <h5 class="card-title">@lang('site::digift.total.expenses')</h5>
                        <h4 class="card-text text-white">- {{Site::format($expenses)}}</h4>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card text-white bg-primary">
                    <div class="card-body p-2">
                        <h5 class="card-title">@lang('site::digift.total.diff')</h5>
                        <h4 class="card-text text-white">= {{Site::format($bonuses - $expenses)}}</h4>
                    </div>
                </div>
            </div>
        </div>
        @filter(['repository' => $repository])@endfilter
        {{--@pagination(['pagination' => $digiftBonuses])@endpagination--}}
        {{--{{$digiftBonuses->render()}}--}}

        @foreach($digiftBonuses as $digiftBonus)
            <div class="card my-2 @if($digiftBonus->blocked) bg-light @endif">

                <div class="row">

                    <div class="col-xl-2 col-sm-6">

                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{\Carbon\Carbon::createFromFormat('Y-m-d', $digiftBonus->created_at)->format('d.m.Y')}}</dd>
                        </dl>

                    </div>
                    <div class="col-xl-3 col-sm-6">

                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">
                                <span>@lang('site::digift_expense.'.$digiftBonus->operationType.'.title')</span>
                            </dt>
                            <dd class="col-12">
                                <span class="badge text-normal @if($digiftBonus->blocked) badge-light @else badge-@lang('site::digift_expense.'.$digiftBonus->operationType.'.class') @endif mr-3 ml-0">
                                    @lang('site::digift_expense.'.$digiftBonus->operationType.'.sign')
                                    {{$digiftBonus->operationValue}}
                                </span>
                            </dd>
                            @if($digiftBonus->blocked)
                            <dt class="col-12">
                                <span>@lang('site::digift_expense.decrease.title')</span>
                            </dt>
                            <dd class="col-12">
                                <span class="badge text-normal @if($digiftBonus->blocked) badge-light @else badge-@lang('site::digift_expense.'.$digiftBonus->operationType.'.class') @endif mr-3 ml-0">
                                    @lang('site::digift_expense.decrease.sign')
                                    {{$digiftBonus->operationValue}} (@lang('site::digift_bonus.rollback'))
                                </span>
                            </dd>
                            @endif
                        </dl>

                    </div>

                    <div class="col-xl-4 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">
                                @if($digiftBonus->bonusable_type=='mountings')
                                    <a href="{{route('admin.'.$digiftBonus->bonusable_type.'.show', $digiftBonus->bonusable_id)}}">
                                        @lang('site::digift_bonus.'.$digiftBonus->bonusable_type) № {{$digiftBonus->bonusable_id}}
                                    </a>
                                @elseif($digiftBonus->bonusable_type=='retailSaleReports')
                                <a href="{{route('admin.retail-sale-reports.show', $digiftBonus->bonusable_id)}}">
                                        @lang('site::digift_bonus.retail_sale_report') № {{$digiftBonus->bonusable_id}}
                                    </a>
                                @endif
                            </dt>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-sm-2 mt-0">
                            <dt class="col-12 text-sm-right text-left">@lang('site::mounting.header.user')</dt>
                            <dt class="col-12 text-sm-right text-left">
                                <a href="{{route('admin.users.show', $digiftBonus->user_id)}}">
                                    {{$digiftBonus->user_name}}
                                </a>
                            </dt>
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach

        {{--{{$digiftBonuses->render()}}--}}
    </div>
@endsection
