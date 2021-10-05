@extends('layouts.app')
@section('title') @lang('site::admin.revision_part.revision_part')  @endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('revision_parts.index') }}">@lang('site::admin.revision_part.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::admin.revision_part.revision_part') №{{$revisionPart->id}}</li>
    </ol>
  
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
                                         src="{{ $revisionPart->partOld->images->first()->src() }}"
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
                                         src="{{ $revisionPart->partNew->images->first()->src() }}"
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
                            
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.date_change')</dt>
                    <dd class="col-sm-9">{{$revisionPart->date_change->format('Y-m-d')}}</dd>
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.text_object')</dt>
                    <dd class="col-sm-9">{{$revisionPart->text_object}}</dd>
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.revision_part.description')</dt>
                    <dd class="col-sm-9">{{$revisionPart->description}}</dd>
                    
                </dl>   
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
