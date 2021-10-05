@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.schemes.index') }}">@lang('site::scheme.schemes')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.schemes.show', $scheme) }}">{{ $scheme->block->name }}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::pointer.pointers')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::pointer.pointers')</h1>
        @alert()@endalert
        <div class="justify-content-start border p-3 mb-2">
            <a class="btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('admin.schemes.shapes', $scheme) }}"
               role="button">
                <i class="fa fa-@lang('site::shape.icon')"></i>
                <span>@lang('site::shape.shapes')</span>
            </a>
            <a href="{{ route('admin.schemes.show', $scheme) }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::scheme.help.back')</span>
            </a>
        </div>
        <span id="crosshair"></span>
        <form id="pointer-create-form"
              action="{{route('admin.pointers.store')}}"
              method="POST">
            <input type="hidden" name="x" value="">
            <input type="hidden" name="y" value="">
            @csrf
        </form>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-pills nav-fill mb-3">
                    @foreach($scheme->datasheet->schemes as $datasheet_scheme)
                        <li class="nav-item">
                            <a class="nav-link mx-1 @if($datasheet_scheme->id == $scheme->id) bg-ferroli text-white @else bg-light @endif"
                               href="{{route('admin.schemes.pointers', $datasheet_scheme)}}">{{$datasheet_scheme->block->name}}</a>
                        </li>
                    @endforeach
                </ul>
                <div class="d-flex align-items-start">
                    <nav class="nav nav-fill flex-column" style="width:300px!important;">
                        <div class="nav-item">
                            <table data-empty="@lang('site::pointer.error.element_id')"
                                   data-scheme="{{$scheme->id}}"
                                   data-action="{{route('admin.pointers.create')}}"
                                   id="block-elements" style="width:300px!important;"
                                   class="table m-0 table-sm table-bordered table-hover">
                                <tbody>
                                @foreach($elements as $element)
                                    <tr class="pointer table-pointer"
                                        data-number="{{$element->number}}">
                                        <td class="number">{{$element->number}}</td>
                                        <td class="">{{$element->product->sku}}</td>

                                        <td class="text-left">
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="element-{{$element->id}}"
                                                       name="element_id"
                                                       form="pointer-create-form"
                                                       value="{{$element->id}}"
                                                       data-scheme="{{$element->scheme_id}}"
                                                       class="custom-control-input m-0">
                                                <label class="custom-control-label" for="element-{{$element->id}}">
                                                    {{str_limit($element->product->name, 18)}}
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </nav>
                    <div class="scheme content d-inline-block p-0" style="position:relative;">

                        <img class="map pointers"
                             style=" position: absolute; left: 0; top: 0; padding: 0; border: 0;"
                             src="{{$scheme->image->src()}}">
                        <div id="pointers">
                            @foreach($elements as $element)
                                @foreach($element->pointers as $pointer)
                                    @include('site::admin.scheme.pointers.row', ['pointer' => $pointer, 'element' => $element])
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
