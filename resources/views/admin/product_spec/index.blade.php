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
            <li class="breadcrumb-item active">Атрибуты технических характеристик товаров</li>
        </ol>
        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-page d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.productspecs.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>Добавить атрибут</span>
            </a>
            <a href="{{ route('admin') }}" class="d-page d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $productspecs])@endpagination
        {{$productspecs->render()}}
        <div class="card mb-2">
            <div class="card-body">
                <table class="table table-sm ">
                    <thead>
                    <tr>
                        <th>Название атрибута</th>
                        <th>Название для сайта</th>
                        <th>Единицы измерения</th>
                        <th>Порядок сортировки</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($productspecs as $productspec)
                        <tr>
                            <td><a href="{{route('admin.productspecs.edit', $productspec)}}">{{$productspec->name}}</a></td>
                            <td>{{$productspec->name_for_site}}</td>
                            <td>{{ $productspec->unit }}</td>
                            <td>{{$productspec->sort_order}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{$productspecs->render()}}
    </div>
@endsection
