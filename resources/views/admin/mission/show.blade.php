@extends('layouts.app')
@section('title') @lang('site::admin.mission.mission')  @endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.missions.index') }}">@lang('site::admin.mission.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::admin.mission.mission') №{{$mission->id}}</li>
    </ol>
  
    @alert()@endalert
    <div class=" border p-2 mb-3">
         @can('edit', $mission) 
         
         <a href="{{ route('admin.missions.edit',$mission) }}" class="d-block d-sm-inline btn btn-secondary p-2 mr-3  mr-sm-3 ">
            <i class="fa fa-pencil"></i>
            <span>@lang('site::messages.edit')</span>
        </a>
        @endcan
        @foreach($statuses as $key => $status)
            <button type="submit"
                    name="status_id"
                    value="{{$status->id}}"
                    form="mission_status"
                    style="background-color: {{$status->color}};color:white"
                    class="d-block d-sm-inline mr-0 mb-2 mb-sm-2  btn mt-2">
                <i class="fa fa-{{$status->icon}}"></i>
                <span>{{$status->button}}</span>
            </button>
        @endforeach
       <!-- <a href="{{ route('admin.missions.pdf', $mission) }}"
            class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-primary">
                <i class="fa fa-print"></i>
                <span>@lang('site::messages.print')</span>
        </a> -->
        <button
            @cannot('delete', $mission) disabled @endcannot
            class="btn btn-danger btn-row-delete"
                    data-form="#mission-delete-form-{{$mission->id}}"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::admin.mission.mission')? "
                    data-toggle="modal" data-target="#form-modal"
                    href="javascript:void(0);" title="@lang('site::messages.delete')">
                <i class="fa fa-close" ></i>
                @lang('site::messages.delete')
        </button>
        
        <form id="mission-delete-form-{{$mission->id}}"
              action="{{route('admin.missions.destroy', $mission)}}"
              method="POST">
            @csrf
            @method('DELETE')
        </form>
                        <form id="mission_status"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.missions.status', $mission) }}">
                    @csrf
                    @method('PUT')
                    </form>
    </div>
        <div class="card mb-2">
            <div class="card-body pb-4">
                <dl class="row mb-0"> 
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.status')</dt>
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
                    <dd class="col-sm-9">{{$mission->target}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.mission.result')</dt>
                    <dd class="col-sm-9">
            
            <div class="form-row">
                        <div class="col mb-3">
                            <textarea
                                  form="mission_status"
                                  name="mission[result]"
                                  id="result"
                                  class="form-control{{ $errors->has('mission.result') ? ' is-invalid' : '' }}"
                                  >{{ old('mission.result',$mission->result) }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('mission.result') }}</span>
                            <button type="submit"
                                    name="status_id"
                                    value="{{$mission->status->id}}"
                                    form="mission_status"
                                    style="background-color: {{$mission->status->color}};color:white"
                                    class="d-block d-sm-inline mr-0 mb-2 mb-sm-2 btn">
                                <i class="fa fa-check"></i>
                                <span>Сохранить</span>
                            </button>
                        </div>
            </div>  
            
            </dd>
                
                </dl>   
            </div>
            
        </div>

        <div class="card mb-2">
            <div class="card-body pb-0">
                <h5 class="card-title">Прикрепленные файлы</h5>
                @include('site::file.files')
                <br />
                <span class="text-success">Для добавления файлов нажмите кнопку "Изменить" вверху.</span>
            </div>
        </div>
        

           

</div>
@endsection
