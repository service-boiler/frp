@extends('layouts.app')
@section('title') @lang('site::admin.revision_part.revision_part')  @endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.revision_parts.index') }}">@lang('site::admin.revision_part.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::admin.revision_part.revision_part') №{{$revisionPart->id}}</li>
    </ol>
  
    @alert()@endalert
    <div class=" border p-2 mb-3">
         
         <a href="{{ route('admin.revision_parts.edit',$revisionPart) }}" class="d-block d-sm-inline btn btn-secondary p-2 mr-3  mr-sm-3 ">
            <i class="fa fa-pencil"></i>
            <span>@lang('site::messages.edit')</span>
        </a>
        <button
                    
            class="btn btn-ms btn-row-delete"
                    data-form="#send_notice"
                    data-btn-delete="Отправить"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="Подтверждение отправки"
                    data-message="Отправить всем АСЦ в ЛК? "
                    data-toggle="modal" data-target="#form-modal"
                    href="javascript:void(0);" title="Отправить всем АСЦ в ЛК">
                <i class="fa fa-envelope"></i>
            <span>Отправить всем АСЦ в ЛК</span>
        </button>
        <a href="{{ route('admin.revision_parts.pdf', $revisionPart) }}"
            class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-primary">
                <i class="fa fa-print"></i>
                <span>@lang('site::messages.print')</span>
        </a>
        <button
                    
            class="btn btn-danger btn-row-delete"
                    data-form="#revision_part-delete-form-{{$revisionPart->id}}"
                    data-btn-delete="@lang('site::messages.delete')"
                    data-btn-cancel="@lang('site::messages.cancel')"
                    data-label="@lang('site::messages.delete_confirm')"
                    data-message="@lang('site::messages.delete_sure') @lang('site::admin.revision_part.revision_part')? "
                    data-toggle="modal" data-target="#form-modal"
                    href="javascript:void(0);" title="@lang('site::messages.delete')">
                <i class="fa fa-close" ></i>
                @lang('site::messages.delete')
        </button>
        
         
        <form id="send_notice"
                  method="POST"
                  
                  action="{{ route('admin.revision_parts.notice', $revisionPart) }}">    
         
                @csrf
                @method('POST')         
        </form>
    
        <form id="revision_part-delete-form-{{$revisionPart->id}}"
              action="{{route('admin.revision_parts.destroy', $revisionPart)}}"
              method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>
    
<div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0">

                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.part_old_sku')</dt>
                    <dd class="col-sm-9">{{$revisionPart->part_old_sku}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.part_old_name')</dt>
                    <dd class="col-sm-9">@if(!empty($revisionPart->partOld)){{$revisionPart->partOld->name}}</dd>
                    <dt class="col-sm-3 text-left text-sm-right"></dt>
                    <dd class="col-sm-9">
                    <img class="d-block" style="max-height: 200px;"
                                         src="{{ $revisionPart->partOld->images()->exists() ? $revisionPart->partOld->images->first()->src() : '' }}"
                                         alt="">@endif</dd>
                    
                    </dl>   
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0">            
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.part_new_sku')</dt>
                    <dd class="col-sm-9">{{$revisionPart->part_new_sku}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.part_new_name')</dt>
                    <dd class="col-sm-9">{{$revisionPart->partNew->name}}</dd>
                    <dt class="col-sm-3 text-left text-sm-right"></dt>
                    <dd class="col-sm-9">
                    <img class="d-block" style="max-height: 200px;"
                                         src="{{ $revisionPart->partNew->images()->exists() ? $revisionPart->partNew->images->first()->src() : ''}}"
                                         alt=""></dd>
                    </dl>   
            </div>
        </div>


        <div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0"> 
                   <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.interchange')</dt>
                    <dd class="col-sm-9"><span class="d-block text-normal @if($revisionPart->interchange) text-success @else text-danger @endif">
                                @lang('site::admin.revision_part.interchange_'.($revisionPart->interchange))
                            </span></dd>
                            
                   <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.enabled')</dt>
                    <dd class="col-sm-9"><span class="d-block text-normal @if($revisionPart->enabled) text-success @else text-danger @endif">
                                @lang('site::admin.revision_part.enabled_'.($revisionPart->enabled))
                            </span></dd>
                           
                   <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.public')</dt>
                    <dd class="col-sm-9"><span class="d-block text-normal @if($revisionPart->public) text-success @else text-danger @endif">
                                @lang('site::admin.revision_part.public_'.($revisionPart->public))
                            </span></dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.date_change')</dt>
                    <dd class="col-sm-9">{{$revisionPart->date_change->format('Y-m-d')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.date_notice')</dt>
                    <dd class="col-sm-9">{{$revisionPart->date_notice->format('Y-m-d')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.text_object')</dt>
                    <dd class="col-sm-9">{{$revisionPart->text_object}}</dd>
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.description')</dt>
                    <dd class="col-sm-9">{{$revisionPart->description}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.comments') (для внутреннего пользования)</dt>
                    <dd class="col-sm-9">{{$revisionPart->comments}}</dd>
                    
                </dl>   
            </div>
        </div>

        <div class="card mb-2">
            <div class="card-body pb-0">
                <h5 class="card-title">Прикрепленные файлы (для внутреннего пользования)</h5>
                @include('site::file.files')
            </div>
        </div>
        
        <div class="card mb-2">
            <div class="card-body pb-0">
            <h3>@lang('site::admin.revision_part.products')</h3>
                @foreach($revisionPart->products as $product)
                
                    <dl class="row mb-3"> 
                       
                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.product_id')</dt>
                        <dd class="col-sm-9">{{$product->name}}</dd>
                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.start_serial')</dt>
                        <dd class="col-sm-9">{{$product->pivot->start_serial}}</dd>
                        
                    </dl> 
                @endforeach                
            </div>
        </div>


           

</div>
@endsection
