
<div class="list-group-item p-1 question-{{$question->id}}" data-id="{{$question->id}}">
    <div class="row">
        <div class="col-xl-10 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">{{$question->text}}</dd>
                <input id="question-{{$question->id}}" type="hidden" name="questions[]" value="{{$question->id}}">
            </dl>
        </div>
        <div class="col-xl-2 col-sm-12">
            <dl class="dl-horizontal mt-2">
                <dt class="col-12"></dt>
                <dd class="col-12">
                    <button type="button" class="btn btn-danger btn-sm question-delete" data-id="{{$question->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i> @lang('site::messages.delete')</span>
                    </button>
                </dd>
            </dl>
        </div>
    </div>
</div>
