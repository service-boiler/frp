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
            <li class="breadcrumb-item active">@lang('site::admin.black_list.black_list')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::admin.black_list.icon')"></i> @lang('site::admin.black_list.black_list')</h1>

        @alert()@endalert

        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-page d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.black-list.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::admin.black_list.address')</span>
            </a>
            <a href="{{ route('admin') }}" class="d-page d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $blackList])@endpagination
        {{$blackList->render()}}
        <div class="card mb-2">
            <div class="card-body">
                <table class="table table-sm ">
                    <thead>
                    <tr>
                        <th>@lang('site::admin.black_list.web')</th>
                        <th>@lang('site::admin.black_list.name')</th>
                        <th>@lang('site::admin.black_list.full')</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($blackList as $address)
                        <tr>
                            <td>@bool(['bool' => $address->active])@endbool<a href="{{route('admin.black-list.edit', $address)}}">{{$address->web}}</a></td>
                            <td>{{$address->name}}</td>
                            <td>{!! $address->full !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{$blackList->render()}}
    </div>
@endsection
