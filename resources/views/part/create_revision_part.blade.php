<div class="mb-1 list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-7 col-sm-7 mt-3">
            <button type="button" class="btn btn-danger btn-sm part-delete" data-id="{{$product->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
            <span class="font-weight-bold">{{$product->name}} ({{$product->sku}})</span>
            
             
        
                    
                
        </div>
        <div class="col-xl-5 col-sm-5 mt-3">
                            <input type="text"
                                   name="products[{{$product->id}}][start_serial]"
                                   id="part_start_serial" 
                                   class="search_part form-control{{ $errors->has('revisionPart.part_new_sku') ? ' is-invalid' : '' }}"
                                   value="{{ old('products.' .$product->id .'.start_serial', !empty($start_serial) ? $start_serial : '') }}">
                            <span class="invalid-feedback">{{ $errors->first('products.' .$product->id .'.start_serial') }}</span>
                            <input type="hidden" name="products[{{$product->id}}][product_id]" value="{{$product->id}}">
                        </div>
    </div>
</div>
