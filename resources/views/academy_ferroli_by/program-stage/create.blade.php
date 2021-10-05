    
<div class="list-group-item p-1 stage-{{$stage->id}}" data-id="{{$stage->id}}">
    <div class="row">
        <div class="col-xl-10 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">{{$stage->name}} <a target="_blank" href="{{ route('academy-admin.stages.show',$stage) }}"><i class="fa fa-external-link"></i> </a></dd>
                <input id="stage-{{$stage->id}}" type="hidden" name="stages[]" value="{{$stage->id}}">
            </dl>
        </div>
        <div class="col-xl-2 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <button type="button" class="btn btn-danger btn-sm stage-delete" data-id="{{$stage->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                    </button>
                </dd>
            </dl>
        </div>
    </div>
</div>
