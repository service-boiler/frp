@extends('layouts.app')
@section('title') @lang('site::admin.mission.index') @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.mission.index')</li>
        </ol>

        @alert()@endalert()
        <div class="p-2 mb-3">
        <a href="{{route('admin.missions.create')}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1  btn btn-ms p-2">
                    <i class="fa fa-plus"></i>
                    <span>@lang('site::admin.mission.add')</span>
                </a>
                <!--
                <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>-->
</div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $missions])@endpagination
        {{$missions->render()}}

        
        @foreach($missions as $mission)
            <div class="card my-4" id="mission-{{$mission->id}}">

                

                    <div class="card-header py-1 with-elements">
                        <div class="card-header-elements">
                        <a href="{{route('admin.missions.show', $mission)}}" class="mr-3 text-big">
                            № {{$mission->id}}  
                        </a>
                        </div>
                        <div class="card-header-elements ml-md-auto">
                         
                            <span class="badge text-normal badge-pill text-white"
                                  style="background-color: {{ $mission->status->color }}">
                                <i class="fa fa-{{ $mission->status->icon }}"></i> {{ $mission->status->name }}
                            </span>
                        
                        
                        </div>
                            

                    </div>

                
                <div class="row">
                    <div class="col-sm-2">
                    <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.mission.date_from'):</dt>
                            <dd class="col-12">{{!empty($mission->date_from) ? $mission->date_from->format('d.m.Y'): 'не задана'}} </dd>
                                                    
                            <dt class="col-12">@lang('site::admin.mission.date_to'):</dt>
                            <dd class="col-12">{{!empty($mission->date_to) ? $mission->date_to->format('d.m.Y'): 'не задана'}} </dd>
                            
                        
                        
                        </dl>
                    </div>
                    <div class="col-sm-3">
                    <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::region.region')</dt>
                            <dd class="col-12"> @if(!empty($mission->region)){{$mission->region->name}}@endif</dd>
                                                    
                            <dt class="col-12">@lang('site::address.locality')</dt>
                            <dd class="col-12"> {{!empty($mission->locality) ? $mission->locality : 'не указан'}}</dd>
                            
                        
                        
                        </dl>
                    </div>
                    
                    <div class="col-sm-7    ">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.mission.users')</dt>
                            <dd class="col-12">
                                <ul class="list-group">
                                    @foreach($mission->users()->get() as $user)
                                        <li class="list-group-item bmission-0 px-0 py-1">
                                            {!!$user->name!!}
                                            </li>
                                        @if($mission->users()->count() > 3 && $loop->iteration == 3)
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                                @if($mission->users()->count() > 3)
                                    <ul class="list-group collapse" id="collapse-mission-{{$mission->id}}">
                                        @foreach($mission->users()->get() as $user)
                                            @if($loop->iteration > 3)
                                                <li class="list-group-item bmission-0 px-0 py-1">
                                                    
                                                        {!!$user->name!!}
                                                    
                                                    
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <p class="mt-2">
                                        <a class="btn py-0 btn-sm btn-ms"
                                           data-toggle="collapse"
                                           href="#collapse-mission-{{$mission->id}}"
                                           role="button"
                                           aria-expanded="false"
                                           aria-controls="collapseExample">
                                            <i class="fa fa-chevron-down"></i>
                                            @lang('site::messages.show')
                                            @lang('site::messages.more')
                                            {{$mission->users()->count() - 3}}...
                                        </a>
                                    </p>
                                @endif
                            </dd>
                        </dl>
                    </div>
                    
                </div>
            </div>
        @endforeach
        {{$missions->render()}}
    </div>
@endsection
