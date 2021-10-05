<div class="mb-2 list-group-item p-1 product-{{$product->id}}" data-id="{{$product->id}}">
    <div class="row">
        <div class="col-xl-12 col-sm-12">
            <button type="button" class="btn btn-danger btn-sm part-delete" data-id="{{$product->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
            <span class="font-weight-bold">{{$product->name}} ({{$product->sku}})</span>
             
        
                    
                
        </div>
    </div>
</div>
