@extends('layouts.app')
@section('title')@lang('site::admin.revision_part.edit')@lang('site::messages.title_separator')@endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.revision_parts.index') }}">@lang('site::admin.revision_part.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
    </ol>
  
    @alert()@endalert

<form id="form" method="POST" enctype="multipart/form-data" action="{{ route('admin.revision_parts.update',$revisionPart) }}">
@method('PUT')
@csrf
    <div class="card mb-5">
        <div class="card-body">
            
            <div class="form-row">
                <div class="col">
                <span class="text-success">Ведите актикулы старой и новой детали, выберите товар из выпадающего списка. Для новых деталей товар обязательно должен быть на сайте. Старые детали можно не указывать или вводить актикул без выбора товара с сайта.</span>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="part_old_sku">@lang('site::admin.revision_part.part_old_sku')</label>
                            <input type="text"
                                   name="revisionPart[part_old_sku]"
                                   id="part_old_sku"  data-field-name="part_old"
                                   class="search_part form-control{{ $errors->has('revisionPart.part_old_sku') ? ' is-invalid' : '' }}"
                                   value="{{ old('revisionPart.part_old_sku',$revisionPart->part_old_sku) }}">
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.part_old_sku') }}</span>
                        </div>
                    </div>
                
                    <div class="form-row">
                        <div class="col">
                            <div class="search_wrapper" id="part_old_sku_wrapper"></div>
                    
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="part_old_name">@lang('site::admin.revision_part.part_old_name')</label>
                            <input type="text"
                                   disabled
                                   name="revisionPart[part_old_name]"
                                   id="part_old_name" 
                                   class="form-control{{ $errors->has('revisionPart.part_old_id') ? ' is-invalid' : '' }}"
                                   value="{{ old('revisionPart.part_old_name',!empty($revisionPart->partNew) ? $revisionPart->partNew->name : '') }}">
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.part_old_id') }}</span>
                        </div>
                    </div>
                
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="part_new_sku">@lang('site::admin.revision_part.part_new_sku')</label>
                            <input type="text"
                                   name="revisionPart[part_new_sku]"
                                   id="part_new_sku" data-field-name="part_new"
                                   class="search_part form-control{{ $errors->has('revisionPart.part_new_sku') ? ' is-invalid' : '' }}"
                                   value="{{ old('revisionPart.part_new_sku',$revisionPart->part_new_sku) }}">
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.part_new_sku') }}</span>
                            
                        </div>
                        
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <div class="search_wrapper" id="part_new_sku_wrapper"></div>
                    
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="part_new_name">@lang('site::admin.revision_part.part_new_name')</label>
                            <input type="text"
                                   disabled
                                   name="revisionPart[part_new_name]"
                                   id="part_new_name" 
                                   class="form-control{{ $errors->has('revisionPart.part_new_id') ? ' is-invalid' : '' }}"
                                   value="{{ old('revisionPart.part_new_name',!empty($revisionPart->partNew) ? $revisionPart->partNew->name : '') }}">
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.part_new_id') }}</span>
                        </div>
                    </div>
                
                </div>
                  <input type="hidden" id="part_old_id" name="revisionPart[part_old_id]" value="{{ old('revisionPart.part_old_id',$revisionPart->part_old_id) }}">
                  <input type="hidden" id="part_new_id" name="revisionPart[part_new_id]" value="{{ old('revisionPart.part_new_id',$revisionPart->part_new_id) }}">
                        
            </div>
                     
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="date_change">@lang('site::admin.revision_part.date_change')</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_change"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="revisionPart[date_change]"
                                           id="date_change"
                                           maxlength="10"
                                           required
                                          
                                           data-target="#datetimepicker_date_change"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('revisionPart.date_change') ? ' is-invalid' : '' }}"
                                           value="{{ old('revisionPart.date_change',$revisionPart->date_change->format('d.m.Y')) }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_change"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>       
                </div>     
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="date_notice">@lang('site::admin.revision_part.date_notice_short')</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_notice"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="revisionPart[date_notice]"
                                           id="date_notice"
                                           maxlength="10"
                                           required
                                          
                                           data-target="#datetimepicker_date_notice"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('revisionPart.date_notice') ? ' is-invalid' : '' }}"
                                           value="{{ old('revisionPart.date_notice',$revisionPart->date_notice->format('d.m.Y')) }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_notice"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>       
                </div> 
                <div class="col-md-2">
                    <div class="form-row">
                        <div class="col">
                            <div class="custom-control custom-checkbox mt-4 ml-3">
                                <input type="checkbox"
                                       @if(old('revisionPart.interchange',$revisionPart->interchange)) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('revisionPart.interchange') ? ' is-invalid' : '' }}"
                                       id="interchange"
                                       name="revisionPart[interchange]">
                                <label class="custom-control-label"
                                       for="interchange">@lang('site::admin.revision_part.interchange')</label>
                                <span class="invalid-feedback">{{ $errors->first('revisionPart.interchange') }}</span>
                            </div>
                        </div> 
                    </div>       
                </div> 
                <div class="col-md-1">
                    <div class="form-row">
                        <div class="col">
                            <div class="custom-control custom-checkbox mt-4 ml-3">
                                <input type="checkbox"
                                       @if(old('revisionPart.enabled',$revisionPart->enabled)) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('revisionPart.enabled') ? ' is-invalid' : '' }}"
                                       id="enabled"
                                       name="revisionPart[enabled]">
                                <label class="custom-control-label"
                                       for="enabled">@lang('site::messages.enabled')</label>
                                <span class="invalid-feedback">{{ $errors->first('revisionPart.enabled') }}</span>
                            </div>
                        </div> 
                    </div>       
                </div>  
                <div class="col-md-3">
                    <div class="form-row ml-4">
                        <div class="col">
                            <div class="custom-control custom-checkbox mt-4 ml-3">
                                <input type="checkbox"
                                       @if(old('revisionPart.public',$revisionPart->public)) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('revisionPart.public') ? ' is-invalid' : '' }}"
                                       id="public"
                                       name="revisionPart[public]">
                                <label class="custom-control-label"
                                       for="public">@lang('site::admin.revision_part.public')</label>
                                <span class="invalid-feedback">{{ $errors->first('revisionPart.public') }}</span>
                            </div>
                        </div> 
                    </div>       
                </div>   

                                   
            </div>       
                     
            
            
            <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="text_object">@lang('site::admin.revision_part.text_object')</label>
                            <textarea
                                  name="revisionPart[text_object]"
                                  id="text_object"
                                  class="form-control{{ $errors->has('revisionPart.text_object') ? ' is-invalid' : '' }}"
                                  >{{ old('revisionPart.text_object',$revisionPart->text_object) }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.text_object') }}</span>
                        </div>
            </div>   
            
            
            <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="description">@lang('site::admin.revision_part.description')</label>
                            <textarea
                                  name="revisionPart[description]"
                                  id="description"
                                  class="form-control{{ $errors->has('revisionPart.description') ? ' is-invalid' : '' }}"
                                  >{{ old('revisionPart.description',$revisionPart->description) }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('revisionPart.description') }}</span>
                        </div>
            </div>   
            
            
            
            
                <div class="form-row">
                            <div class="col mb-3">
                                <label class="control-label" for="comments">@lang('site::admin.revision_part.comments') (для внутреннего пользования)</label>
                                <textarea
                                      name="revisionPart[comments]"
                                      id="comments"
                                      class="form-control{{ $errors->has('revisionPart.comments') ? ' is-invalid' : '' }}"
                                      >{{ old('revisionPart.comments',$revisionPart->comments) }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('revisionPart.comments') }}</span>
                            </div>
                </div>      
 </form>
 
                <div class="card mt-2 mb-2">
                    <div class="card-body">
                        <h5 class="card-title">@lang('site::file.files') (для внутреннего пользования)</h5>
                        <h6 class="card-subtitle mb-2 text-muted">@lang('site::file.maxsize5mb')</h6>
                        @include('site::file.create.type')
                    </div>
                </div>
            
            <h5 class="card-title">Оборудование для которого заменяется деталь</h5>
                
                <div class="form-row">
                            
                           <div class="col-sm-5">
                                    <div class="form-group">
                                        <label class="control-label" for="product_id">
                                            Поиск оборудования
                                        </label>
                                        <select 
                                                id="product_id"
                                               
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($products as $product)
                                                <option @if(old('revisionPart.product_id') == $product->id) selected @endif value="{{$product->id}}"> {{$product->name}} / {{$product->sku}} </option>
                                            @endforeach
                                        </select>
                                        <small id="product_idHelp" class="d-block form-text text-success">
                                            Введите наименование и выберите из предложенного списка
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('revisionPart.product_id') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="equipment_id">
                                            Поиск модельного ряда
                                        </label>
                                        <select 
                                                id="equipment_id"
                                               
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($equipments as $equipment)
                                                <option @if(old('revisionPart.equipment_id') == $equipment->id) selected @endif value="{{$equipment->id}}"> {{$equipment->name}} </option>
                                            @endforeach
                                        </select>
                                        <small id="product_idHelp" class="d-block form-text text-success">
                                            Введите наименование и выберите из предложенного списка
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('revisionPart.equipment_id') }}</span>
                                    </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="row mb-3">
                                    <div class="col-xl-7 col-sm-7">
                                    <strong>Оборудование</strong>
                                    </div>
                                    <div class="col-xl-5 col-sm-5">
                                   <strong>С какого SN изменения</strong>
                                    </div>
                                </div>
                                <div class="row">     
                                <div class="col">     
                                
                                    <div class="form-group">  
                                        <div class="list-group" id="parts">
                                        @foreach($revisionPart->products as $product)
                                        @include('site::part.create_revision_part', ['product' => $product, 'start_serial' => $product->pivot->start_serial])
                                        @endforeach
                                        </div>
                                    </div>
                                </div>
                                </div>
                             </div>
                                
                            
                </div>
               
         <hr/>
            <div class="form-row">
                <div class="col text-right">
                    <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('admin.revision_parts.index') }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    

