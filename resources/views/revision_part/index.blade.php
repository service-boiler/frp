@extends('layouts.app')
@section('title') @lang('site::admin.revision_part.index') @endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::admin.revision_part.index')</li>
        </ol>

        @alert()@endalert()
        
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $revisionParts])@endpagination
        {{$revisionParts->render()}}

        
        @foreach($revisionParts as $revisionPart)
            <div class="card my-4" id="revisionPart-{{$revisionPart->id}}">

                <div class="card-header">

                    <div class="row">
                        <div class="col-8">
                        <a href="{{route('revision_parts.show', $revisionPart)}}" class="mr-3 text-big">
                            № {{$revisionPart->id}}  {{$revisionPart->text_object}} 
                        </a>
                        </div>
                        <div class="col-4">
                        @lang('site::admin.revision_part.date_change'): {{$revisionPart->date_change->format('d.m.Y')}} 
                        </div>
                        
                            

                    </div>

                </div>
                <div class="row">
                    <div class="col-sm-2 pl-5">
                   <img style="max-width: 100px;" src="{{$revisionPart->partNew->images()->first()->src()}}" />
                    </div>
                    <div class="col-sm-4">
                    <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.revision_part.part_old_name')</dt>
                            <dd class="col-12">{{$revisionPart->part_old_sku}} @if(!empty($revisionPart->partOld)){{$revisionPart->partOld->name}}@endif</dd>
                            
                        
                        <dt class="col-12">@lang('site::admin.revision_part.part_new_name')</dt>
                            <dd class="col-12">{{$revisionPart->part_new_sku}} @if(!empty($revisionPart->partNew)){{$revisionPart->partNew->name}}@endif</dd>
                            
                        </dl>
                    </div>
                    
                    <div class="col-sm-6">
                        <dl class="dl-horizontal mt-2">
                            <dt class="col-12">@lang('site::admin.revision_part.products') ({{$revisionPart->products()->count()}})</dt>
                            <dd class="col-12">
                                <ul class="list-group">
                                    @foreach($revisionPart->products()->get() as $item)
                                        <li class="list-group-item brevisionPart-0 px-0 py-1">
                                            <a href="{{route('products.show', $item)}}">
                                                {!!$item->name!!}
                                            </a>

                                        </li>
                                        @if($revisionPart->products()->count() > 3 && $loop->iteration == 3)
                                            @break
                                        @endif
                                    @endforeach
                                </ul>
                                @if($revisionPart->products()->count() > 3)
                                    <ul class="list-group collapse" id="collapse-revisionPart-{{$revisionPart->id}}">
                                        @foreach($revisionPart->products()->get() as $item)
                                            @if($loop->iteration > 3)
                                                <li class="list-group-item brevisionPart-0 px-0 py-1">
                                                    <a href="{{route('products.show', $item)}}">
                                                        {!!$item->name!!}
                                                    </a>
                                                    
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                    <p class="mt-2">
                                        <a class="btn py-0 btn-sm btn-ms"
                                           data-toggle="collapse"
                                           href="#collapse-revisionPart-{{$revisionPart->id}}"
                                           role="button"
                                           aria-expanded="false"
                                           aria-controls="collapseExample">
                                            <i class="fa fa-chevron-down"></i>
                                            @lang('site::messages.show')
                                            @lang('site::messages.more')
                                            {{$revisionPart->products()->count() - 3}}...
                                        </a>
                                    </p>
                                @endif
                            </dd>
                        </dl>
                    </div>
                    
                </div>
            </div>
        @endforeach
        {{$revisionParts->render()}}
    </div>
@endsection
