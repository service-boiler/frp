
<div class="list-group-item p-1 presentation-{{$presentation->id}}" data-id="{{$presentation->id}}">
    <div class="row">
        <div class="col-xl-10 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">{{$presentation->name}} <a target="_blank" href="{{ route('academy-admin.presentations.show',$presentation) }}"><i class="fa fa-external-link"></i> </a></dd>
                <input id="presentation-{{$presentation->id}}" type="hidden" name="presentations[]" value="{{$presentation->id}}">
            </dl>
        </div>
        <div class="col-xl-2 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <button type="button" class="btn btn-danger btn-sm presentation-delete" data-id="{{$presentation->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                    </button>
                </dd>
            </dl>
        </div>
    </div>
</div>