</div>
@endsection


@push('scripts')
<script>
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
try {
window.addEventListener('load', function () {
    let product = $('#product_id'),
        part_new_sku = $('#part_new_sku'),
        equipment = $('#equipment_id'),
        parts = $('#parts'),
        selected = [];
       
//---- поиск и добавление оборудования ----               
    $(document)
        .on('click', '.part-delete', (function () {
            $('.product-' + $(this).data('id')).remove();
            let index = selected.indexOf($(this).data('id'));
            
            if (index > -1) {
                selected.splice(index, 1);
                $('.product-' + $(this).data('id')).remove();
            }
            
        }));
    

    product.select2({
        theme: "bootstrap4",
        placeholder: '@lang('site::messages.select_from_list')',
        selectOnClose: true,
        minimumInputLength: 3,
    });

    product.on('select2:select', function (e) {
        let product_id = $(this).find('option:selected').val();
        if (!selected.includes(product_id)) {
            product.removeClass('is-invalid');
            selected.push(product_id);
            axios
                .get("/api/products/create-revision-part/" + product_id)
                .then((response) => {

                    parts.append(response.data);
                    $('[name="product[' + product_id + ']"]').focus();
                    
                    product.val(null)
                })
                .catch((error) => {
                    this.status = 'Error:' + error;
                });
        } else {
            product.addClass('is-invalid');
        }
    });
//---- Конец. Поиск и добавление оборудования ----            
 //---- поиск и добавление модельного ряда ----               
    equipment.select2({
        theme: "bootstrap4",
        placeholder: '@lang('site::messages.select_from_list')',
        selectOnClose: true,
        minimumInputLength: 3,
    });

    equipment.on('select2:select', function (e) {
        let equipment_id = $(this).find('option:selected').val();
        if (!selected.includes(equipment_id)) {
            equipment.removeClass('is-invalid');
            selected.push(equipment_id);
            axios
                .get("/api/products/create-from-equipment/" + equipment_id)
                .then((response) => {

                    parts.append(response.data);
                    equipment.val(null)
                })
                .catch((error) => {
                    this.status = 'Error:' + error;
                });
        } else {
            equipment.addClass('is-invalid');
        }
    });
//---- Конец. Поиск и добавление модельного ----     
               
//---- Поиск новой запчасти по артикулу ----

    $(document)
        .on('keyup', '.search_part', (function(I){
    
        var field_name = $(this)[0].dataset.fieldName;
        
        switch(I.keyCode) {
            // игнорируем нажатия 
            case 13:  // enter
            case 27:  // escape
            case 38:  // стрелка вверх
            case 40:  // стрелка вниз
            break;

            default:
                
                $(this).attr('autocomplete','off');
                
                            if($(this).val().length>6){
                                input_initial_value = $(this).val();
                                $('#' + field_name+'_name').val('');
                                $('#' + field_name+'_id').val('');
                                $.get("/api/products/search", { "filter[search_part]":$(this).val()},function(data){
                                    var list = JSON.parse(data);
                                    suggest_count = list.data.length;
                                    $('#' + field_name+'_sku_wrapper').hide();               
                                    if(suggest_count > 0){
                                        $('#' + field_name+'_sku_wrapper').html("").show();
                                        for(var i in list.data){
                                            if(list.data[i] != ''){
                                            
                                                $('#' + field_name+'_sku_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list.data[i].id+'">'+list.data[i].sku+' '+list.data[i].name+'</div>');
                                                
                                                $('#' + field_name+'_sku_wrapper').find('#result_id-'+list.data[i].id).click(function() {
                                                  
                                                   $('#' + field_name+'_sku_search').val('');
                                                    $('#' + field_name+'_sku').val(list.data[$(this)[0].getAttribute('data-key')].sku);
                                                    $('#' + field_name+'_name').val(list.data[$(this)[0].getAttribute('data-key')].name);
                                                    $('#' + field_name+'_id').val(list.data[$(this)[0].getAttribute('data-key')].id);
                                                    
                                                    $('#text_object').val($('#text_object')[0].value + ' '+list.data[$(this)[0].getAttribute('data-key')].sku + ' '+list.data[$(this)[0].getAttribute('data-key')].name);
                                                    
                                                    $('#' + field_name+'_sku_wrapper').fadeOut(2350).html('');
                                                });
                                            } else {
                                             $('#' + field_name+'_sku_wrapper').html("").hide();
                                            }
                                        }
                                    }
                                }, 'html');
                            }
                        break;
                    }
                    })
                )
            
            
                .on('keydown', '.search_part', (function(I){
                var field_name = $(this)[0].dataset.fieldName;
                    switch(I.keyCode) {
                        case 27: // escape
                            $('#' + field_name+'_sku_wrapper').hide();
                            return false;
                        break;
                        
                    }
                })
                
                );

             $('html').click(function(){
                $('.search_wrapper').hide();
            }); 
            
            
        
        
});

} catch (e) {
console.log(e);
}

</script>
@endpush