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
                <a href="{{ route('admin.products.index') }}">@lang('site::product.cards')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.show', $product) }}">{{$product->fullName}}</a>
            </li>
            <li class="breadcrumb-item active">Спецификация</li>
        </ol>
        @alert()@endalert
        <div class="card mb-4 ">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <form method="POST" id="form-content"
                              action="{{ route('admin.products.specs.store', $product) }}">

                            @csrf
                            <div class="row" id="specs">
                            @foreach($specs as $spec)
                                
                                    
                                     <div class="col-6 spec-{{$spec->id}}" data-id="spec-{{$spec->id}}">
                                                <a href="" class="spec-delete inline-block" data-id="{{$spec->id}}"
                                                    data-dismiss="alert"
                                                    aria-label="Close">
                                                <span aria-hidden="true"><i class="fa fa-close"></i> </span>
                                            </a>
                                            <label class="" for="spec-id-{{$spec->id}}">{{$spec->name}}, {{$spec->unit}} </label>
                                                <input type="text"
                                                       name="specs[{{$spec->id}}]"
                                                       id="spec-{{$spec->id}}"
                                                       class="form-control inline-block"
                                                       value="{{ old('specs' .$spec->id, $product->specRelations()->where('product_spec_id',$spec->id)->first() ? $product->specRelations()->where('product_spec_id',$spec->id)->first()->spec_value : '' )}}">
                                                <span class="invalid-feedback">{{ $errors->first('product.sku') }}</span>
                                            
                                            </div>
                                            
                                
                            @endforeach
                            </div>
                        </form>
                    </div>
                    
                </div>
                
            </div>
        </div>
        
        
        <div class="row">        
            <div class="col-12">
                <fieldset class="border p-3" id="specs-search-fieldset">
                    <div class="form-group required">
                        <label class="control-label"
                                   for="search_spec">Для добавления атрибута товара введите часть названия атрибута и выберите из выпадающего списка</label>
                            <select data-limit="20" id="search_spec" class="form-control">
                                <option value=""></option>
                            </select>
                            
                    </div>
                </fieldset>
            </div>
            
        </div> 
        
        <div class="card mb-4 ">
            <div class="card-body">
                <div class="row">        
                    <div class="col-12">
                        Если нужные атрибуты технических характеристик не найдены, их необходимо добавить в разделе <a target="_blank" href="{{route('admin.productspecs.index')}}">Тех. хар. товаров</a>.
                        <br />На странице отображаются все атрибуты, которые имеются у всего модельного ряда. 
                        Если какому-то товару добавить заполненный атрибут, то у остальных товаров этого же модельного ряда появится этот атрибут без значения.
                    </div>
                </div> 
            </div> 
        </div> 
        
        <div class=" border p-3 mt-2 mb-4 text-right">
            <button form="form-content" type="submit" class="btn btn-ms">
                <i class="fa fa-check"></i>
                <span>@lang('site::messages.save')</span>
            </button>
            <a href="{{ route('admin.products.specs.index', $product) }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-close"></i>
                <span>@lang('site::messages.cancel')</span>
            </a>
            <a href="{{ route('admin.products.show', $product) }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::product.help.back')</span>
            </a>
        </div>

    
    
        <div class="card mb-4 ">
            <div class="card-body">
                @if(!empty($product->equipment))
                <div class="row mb-3">        
                    <div class="col-12">
                         Другие товары модельного ряда <b>{{$product->equipment->name}}</b> 
                    </div>
                </div> 
                @foreach($product->equipment->products as $eq_product)
                <div class="row mb-3">        
                    <div class="col-12">
                        <a href="{{route('admin.products.specs.index',$eq_product)}}">{{$eq_product->name}}</a>
                    </div>
                </div> 
                @endforeach
                @else Товар не привязан к модельному ряду! @endif
            </div> 
        </div> 
        </div>
    
@endsection


@push('scripts')
<script>

 try {
        window.addEventListener('load', function () {

            let spec = $('#spec_id'),
                search_spec = $('#search_spec'),
                specs = $('#specs'),
                selected = [];
            
            $(document)
                .on('click', '.spec-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.spec-' + $(this).data('id')).remove();
                   
                }))
                
            search_spec.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/product-specs',
                    dataType: 'json',
                    delay: 700,
                    data: function (params) {
                        return {
                            'filter[search_spec]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                    
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (spec) {
                    if (spec.loading) return "...";
                    let markup = spec.name;
                    console.log(markup);
                    return markup;
                },
                templateSelection: function (spec) {
                    if (spec.id !== "") {
                        return spec.name;
                    } else {
                    return "-- выберите атрибут товара --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            search_spec.on('select2:select', function (e) {
                let spec_id = $(this).find('option:selected').val();
                if (!selected.includes(spec_id)) {
                    search_spec.removeClass('is-invalid');
                    
                    axios
                        .get("/api/product-specs/create/" + spec_id)
                        .then((response) => {

                            specs.append(response.data);
                            $('[name=spec_id]').focus();
                            search_spec.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    search_spec.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush