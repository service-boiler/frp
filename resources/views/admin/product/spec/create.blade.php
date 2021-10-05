<div class="col-6 spec-{{$spec->id}}" data-id="spec-{{$spec->id}}">
    <a href="" class="spec-delete inline-block" data-id="{{$spec->id}}"
            data-dismiss="alert"
            aria-label="Close">
        <span aria-hidden="true"><i class="fa fa-close"></i> </span>
    </a>
    <label class="font-weight-bold" for="spec-id-{{$spec->id}}">{{$spec->name}}, {{$spec->unit}} </label>
    <input type="text"
           name="specs[{{$spec->id}}]"
           id="spec-id-{{$spec->id}}"
           required
           class="form-control inline-block"
           value="{{ old('specs' .$spec->id)}}">
    <span class="invalid-feedback">{{ $errors->first('product.sku') }}</span>
</div>