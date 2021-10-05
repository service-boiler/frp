@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.esb_user.index')</li>
        </ol>

        @alert()@endalert()
        {{--<div class="card mb-2">
            <div class="card-body">
                <a href="{{route('esb-users.create')}}" class="btn btn-ms"><i class="fa fa-plus"></i> @lang('site::user.esb_user.add')</a>
            </div>
        </div>--}}
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $users])@endpagination
        {{$users->render()}}
        @foreach($users as $user)
            <div class="card my-4" id="mounter-{{$user->id}}">

                <div class="card-header with-elements">
                    <div class="card-header-elements">

                        <a href="{{route('esb-users.show', $user)}}" class="mr-3 ml-0">
                            {{$user->name}}
                        </a>
                            {{$user->phone}}
                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-7">
                        <dl class="row mt-2 ml-2">
                            @foreach($user->esbProducts->where('enabled',1) as $product)
                            <dt class="col-3">
                                {{$product->product->name}} 
                            </dt>
                            <dd class="col-9">
                                {{$product->address->locality}}, {{$product->address->street}} 
                            </dd>
                            @endforeach
                                
                            
                            
                        </dl>
                    </div>
                </div>
            </div>
        @endforeach
        {{$users->render()}}
    </div>
@endsection
