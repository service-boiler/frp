@extends('layouts.app')
@section('title')@lang('site::messages.add') @lang('site::academy.program.add')@lang('site::messages.title_separator')@endsection
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
                <a href="{{ route('academy-admin.programs.index') }}">@lang('site::academy.program.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::academy.program.add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('academy-admin.programs.store') }}">
                    @csrf
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.program.name')</label>
                                    <input type="text" name="program[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                            
                    <div class="form-group" id="form-group-theme_id">
                                <label class="control-label" for="theme_id">@lang('site::academy.program.theme')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('program.theme_id') ? ' is-invalid' : '' }}"
                                            name="program[theme_id]"
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
                                <span class="invalid-feedback">{{ $errors->first('program.theme_id') }}</span>
                    </div>       
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.program.annotation')</label>
                                    <input type="text" name="program[annotation]"
                                           id="annotation"
                                           class="form-control{{ $errors->has('annotation') ? ' is-invalid' : '' }}"
                                           value="{{ old('program[annotation]') }}">
                                    <span class="invalid-feedback">{{ $errors->first('annotation') }}</span>
                                </div>
                    </div>
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="bonuses">@lang('site::academy.program.bonuses')</label>
                                    <input type="number" name="program[bonuses]"
                                           id="bonuses"
                                           class="form-control{{ $errors->has('program.bonuses') ? ' is-invalid' : '' }}"
                                           >
                                    <span class="invalid-feedback">{{ $errors->first('program.bonuses') }}</span>
                                </div>
                    </div>
                    <div class="row">        
                            <div class="col-5">
                                <fieldset class="border p-3" id="stages-search-fieldset">
                                    <div class="form-group">
                                        <label class="control-label"
                                                   for="stages_search">@lang('site::academy.program.add_stage')</label>
                                            <select data-limit="30" id="stages_search" class="form-control">
                                                <option value=""></option>
                                            </select>
                                            
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="">@lang('site::academy.program.stages')</label>
                                    <div class="list-group" id="stages">
                                        @foreach($stages as $stage)
                                            @include('site::academy-admin.program-stage.create', ['stage' => $stage])
                                        @endforeach
                                    </div>
                                   
                                </div>
                                @lang('site::academy.program.sort_help_create')
                            </div>
                    
                    </div>       
                           
                    <div class="row mt-4">  
                            <div class="col-5">
                                 <div class="custom-control custom-checkbox align-text-bottom">
                                                <input type="checkbox"
                                                       checked
                                                       class="custom-control-input"
                                                       id="enabled"
                                                       name="program[enabled]">
                                                <label class="custom-control-label"
                                                       for="enabled">@lang('site::academy.program.enabled')</label>
                                                
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
                        <a href="{{ route('academy-admin.programs.index') }}" class="btn btn-secondary mb-1">
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

            let stage = $('#stage_id'),
                stages_search = $('#stages_search'),
                stages = $('#stages'),
                selected = [];
            
            $(document)
                .on('click', '.stage-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.stage-' + $(this).data('id')).remove();
                   
                }))
                
            stages_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/program-stages',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_stage]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (stage) {
                    if (stage.loading) return "...";
                    let markup = stage.name;
                    return markup;
                },
                templateSelection: function (stage) {
                    console.log(stage);
                    if (stage.id !== "") {
                        return stage.name;
                    } else {
                    return "-- выберите этап обучения --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            stages_search.on('select2:select', function (e) {
                let stage_id = $(this).find('option:selected').val();
                if (!selected.includes(stage_id)) {
                    stages_search.removeClass('is-invalid');
                    selected.push(stage_id);
                    axios
                        .get("/api/program-stages/create/" + stage_id)
                        .then((response) => {

                            stages.append(response.data);
                            $('[name=stage_id]').focus();
                            stages_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    stages_search.addClass('is-invalid');
                }
            });


           


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush