<div class="main-container">
         @alert()@endalert
                <div class="slide-image" style="height: 50vh;">
                    @if(!empty($slide->image))
                        <img style="height: 100%; width: auto;" src="{{$slide->image->src()}}" alt="">
                    @else
                        <h2>Изображение не загружено на сервер</h2>
                    @endif
                </div>

                <div class="row mt-3">
                        <div class="col text-center">
                           {{$slide->name}}
                        </div>
               </div>
               <div class="row mt-3">
                        <div class="col text-center">
                                    @if(!empty($slide->sound))
                                        <audio controls id="sound">
                                                <source src="{{$slide->sound->src()}}"  type="audio/mpeg">
                                        </audio>
                                    @endif
                        </div>
                </div>
               <div class="row mt-2">
                        <div class="col text-center">
                           {!!$slide->text!!}
                        </div>
               </div>
               <div class="row mt-2">
                        <div class="col text-center">
                             <a class="btn btn-ms" href="{{route('academy-admin.presentations.edit',$slide->presentation)}}#slide-{{$slide->id}}">Изменить</a>
                            
                            <button type="button" class="ml-4 btn btn-secondary" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">Закрыть</span>
                            </button>
                        </div>
               </div>
     
</div>

