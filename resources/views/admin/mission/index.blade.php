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
                
                <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
</div>
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $missions])@endpagination
        {{$missions->render()}}
        @for($i=-1;$i<5;$i++)
            @if($missions->where('date_from','>=',date("Y-m-", strtotime("+$i month")).'01')->count()>0)
                <h4 class="mt-4 p-0 mb-0"> @lang('site::messages.months.' .date("m", strtotime("+$i month"))) </h4>
            @endif
                @foreach($users as $user)
                @if($user->missionsMonth(date("m", strtotime("+$i month")),$missions)->count()>0)
                <hr class="mt-2 mb-0">
                <h5 class="mt-1 p-0 mb-1">{{$user->name}}</h5>

                    @foreach($user->missionsMonth(date("m", strtotime("+$i month")),$missions)->get() as $mission)
                            <div class="card mb-2" id="mission-{{$mission->id}}">
                                    <div class="card-header py-1 with-elements">
                                        <div class="card-header-elements">
                                            <a href="{{route('admin.missions.show', $mission)}}" class="mr-3 text-big">
                                                № {{$mission->id}}  
                                            </a>
                                            @lang('site::admin.mission.date_from'): <b>{{!empty($mission->date_from) ? $mission->date_from->format('d.m.Y'): 'не задана'}} </b>
                                            &nbsp;&nbsp;&nbsp;@lang('site::admin.mission.date_to'): <b>{{!empty($mission->date_to) ? $mission->date_to->format('d.m.Y'): 'не задана'}} 
                                           &nbsp;&nbsp;&nbsp; </b>
                                        </div>
                                        <div class="card-header-elements ml-md-auto">
                                        <b> @if(!empty($mission->region)){{$mission->region->name}}@endif,
                                            {{!empty($mission->locality) ? $mission->locality : ''}}</b>
                                            <span class="badge text-normal badge-pill text-white"
                                                  style="background-color: {{ $mission->status->color }}">
                                                <i class="fa fa-{{ $mission->status->icon }}"></i> {{ $mission->status->name }}
                                            </span>
                                        
                                        
                                        </div>
                                            

                                    </div>

                                
                                <div class="row">
                                    <div class="col-sm-3 pt-2 pl-4">
                                    
                                                <ul class="list-group">
                                                    @foreach($mission->users()->get() as $muser)
                                                        <li class="list-group-item border-0 px-0 py-1">
                                                            {!!$muser->name!!}
                                                            @if($muser->pivot->main)<span class="d-inline text-normal text-success"><i class="fa fa-flag"></i></span>@endif
                                                            </li>
                                                        @if($mission->users()->count() > 3 && $loop->iteration == 3)
                                                            @break
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                @if($mission->users()->count() > 3)
                                                    <ul class="list-group collapse" id="collapse-mission-{{$mission->id}}">
                                                        @foreach($mission->users()->get() as $muser)
                                                            @if($loop->iteration > 3)
                                                                <li class="list-group-item border-0 px-0 py-0">
                                                                    
                                                                        {!!$muser->name!!}@if($muser->pivot->main)<span class="d-inline text-normal text-success"><i class="fa fa-flag"></i></span>@endif
                                                                    
                                                                    
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
                                            
                                    </div>
                                    
                                    <div class="col-sm-4 pt-2">
                                        <b>Цель: </b>{{$mission->target}}
                                    </div>
                                    <div class="col-sm-5 pt-2">
                                        <b>Результат: </b>{{$mission->result}}
                                    </div>
                                    
                                </div>
                            </div>
                       
                        @endforeach
                <hr class="mb-5">
               @endif
                @endforeach
        @endfor
        
       
        {{$missions->render()}}
    </div>
@endsection
