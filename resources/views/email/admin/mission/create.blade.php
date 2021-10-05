@extends('layouts.email')

@section('title')
    '@lang('site::admin.mission.mission')'
@endsection
@section('h1')
    <a class="btn btn-ms" href="{{ route('admin.missions.show', $mission) }}">
            <b>{{ route('admin.missions.show', $mission) }}</b></a>
@endsection

@section('body')
    <p><b>@lang('site::admin.mission.mission') №</b> {{$mission->id }}</p>
    
    <p>  <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.status')</dt>
                    <dd class="col-sm-9"><span class="badge text-normal badge-pill text-white"
                              style="background-color: {{ $mission->status->color }}">
                            <i class="fa fa-{{ $mission->status->icon }}"></i> {{ $mission->status->name }}
                        </span></dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right font-weight-bold">@lang('site::admin.mission.users'):</dt>
                    
                    <dd class="col-sm-9 font-weight-bold">  @foreach($mission->users as $user) {{$user->name}} 
                    @if($user->pivot->main)
                    <span class="d-inline text-normal text-success"><i class="fa fa-flag"></i></span>&nbsp;&nbsp;&nbsp;
                    @endif @endforeach     
                    </dd>
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.date_from')</dt>
                    <dd class="col-sm-9">{{$mission->date_from->format('Y-m-d')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.date_to')</dt>
                    <dd class="col-sm-9">{{$mission->date_to->format('Y-m-d')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::region.region')</dt>
                    <dd class="col-sm-9">@if(!empty($mission->region)){{$mission->region->name}}@endif</dd>
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::address.locality')</dt>
                    <dd class="col-sm-9">{{!empty($mission->locality) ? $mission->locality : 'не указан'}}</dd>
                    
                        
                    
                    
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.comments')</dt>
                    <dd class="col-sm-9">{{$mission->comments}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.division')</dt>
                    <dd class="col-sm-9">{{$mission->division ? $mission->division->name : 'не задан'}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.project')</dt>
                    <dd class="col-sm-9">{{$mission->project ? $mission->project->name : 'не задан'}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.budget')</dt>
                    <dd class="col-sm-9">{{$mission->budget}} {{$mission->budgetCurrency->name}} @if($mission->budgetCurrency->id != 978)  (€ {{round( $mission->budget/$mission->budget_currency_eur_rate,0)}} по курсу {{$mission->budget_currency_eur_rate}}) @endif</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.target')</dt>
                    <dd class="col-sm-9">{{$mission->target}}</dd></p>
    
    
@endsection