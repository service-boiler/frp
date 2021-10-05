<div class="items-col col-12" id="esbCatalogPrice-{{$esbCatalogPrice->id}}" data-id="{{$esbCatalogPrice->id}}">
    <div class="card mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-8">

                    <a class="mb-1 @if(!$esbCatalogPrice->enabled)text-muted @endif"
                       href="{{ route('admin.esb-catalog-prices.edit', $esbCatalogPrice) }}">{{ $esbCatalogPrice->service->name }}</a>

                        @component('site::components.bool.pill', ['bool' => $esbCatalogPrice->enabled])@endcomponent
                    <br /> {{$esbCatalogPrice->company ? $esbCatalogPrice->company->public_name : ''}}
                </div>
                <div class="col-2 text-right">@if($esbCatalogPrice->price)
                    Цена: {{ Site::format($esbCatalogPrice->price) }}
                @endif
                </div>

                <div class="col-2 text-right">
                    @can('delete', $esbCatalogPrice)
                        <a class="btn btn-danger btn-row-delete"
                           title="@lang('site::messages.delete')"
                           href="javascript:void(0);"
                           data-form="#price-delete-form-{{$esbCatalogPrice->id}}"
                           data-btn-delete="@lang('site::messages.delete')"
                           data-btn-cancel="@lang('site::messages.cancel')"
                           data-label="@lang('site::messages.delete_confirm')"
                           data-message="@lang('site::messages.delete_sure') {{ $esbCatalogPrice->service->name }}?"
                           data-toggle="modal"
                           data-target="#form-modal"
                        ><i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span></a>
                        <form id="price-delete-form-{{$esbCatalogPrice->id}}"
                              action="{{route('admin.esb-catalog-prices.destroy', $esbCatalogPrice)}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </div>

            </div>
        </div>
    </div>
</div>