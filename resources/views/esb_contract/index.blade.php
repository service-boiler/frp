@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::contract.contracts')</li>
        </ol>
        @alert()@endalert()

        <div class=" border p-3 mb-2">

            <a href="{{ route('home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_home')</span>
            </a>
        </div>

        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbContracts])@endpagination
        {{$esbContracts->render()}}
        @foreach($esbContracts as $contract)
            <div class="card my-4" id="contract-{{$contract->id}}">
                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <a class="btn btn-success py-1" href="{{route('esb-contracts.show', $contract)}}" class="mr-2 ml-0 text-big">
                            Договор № {{$contract->number}} </a>

                    </div>
                    <div class="card-header-elements ml-md-auto">
                        @if(auth()->user()->hasRole(config('site.supervisor_roles'),[]))
                            <a href="{{route('admin.esb-clients.show',$contract->esbUser)}}">
                                <i class="fa fa-external-link"></i> &nbsp;&nbsp;<b>{{$contract->esbUser->name_filtred}}</b></a> &nbsp;/
                            @endif
                            {{optional($contract->service)->name_short}}
                    </div>
                </div>

                <div class="row">
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::messages.created_at')</dt>
                            <dd class="col-12">{{$contract->created_at->format('d.m.Y H:i')}}</dd>
                            <dt class="col-12">@lang('site::contract.date')</dt>
                            <dd class="col-12">{{optional($contract->date)->format('d.m.Y') ?: '---'}}</dd>
                        </dl>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Срок действия с:</dt>
                            <dd class="col-12">{{optional($contract->date_from)->format('d.m.Y') ?: '---'}}</dd>
                            <dt class="col-12">Срок действия по:</dt>
                            <dd class="col-12">{{optional($contract->date_to)->format('d.m.Y') ?: '---'}}</dd>

                        </dl>
                    </div>
                    <div class="col-md-8">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">Адрес:</dt>
                            <dd class="col-12">{{optional($contract->esbUser->addresses->first())->full ?: '---'}}</dd>

                            <dt class="col-12">Шаблон:</dt>
                            <dd class="col-12"><span class="bg-light px-2">{{ optional($contract->template)->name ?: '---' }}</span></dd>


                        </dl>
                    </div>


                </div>
            </div>
        @endforeach
        {{$esbContracts->render()}}

    </div>
@endsection
