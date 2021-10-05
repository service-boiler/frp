<div class="items-col col-12 sort-item" id="esbMaintenanceDistance-{{$esbMaintenanceDistance->id}}" data-id="{{$esbMaintenanceDistance->id}}">
    <div class="card mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <i class="fa fa-arrows"></i>
                    <a class="mb-1"
                       href="{{ route('admin.esb-maintenance-distances.edit', $esbMaintenanceDistance) }}">{{ $esbMaintenanceDistance->name }}</a>
                       <div class="ml-3">@lang('site::product.active') 
                            @bool(['bool' => $esbMaintenanceDistance->active == 1])@endbool</div>
                </div>
                <div class="col-sm-5 ext-right"><span class="text-muted">Рекомендуемая базовая стоимость транспортных расходов:</span><br />
                   {!! moneyFormatRub($esbMaintenanceDistance->cost) !!}
                </div>
                <div class="col-sm-2 text-right m-0">
                    
                        <a class="btn btn-sm btn-danger btn-row-delete p-1"
                           title="@lang('site::messages.delete')"
                           href="javascript:void(0);"
                           data-form="#esbMaintenanceDistance-delete-form-{{$esbMaintenanceDistance->id}}"
                           data-btn-delete="@lang('site::messages.delete')"
                           data-btn-cancel="@lang('site::messages.cancel')"
                           data-label="@lang('site::messages.delete_confirm')"
                           data-message="@lang('site::messages.delete_sure') {{ $esbMaintenanceDistance->name }}?"
                           data-toggle="modal"
                           data-target="#form-modal"
                        ><i class="fa fa-close"></i> <span class="d-none d-sm-inline-block">@lang('site::messages.delete')</span></a>
                        <form id="esbMaintenanceDistance-delete-form-{{$esbMaintenanceDistance->id}}"
                              action="{{route('admin.esb-maintenance-distances.destroy', $esbMaintenanceDistance)}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>