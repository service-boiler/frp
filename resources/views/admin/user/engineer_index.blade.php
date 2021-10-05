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
            <li class="breadcrumb-item active">Инженеры (сотрудники и партнеры)</li>
        </ol>

        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">
            <a href="{{ route('admin') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
            <a href="{{ route('admin.engineers.create') }}" class="d-block ml-sm-3 d-sm-inline btn btn-primary">
                <i class="fa fa-plus"></i>
                <span>Создать инженера</span>
            </a>
        </div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $engineers])@endpagination
        {{$engineers->render()}}
        @foreach($engineers as $engineer)
            <div class="card my-2" id="engineer-{{$engineer->id}}">

                <div class="row">
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::engineer.name')</dt>
                            <dd class="col-12">
                                <a href="{{route('admin.engineers.show', $engineer)}}" class="mr-1 text-big ml-0">
                                    {{$engineer->name}}

                                </a>
                                
                            </dd>

                        </dl>
                    </div>
                    <div class="col-xl-2 col-sm-6">
                        <dl class="dl-horizontal mt-0 mt-sm-2">
                            <dt class="col-12">@lang('site::engineer.phone')</dt>
                            <dd class="col-12">
                                {{ $engineer->phone_formated }}
                            </dd>
                        </dl>
                    </div>
                    <div class="col-xl-3 col-sm-6">
                        <dl class="dl-horizontal mt-0 mt-sm-2">
                            <dt class="col-12">Сертификаты</dt>

                            @if($engineer->certificates()->exists())

                                    @foreach($engineer->certificates()->with('type')->get() as $certificate)
                                          <dd class="col-12">{{$certificate->id}} &nbsp; {{$certificate->type->name}}</dd>
                                    @endforeach

                                @endif
                        </dl>
                    </div>
                    <div class="col-xl-4 col-sm-6">
                        @if($engineer->addresses()->exists())
                            <dl class="dl-horizontal mt-0 mt-sm-2">
                                <dt class="col-12">@lang('site::engineer.address')</dt>
                                <dd class="col-12">{{$engineer->addresses()->first()->full}}</dd>
                            </dl>
                        @endif
                            <span class="mt-1 d-block text-normal @if($engineer->active) text-success @else text-danger @endif">
                                @lang('site::user.active_'.($engineer->active))
                            </span>
                    </div>

                </div>
            </div>
        @endforeach
        {{$engineers->render()}}

    </div>
@endsection
