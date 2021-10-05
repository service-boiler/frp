@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin') }}">@lang('site::academy.admin_index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.stages.index') }}">@lang('site::academy.stage.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::academy.stage.add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('academy-admin.stages.store') }}">
                    @csrf
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.stage.name')</label>
                                    <input type="text" name="stage[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                            
                    <div class="form-group" id="form-group-theme_id">
                                <label class="control-label" for="theme_id">@lang('site::academy.stage.theme')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('stage.theme_id') ? ' is-invalid' : '' }}"
                                            name="stage[theme_id]"
                                            id="theme_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($themes as $theme)
                                            <option @if(isset($theme_id) && $theme_id == $theme->id)
                                                    selected
                                                    @endif
                                                    value="{{ $theme->id }}">
                                                {{ $theme->name }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('stage.theme_id') }}</span>
                    </div>       
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.stage.annotation')</label>
                                    <input type="text" name="stage[annotation]"
                                           id="annotation"
                                           class="form-control{{ $errors->has('annotation') ? ' is-invalid' : '' }}"
                                           value="{{ old('stage[annotation]') }}">
                                    <span class="invalid-feedback">{{ $errors->first('annotation') }}</span>
                                </div>
                    </div>
                    
                    <div class="row">        
                            <div class="col-5">
                                <fieldset class="border p-3" id="presentations-search-fieldset">
                                    <div class="form-group">
                                        <label class="control-label"
                                                   for="presentations_search">@lang('site::academy.stage.add_presentation')</label>
                                            <select data-limit="30" id="presentations_search" class="form-control">
                                                <option value=""></option>
                                            </select>
                                            
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="">@lang('site::academy.stage.presentations')</label>
                                    <div class="list-group" id="presentations">
                                        @foreach($presentations as $presentation)
                                            @include('site::academy-admin.stage-presentation.create', ['presentation' => $presentation])
                                        @endforeach
                                    </div>
                                   
                                </div>
                            
                            </div>
                    
                    </div>       
                           
                    
                    <div class="row">        
                            <div class="col-5">
                                <fieldset class="border p-3" id="videos-search-fieldset">
                                    <div class="form-group">
                                        <label class="control-label"
                                                   for="videos_search">@lang('site::academy.stage.add_video')</label>
                                            <select data-limit="30" id="videos_search" class="form-control">
                                                <option value=""></option>
                                            </select>
                                            
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="">@lang('site::academy.stage.videos')</label>
                                    <div class="list-group" id="videos">
                                        @foreach($videos as $video)
                                            @include('site::academy-admin.stage-video.create', ['video' => $video])
                                        @endforeach
                                    </div>
                                   
                                </div>
                            
                            </div>
                    
                    </div>       
                    <div class="row mt-3">        
                            
                            <div class="col-7">
                                
                            <label class="control-label" for="theme_id">@lang('site::academy.stage.add_test')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('stage.test_id') ? ' is-invalid' : '' }}"
                                            name="stage[test_id]"
                                            id="test_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($tests as $test)
                                            <option @if(isset($test_id) && $test_id == $test->id)
                                                    selected
                                                    @endif
                                                    value="{{ $test->id }}">
                                                {{ $test->name }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('stage.test_id') }}</span>
                            </div>
                            
                    
                    </div>          
                    <div class="row mt-3">        
                            
                            <div class="col-7">
                                
                            <label class="control-label" for="parent_stage_id">@lang('site::academy.stage.add_parent_stage')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('stage.parent_stage_id') ? ' is-invalid' : '' }}"
                                            name="stage[parent_stage_id]"
                                            id="parent_stage_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($stages as $parent_stage)
                                            <option @if(old('stage[parent_stage_id]')) selected @endif
                                                    value="{{ $parent_stage->id }}">
                                                {{ $parent_stage->name }}
                                            </option>
                                        @endforeach
                                        
                                    </select>
                                    
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('stage.parent_stage_id') }}</span>
                            </div>
                            
                    
                    </div>   
                    <div class="row mt-4">  
                            <div class="col-5">
                                 <div class="custom-control custom-checkbox align-text-bottom">
                                                <input type="checkbox"
                                                       checked
                                                       class="custom-control-input"
                                                       id="is_required"
                                                       name="stage[is_required]">
                                                <label class="custom-control-label"
                                                       for="is_required">@lang('site::academy.stage.is_required')</label>
                                                
                                            </div>
                            
                            </div>                    
                    </div>                    
                            
                </form>
					 
                <hr/>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('academy-admin.stages.index') }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>

 try {
        window.addEventListener('load', function () {

            let presentation = $('#presentation_id'),
                presentations_search = $('#presentations_search'),
                presentations = $('#presentations'),
                selected = [];
            
            $(document)
                .on('click', '.presentation-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.presentation-' + $(this).data('id')).remove();
                   
                }))
                
            presentations_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/stage-presentations',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_presentation]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (presentation) {
                    if (presentation.loading) return "...";
                    let markup = presentation.name;
                    return markup;
                },
                templateSelection: function (presentation) {
                    console.log(presentation);
                    if (presentation.id !== "") {
                        return presentation.name;
                    } else {
                    return "-- выберите презентацию --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            presentations_search.on('select2:select', function (e) {
                let presentation_id = $(this).find('option:selected').val();
                if (!selected.includes(presentation_id)) {
                    presentations_search.removeClass('is-invalid');
                    selected.push(presentation_id);
                    axios
                        .get("/api/stage-presentations/create/" + presentation_id)
                        .then((response) => {

                            presentations.append(response.data);
                            $('[name=presentation_id]').focus();
                            presentations_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    presentations_search.addClass('is-invalid');
                }
            });


            let video = $('#video_id'),
                videos_search = $('#videos_search'),
                videos = $('#videos');
                selected.length = 0;
           
            $(document)
                .on('click', '.video-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.video-' + $(this).data('id')).remove();
                   
                }))
              
            videos_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/stage-videos',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_video]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (video) {
                    if (video.loading) return "...";
                    let markup = video.name;
                    return markup;
                },
                templateSelection: function (video) {
                    
                    if (video.id !== "") {
                        return video.name;
                    } else {
                    return "-- выберите видеоурок --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
             
            videos_search.on('select2:select', function (e) {
                
                let video_id = $(this).find('option:selected').val();
               
                    videos_search.removeClass('is-invalid');
                    selected.push(video_id);
                    axios
                        .get("/api/stage-videos/create/" + video_id)
                        .then((response) => {

                            videos.append(response.data);
                            console.log(response.data);
                            $('[name=video_id]').focus();
                            videos_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush