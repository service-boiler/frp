<div class="items-col col-12" id="esbCatalogService-{{$esbCatalogService->id}}" data-id="{{$esbCatalogService->id}}">
    <div class="card mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-8">
                    @if($esbCatalogService->company_id != auth()->user()->company()->id)
                        {{ $esbCatalogService->name }} <br >
                        <small class="text-success">Общий стандартный справочник. Редактирование доступно только администратору</small>
                    @else
                    <a class="text-large mb-1"
                       href="{{ route('esb-catalog-services.edit', $esbCatalogService) }}">{{ $esbCatalogService->name }}</a>
                     @endif
                        @component('site::components.bool.pill', ['bool' => $esbCatalogService->enabled])@endcomponent

                </div>
                <div class="col-2 text-right">
                    {{$esbCatalogService->type->name}}
                </div>
                <div class="col-2 text-right">@if($esbCatalogService->cost_std)
                    РЦ: {{ Site::format($esbCatalogService->cost_std) }}
                @endif
                </div>

            </div>
        </div>
    </div>
</div>