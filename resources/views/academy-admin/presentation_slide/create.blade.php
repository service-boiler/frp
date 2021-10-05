    <div class="card presentation_slide-item mt-4">
          <div class="card-body" id="summernote">
            
                <div class="form-row required">
                <div class="col">
                    <label class="control-label" class="control-label"
                           for="slide[{{$random}}][name]">@lang('site::academy.presentation.slide_name')</label>
                        <input type="name"
                               name="slide[{{$random}}][name]"
                               id="name_{{$random}}"
                               required
                               maxlength="100"
                               class="form-control{{ $errors->has('slide.'.$random.'.name') ? ' is-invalid' : '' }}"
                               value="{{ old('slide.'.$random.'.name') }}">
                        <span class="invalid-feedback">{{ $errors->first('slide.'.$random.'.name') }}</span>
                
                </div>
                </div>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="image_id">@lang('site::catalog.image_id')</label>

                                <form method="POST" enctype="multipart/form-data"
                                      action="{{route('ferroli-user.images.store')}}">
                                    @csrf
                                     
                                    <input type="hidden"
                                           name="slide_random"
                                           value="{{$random}}"/>
                                        
                                    
                                    <input type="hidden"
                                           name="storage"
                                           value="presentations"/>
                                      
                                    <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.presentations.accept')}}"
                                           name="path"/>
                                    <div>
                                    <input type="button" class="btn btn-ms image-upload-button-presentation_slide"
                                           value="@lang('site::messages.load')"/>
                                           <span id="downloadbar" class="d-none"><img style="height:40px" src="/images/l3.gif" /></span>
                                    <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                    </div>
                                    
                                    
                                </form>
                                
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="images-{{$random}}" class="row bg-white">
                            @include('site::admin.image.edit')
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <label class="control-label" class="control-label"
                                               for="file_id">@lang('site::academy.presentation.slide_sound')</label>

                                        <form id="sound" method="POST" enctype="multipart/form-data"
                                              action="{{route('ferroli-user.files.store')}}">
                                            @csrf
                                           <input type="hidden"
                                                    name="slide_random"
                                                    value="{{$random}}"/>
                                            <input type="hidden"
                                                   name="type_id"
                                                   value="24"/>
                                            <input type="hidden"
                                                   name="storage"
                                                   value="sounds"/>
                                            <input class="d-inline-block form-control-file{{ $errors->has('file_id') ? ' is-invalid' : '' }}"
                                                   type="file"
                                                   accept="{{config('site.sounds.accept')}}"
                                                   name="path"/>

                                            <input type="button" class="btn btn-ms file-upload-button-sound"
                                                   value="@lang('site::messages.load')"/>
                                            <span id="downloadbar" class="d-none"><img style="height:40px" src="/images/l3.gif" /></span>
                                            <span class="invalid-feedback">{{ $errors->first('file_id') }}</span>
                                        </form>


                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="files-{{$random}}" class="row bg-white">
                                    @include('site::admin.file.edit')
                                </div>
                            </div>
                        </div>
                <div class="row mt-4">
                    <div class="col">
                
                        <label class="control-label" class="control-label"
                                for="slide[{{$random}}][text]">@lang('site::academy.presentation.slide_text')</label>
                    
                        <textarea
                               name="slide[{{$random}}][text]"
                               id="text_{{$random}}"
                               class="summernote form-control{{ $errors->has('catalog.description') ? ' is-invalid' : '' }}"
                               >{{ old('slide.'.$random.'.text') }}</textarea>
                        <span class="invalid-feedback">{{ $errors->first('slide.'.$random.'.text') }}</span>
                
                    </div>
                </div>
            
           
            <div class="row mt-2">
                <div class="col">
                    <a href="javascript:void(0);" onclick="$(this).parent().parent().parent().parent().remove();" class="btn btn-sm btn-danger">@lang('site::messages.delete') @lang('site::academy.presentation.slide_add')</a>
                </div>
            </div>
            
        </div>
    </div>
