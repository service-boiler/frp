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
            <li class="breadcrumb-item active">Клиенты и конечные потребители</li>
        </ol>

        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
            <a href="{{ route('admin.esb-clients.create') }}" class="d-block ml-sm-3 d-sm-inline btn btn-primary">
                <i class="fa fa-plus"></i>
                <span>Создать клиента</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $clients])@endpagination
        {{$clients->render()}}
        @foreach($clients as $client)
            <div class="card my-2" id="engineer-{{$client->id}}">

                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Имя</dt>
                            <dd class="col-12">
                                <a href="{{route('admin.esb-clients.show', $client)}}" class="mr-1 text-big ml-0">
                                    {{$client->name}}

                                </a>
                                
                            </dd>

                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        @if($client->addresses()->exists())
                            <dl class="dl-horizontal mt-0 mt-sm-2">
                                <dt class="col-12">@lang('site::engineer.address')</dt>
                                <dd class="col-12">{{$client->addresses()->first()->full}}</dd>
                            </dl>
                        @endif
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-0 mt-sm-2">
                            <dt class="col-12">@lang('site::engineer.phone')</dt>
                            <dd class="col-12">
                                {{ $client->phone_formated }}
                            </dd>
                        </dl>
                    </div>

                </div>
            </div>
        @endforeach
        {{$clients->render()}}

    </div>
@endsection
