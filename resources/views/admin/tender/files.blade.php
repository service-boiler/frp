<dl class="row">
    @foreach($types as $type)
        <dt class="@if($fails->contains('field', 'file_'.$type->id)) bg-danger text-white @endif col-sm-4 text-left text-sm-right">{{$type->name}}</dt>
        <dd class="col-sm-8">
            <ul class="list-group file-list image-box">
                @if( !$files->isEmpty())
                    @foreach($files as $file)
                        @if($file->type_id == $type->id)
                            <li class="list-group-item">
                                @if($file->isImage)
                                    <img style="max-width:150px;cursor: pointer;" data-toggle="modal"
                                         data-target=".image-modal-{{$file->id}}"
                                         class="img-fluid border" src="{{ $file->src() }}">
                                @else
                                    <a href="{{ route('files.show', $file) }}" class="">{{$file->name}}</a>
                                @endif
                            </li>
                        @endif
                    @endforeach
                @endif
            </ul>
            @if( !$files->isEmpty())
                @foreach($files as $file)
                    @if($file->type_id == $type->id)
                        @if($file->isImage)
                            <div style="z-index: 10000" class="modal fade image-modal-{{$file->id}}" tabindex="-1"
                                 role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <img class="img-fluid border" src="{{ $file->src() }}">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">@lang('site::messages.close')</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endforeach
            @endif
        </dd>
    @endforeach
</dl>