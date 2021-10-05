@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::product.esb_maintenance_distances')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::difficulty.icon')"></i> @lang('site::product.esb_maintenance_distances')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.esb-maintenance-distances.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::product.esb_maintenance_distance')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbMaintenanceDistances])@endpagination
        {{$esbMaintenanceDistances->render()}}
        <div class="row items-row-view" data-target="{{route('admin.esb-maintenance-product-groups.sort')}}" id="sort-list">
            @each('site::admin.esb_maintenance_distance.index.row', $esbMaintenanceDistances, 'esbMaintenanceDistance')
        </div>
        {{$esbMaintenanceDistances->render()}}
        <div class="card">
        <div class="card-body">
            Итоговая рекомендуемая стоимость транспортных расходов для ТО выводится конеченому потребителю и сервиному центру умноженная на коэффициент, 
            указанный для основного региона пользователя. Региональный коэффициент указывается в справочнике "Регионы". В том же справочнике указывается коэффициент на ТО.
        </div>
        </div>
    </div>
@endsection
