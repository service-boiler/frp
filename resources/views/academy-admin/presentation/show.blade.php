@extends('layouts.app')

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
                <a href="{{ route('academy-admin.presentations.index') }}">@lang('site::academy.presentation.index')</a>
            </li>
            <li class="breadcrumb-item active">{{ $presentation->name }}</li>
        </ol>
        <h1 class="header-title mb-4">{{ $presentation->name }}</h1>

         @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">

            <a href="{{ route('academy-admin.presentations.index') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a>
            <a class="btn btn-ms d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.presentations.edit', $presentation) }}"
               role="button">
                <i class="fa fa-pencil"></i>
                <span>@lang('site::messages.edit') @lang('site::academy.presentation.add')</span>
            </a>
            <a class="btn btn-primary d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('academy-admin.presentations.preview', ['presentation' => $presentation, 'slide_num' => '0']) }}"
               role="button">
                <i class="fa fa-play-circle-o"></i>
                <span>@lang('site::academy.presentation.preview')</span>
            </a>
                            
                            
            <a class="btn btn-danger btn-row-delete 
                @cannot('delete', $presentation)
               disabled
               @endcannot
               "
               title="@lang('site::messages.delete')"
               href="javascript:void(0);" 
               
               @can('delete', $presentation) 
               data-form="#presentation-delete-form-{{$presentation->id}}"
               data-btn-delete="@lang('site::messages.delete')"
               data-btn-cancel="@lang('site::messages.cancel')"
               data-label="@lang('site::messages.delete_confirm')"
               data-message="@lang('site::messages.delete_sure') {{ $presentation->name }}?"
               data-toggle="modal"
               data-target="#form-modal"
                               
                                @endcan>
                <i class="fa fa-close"></i>
                <span class="d-none d-sm-inline-block" >@lang('site::messages.delete')</span>
            </a>
            @cannot('delete', $presentation)  
             <i class="fa fa-question-circle-o" data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::academy.presentation.delete_cannot')" ></i>
             
             @endcannot            

            

        </div>
        <form id="presentation-delete-form-{{$presentation->id}}"
                action="{{route('academy-admin.presentations.destroy', $presentation)}}"
                method="POST">
             @csrf
             @method('DELETE')
        </form>
    
        <div class="card mt-4">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-2 text-left text-sm-right">@lang('site::academy.presentation.annotation'):</dt>
                    <dd class="col-sm-10">{!!$presentation->annotation!!}</dd>
                    <dt class="col-sm-2 text-left text-sm-right">@lang('site::academy.presentation.text'):</dt>
                    <dd class="col-sm-10">{!!$presentation->text!!}</dd>
    
                </dl>
                <div class="row">
                <div class="col-12">
                <ul class="list-group inline" data-target="{{route('academy-admin.presentation_slides.sort')}}" id="sort-list">    
                @foreach($presentation->slides as $val => $slide)
                 
                    
                        <li class="mt-1 sort-item list-group-item" data-id="{{$slide->id}}">
                                    <i class="fa fa-arrows"></i> {{$val+1}}
                               @if(!empty($slide->image))
                                    <a class="dynamic-modal-form-card-lg" href="javascript:void(0);" data-form-action="{{route('academy-admin.presentation_slides.card',$slide)}}"
                                    data-label="{{$slide->name}}" >
                                    <image style="height:50px" src="{{$slide->image->src()}}"></a>
                                    @endif
                           
                                    <a class="dynamic-modal-form-card-lg" href="javascript:void(0);" data-form-action="{{route('academy-admin.presentation_slides.card',$slide)}}"
                                    data-label="{{$slide->name}}" >{{$slide->name}}</a>
                                    
                                    @if(!empty($slide->sound))
                                    <div class="mt-3" style="height:30px;">
                                        <audio controls id="sound">
                                                <source src="{{$slide->sound->src()}}"  type="audio/mpeg">
                                        </audio>
                                    </div>
                                    @endif
                                    
                                  
                            
                       </li>
                       
                        
                @endforeach
                </ul>
                </div>
                </div>
            </div>
        </div>
        <div class="card mb-4 mt-2">
            <div class="card-body">
                
                <div class="row">
                    <div class="col">
                    Привязка к этапам:
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                    @foreach($presentation->stages as $stage)
                        <p>
                            <a href="{{route('academy-admin.stages.show',$stage)}}">{{$stage->name}}</a>
                        </p>
                    @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection
