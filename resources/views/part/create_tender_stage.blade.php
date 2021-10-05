@if($tenderProduct)
<div class="row mb-2 product-{{$tenderProduct->id}}-{{$random}}" id="product-{{$tenderProduct->id}}-{{$random}}">
    <div class="col-2">
        {{$plannedDate}}
    </div>
    <div class="col-2">
        {{$tenderProduct->product->name}}
    </div>
    <div class="col-1">
        {{$count}} шт.
    </div>
    @if($canUpdate)
    <div class="col-2">
        <button type="button" class="btn btn-danger btn-sm part-delete" 
                data-id="{{$tenderProduct->id}}-{{$random}}" 
                data-product="{{$tenderProduct->id}}"
                data-count="{{$count}}"
                data-planned-date="{{$plannedDate}}"
                
                        data-dismiss="alert"
                        aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                </button>
    </div>
    @endif
    
</div>
@else
    <div class="row mb-2 product-{{$random}}" id="product-{{$random}}">
        <div class="col-2">
            {{$plannedDate}}
        </div>
        <div class="col-2">
            Товар был удален из тендера
        </div>
        <div class="col-1">
            {{$count}} шт.
        </div>
        @if($canUpdate)
            <div class="col-2">
                <button type="button" class="btn btn-danger btn-sm part-delete"
                        data-id="{{$random}}"
                        data-product="{{$tenderProduct->id}}"
                        data-count=""
                        data-planned-date="{{$plannedDate}}"

                        data-dismiss="alert"
                        aria-label="Close">
                    <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                </button>
            </div>
        @endif

    </div>
@endif


