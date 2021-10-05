@extends('layouts.app')
@section('title'){!! $datasheet->name ?: $datasheet->file->name !!}@lang('site::messages.title_separator')@endsection
@section('header')
    @include('site::header.front',[
        'h1' => $datasheet->name ?: $datasheet->file->name,
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['url' => route('datasheets.index'), 'name' => __('site::datasheet.datasheets')],
            ['name' => $datasheet->name ?: $datasheet->file->name]
        ]
    ])
@endsection
@section('content')
    <div class="container">
        <div class="card mb-4">
            <div class="card-body">
                <dl class="row">
                    @if(!empty($datasheet->file))
                    <dt class="col-sm-4 text-left text-sm-right"></dt>
                    <dd class="col-sm-8">
                        @if($datasheet->file->exists())
                            @include('site::file.download', ['file' => $datasheet->file, 'small' => true])
                        @else
                            <span class="badge badge-danger text-big">@lang('site::file.error.not_found')</span>
                        @endif
                    </dd>
                    @endif
                    @if(!empty($datasheet->cloud_link))
                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::datasheet.cloud_link')</dt>
                    <dd class="col-sm-8"> <a class="btn btn-success btn-sm" href="{{$datasheet->cloud_link}}" target="_blank"><i class="fa fa-cloud-download"></i> @lang('site::messages.download') {{$datasheet->name}}</a>
                        
                    </dd>
                    @endif

                    <dt class="col-sm-4 text-left text-sm-right">@lang('site::datasheet.name')</dt>
                    <dd class="col-sm-8">{{$datasheet->name}}</dd>
                    
                    @if($datasheet->date_from)
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::datasheet.date_from_to')</dt>
                        <dd class="col-sm-8">@include('site::datasheet.date')</dd>
                    @endif
                    
                    @if(!empty($datasheet->file))
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::datasheet.type_id')</dt>
                        <dd class="col-sm-8">{{$datasheet->file->type->name}}</dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.mime')</dt>
                        <dd class="col-sm-8">{{mimeToExt($datasheet->file->mime)}}</dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::file.size')</dt>
                        <dd class="col-sm-8">
                            @if($datasheet->file->exists())
                                {{formatFileSize(filesize(Storage::disk($datasheet->file->storage)->getAdapter()->getPathPrefix().$datasheet->file->path))}}
                            @endif
                        </dd>
                    @endif
                    @if($products->isNotEmpty())
                        <dt class="col-sm-4 text-left text-sm-right">@lang('site::datasheet.help.products')</dt>
                        <dd class="col-sm-8">
                            <div class="list-group">
                                @foreach($products as $product)
                                    <a href="{{route('products.show', $product)}}"
                                       class="list-group-item p-1 list-group-item-action">
                                        {!! $product->name !!}
                                    </a>
                                @endforeach
                            </div>
                        </dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>
@endsection
