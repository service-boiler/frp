<div class="items-col col-12 sort-item" id="esbCatalogService-{{$esbCatalogService->id}}" data-id="{{$esbCatalogService->id}}">
    <div class="card mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    <i class="fa fa-arrows"></i>

                    <a class="text-large mb-1 @if(!$esbCatalogService->enabled) text-muted @endif"
                       href="{{ route('admin.esb-catalog-services.edit', $esbCatalogService) }}">{{ $esbCatalogService->name }}</a>


                </div>
                <div class="col-2 text-right">
                    {{$esbCatalogService->type->name}} <br />
                    @component('site::components.bool.pill', ['bool' => $esbCatalogService->enabled])@endcomponent
                </div>
                <div class="col-2 text-right">@if($esbCatalogService->cost_std)
                    РЦ: {{ Site::format($esbCatalogService->cost_std) }}
                @endif
                </div>

            </div>
        </div>
    </div>
</div>