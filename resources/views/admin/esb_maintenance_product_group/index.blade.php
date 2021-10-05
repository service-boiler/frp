@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::product.esb_maintenance_groups')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::difficulty.icon')"></i> @lang('site::product.esb_maintenance_groups')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.esb-maintenance-product-groups.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::product.esb_maintenance_group')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbMaintenanceProductGroups])@endpagination
        {{$esbMaintenanceProductGroups->render()}}
        <div class="row items-row-view" data-target="{{route('admin.esb-maintenance-product-groups.sort')}}" id="sort-list">
            @each('site::admin.esb_maintenance_product_group.index.row', $esbMaintenanceProductGroups, 'esbMaintenanceProductGroup')
        </div>
        {{$esbMaintenanceProductGroups->render()}}
        <div class="card">
        <div class="card-body">
            Группы стоимости ТО указываются для каждого товара. Итоговая рекомендуемая стоимость ТО выводится конеченому потребителю и сервиному центру умноженная на коэффициет, 
            указанный для основного региона пользователя. Региональный коэффициент указывается в справочнике "Регионы". В том же справочнике указывается коэффициент для транспортных расходов.
            <br />Если для региона не указан коэффициент, то принимается, что он равен 1.
        </div>
        </div>
    </div>
@endsection
