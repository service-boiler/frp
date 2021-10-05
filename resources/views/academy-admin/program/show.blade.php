@extends('layouts.app')
@section('title'){{ $program->name }} @lang('site::academy.program.index')@lang('site::messages.title_separator')@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.programs.index') }}">@lang('site::academy.program.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $program->name }}</li>
        </ol>
      
         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.programs.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.programs.edit', $program) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.program.add')</span>
            </a>
            
            <button type="submit" form="program-delete-form-{{$program->id}}" 
                                @cannot('delete', $program) disabled 
                                data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.program_delete_cannot')"
                                @endcannot
								class="btn btn-danger d-block d-sm-inline @cannot('delete', $program) ssdisabled @endcannot">
								<i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span>
							</button>

        </div>
        <form id="program-delete-form-{{$program->id}}"
									action="{{route('academy-admin.programs.destroy', $program)}}"
									method="POST">
								 @csrf
								 @method('DELETE')
							</form>
    
        <div class="card mb-5">
            <div class="card-body">
                    <dl class="row">
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.enabled'):</dt>
                    <dd class="col-sm-9">@bool(['bool' => $program->enabled])@endbool</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.name_show'):</dt>
                    <dd class="col-sm-9">{!!$program->name!!}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.theme'):</dt>
                    <dd class="col-sm-9">@if(!empty($program->theme)){!!$program->theme->name!!}@endif</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.annotation'):</dt>
                    <dd class="col-sm-9">{!!$program->annotation!!}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::academy.program.bonuses'):</dt>
                    <dd class="col-sm-9">{!!$program->bonuses!!}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right mt-4">@lang('site::academy.program.stages'):</dt>
                    <dd class="col-sm-9 mt-4">
                        <ul class="list-group" data-target="{{route('academy-admin.programs.sort-stages')}}" id="sort-list">    
                            @foreach($stages as $stage)
                                <li class="sort-item list-group-item p-2" data-id="{{$stage->pivot->id}}">
                                    <i class="fa fa-arrows"></i>
                                    <div style="min-width: 250px; display: inline-block;">
                                        {{$stage->name}} 
                                        @if($stage->is_required) <i class="fa fa-check text-success"></i> @endif
                                        <a target="_blank" href="{{ route('academy-admin.stages.show',$stage) }}"><i class="fa fa-external-link"></i> </a>
                                        @if(!empty($stage->parentStage))(Обязателен {{$stage->parentStage->name}} )@endif
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    <span class="text-success mt-3">Для сортировки перетащите этап на нужную позицию.</span>
                    </dd>
                  
                    
                       
                </dl>
                
                
                </div>
            </div>
        </div>
    </div>
@endsection
