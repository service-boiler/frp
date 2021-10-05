@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_contract_type.index')</li>
        </ol>

        @alert()@endalert()
        <div class="card mb-2">
            <div class="card-body">
                <a href="{{route('esb-contract-types.create')}}" class="btn btn-ms"><i class="fa fa-plus"></i> @lang('site::user.esb_contract_type.add')</a>
            </div>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $esbContractTypes])@endpagination
        {{$esbContractTypes->render()}}
        @foreach($esbContractTypes as $esbContractType)
            <div class="card my-4" id="repair-{{$esbContractType->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">
                        <span class="badge text-normal badge-pill text-white"
                              style="background-color: {{ $esbContractType->color }}">
                           ID типа договра: {{$esbContractType->id}}
                        </span>
                        <a href="{{route('esb-contract-types.show', $esbContractType)}}" class="mx-3">
                            {{$esbContractType->name}}
                        </a>
                    </div>

                    <div class="card-header-elements ml-md-auto">
                        @lang('site::user.esb_contract_type.shared'): {{ $esbContractType->shared ? 'ДА' : 'НЕТ'}} , 
                        @lang('site::user.esb_contract_type.enabled'): {{ $esbContractType->enabled ? 'ДА' : 'НЕТ'}}
                        
                    </div>
                </div>
            </div>
        @endforeach
        {{$esbContractTypes->render()}}
    </div>
@endsection
