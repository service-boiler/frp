@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-visits.index') }}">Запланированные выезды</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.create') новую заявку</li>
        </ol>

        @alert()@endalert()
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row justify-content-center mb-5">
            <div class="col">
                <form id="form"
                      method="POST"
                      action="{{ route('esb-visits.store') }}">
                    @csrf
                    <div class="card mb-2">
                        <div class="card-body">
                        
                       
                            <div class="row mb-3">
                                <div class="col-12">
                                    Клиент: <span class="font-weight-bold">{{$client->name_filtred}}</span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-row required"> <label class="control-label"
                                               for="type_id">Тип выезда</label>
                                    <select class="form-control{{  $errors->has('type_id') ? ' is-invalid' : '' }}"
                                                name="type_id"
                                                id="type_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                            
                                        @foreach($visit_types as $type)
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @endforeach
                                   </select>
                                        <span class="invalid-feedback">{{ $errors->first('type_id') }}</span>
                                   
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-row mb-2">
                                        <label class="control-label"
                                               for="date_planned">Планируемая дата и время</label>
                                        <div class="input-group date datetimepickerfull" id="datetimepicker_date_palnned"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="date_palnned"
                                                   id="date_palnned"
                                                   data-target="#datetimepicker_date_palnned"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('date_palnned') ? ' is-invalid' : '' }}"
                                                   value="{{ old('date_palnned')}}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_palnned"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('date_planned') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-2">

                                    <div class="form-row required">
                                        <label class="control-label"
                                               for="phone">@lang('site::user.phone_second')</label>
                                        <input required
                                               type="tel"
                                               oninput="mask_phones()"
                                               id="phone"
                                               name="phone"
                                               class="phone-mask form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}"
                                               pattern="{{config('site.phone.pattern')}}"
                                               maxlength="{{config('site.phone.maxlength')}}"
                                               title="{{config('site.phone.format')}}"
                                               data-mask="{{config('site.phone.mask')}}"
                                               value="{{ old('phone',$client->phone) }}"
                                               placeholder="@lang('site::mounter.placeholder.phone')">
                                        <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-4">

                                    <div class="form-row required">
                                        <label class="control-label"
                                               for="contact_name">@lang('site::user.contact')</label>
                                        <input required
                                               type="text"
                                               id="contact_name"
                                               name="contact_name"
                                               class="form-control{{ $errors->has('contact_name') ? ' is-invalid' : '' }}"
                                               value="{{ old('contact_name',($client->name)) }}"
                                               >
                                        <span class="invalid-feedback">{{ $errors->first('contact_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            @if($client->esbProducts()->exists())
                            <div class="form-row">
                                <label class="control-label"
                                       for="user_product_id">Оборудование клиента</label>
                                <select class="form-control{{  $errors->has('.user_product_id') ? ' is-invalid' : '' }}"
                                        name="user_product_id"
                                        id="user_product_id">
                                    @foreach($client->esbProducts as $product)
                                        <option
                                                @if(old('user_product_id') == $product->id) selected
                                                @endif
                                                value="{{ $product->id }}">
                                                {{ !empty($product->product) ? $product->product->name : mb_substr($product->product_no_cat,0,25)}}

                                                - SN: {{ $product->serial}} - {{ optional($product->address)->full }}
                                                </option>

                                    @endforeach
                                </select>
                                <span class="invalid-feedback">{{ $errors->first('user_product_id') }}</span>

                            </div>
                            @endif

                            <div class="form-row">
                                <div class="col-sm-3">
                                    <label class="control-label"
                                           for="cost_planned">Стоимость выезда и работ</label>
                                    <div class="input-group">
                                        <input type="text"
                                               name="cost_planned" form="new_visit"
                                               id="cost_new"
                                               class="form-control{{ $errors->has('cost_planned') ? ' is-invalid' : '' }}"
                                               value="{{ old('cost_planned')}}">
                                        <div class="input-group-append">

                                            <div class="input-group-text">
                                                <i class="fa fa-money"></i>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-sm-5">
                                    <div class="form-row required"> <label class="control-label"
                                                                           for="engineer_id">Инженер</label>
                                        <select class="form-control{{  $errors->has('engineer_id') ? ' is-invalid' : '' }}"
                                                name="engineer_id"
                                                id="engineer_id">
                                            <option value="">@lang('site::messages.select_from_list')</option>

                                            @foreach($engineers as $engineer)
                                                <option value="{{$engineer->id}}">{{$engineer->name}} / {{$engineer->region->name}} / {{$engineer->type->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="invalid-feedback">{{ $errors->first('engineer_id') }}</span>

                                    </div>
                                </div>
                            </div>
                            <div class="form-row">

                                    <div class="col mb-3">
                                        <label class="control-label" for="comments">@lang('site::user.esb_request.comments_equipment')</label>
                                        <textarea
                                              name="comments"
                                              id="comments"
                                              class="form-control{{ $errors->has('comments') ? ' is-invalid' : '' }}"
                                              >{{ old('comments') }}</textarea>
                                        <span class="invalid-feedback">{{ $errors->first('comments') }}</span>
                                    </div>
                            </div>
                        
                        


                            <div class="form-row">
                                <div class="col text-right">
                                    <button form="form" type="submit"
                                            class="btn btn-ms mb-1">
                                        <i class="fa fa-check"></i>
                                        <span>@lang('site::messages.save')</span>
                                    </button>
                                    <a href="{{ route('home') }}" class="btn btn-secondary mb-1">
                                        <i class="fa fa-close"></i>
                                        <span>@lang('site::messages.cancel')</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        try {
            window.addEventListener('load', function () {

                let engineer = $('#engineer_id');


                engineer.select2({
                    theme: "bootstrap4",
                    placeholder: '@lang('site::messages.select_from_list')',
                    selectOnClose: true,
                    minimumInputLength: 3,
                });




            });
        } catch (e) {
            console.log(e);
        }

    </script>
@endpush