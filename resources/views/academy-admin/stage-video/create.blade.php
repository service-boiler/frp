
<div class="list-group-item p-1 video-{{$video->id}}" data-id="{{$video->id}}">
    <div class="row">
        <div class="col-xl-10 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">{{$video->name}} <a target="_blank" href="{{ route('academy-admin.videos.show',$video) }}"><i class="fa fa-external-link"></i> </a></dd>
                <input id="video-{{$video->id}}" type="hidden" name="videos[]" value="{{$video->id}}">
            </dl>
        </div>
        <div class="col-xl-2 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <button type="button" class="btn btn-danger btn-sm video-delete" data-id="{{$video->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                    </button>
                </dd>
            </dl>
        </div>
    </div>
</div>
