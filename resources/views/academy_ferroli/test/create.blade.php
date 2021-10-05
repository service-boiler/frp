@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.tests.index') }}">@lang('site::academy.test.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.add')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::academy.test.add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('academy-admin.tests.store') }}">
                    @csrf
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.test.name')</label>
                                    <input type="text" name="academyTest[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
                    </div>
                            
                    <div class="form-group" id="form-group-theme_id">
                                <label class="control-label" for="theme_id">@lang('site::academy.test.theme')</label>
                                <div class="input-group">
                                    <select class="form-control{{  $errors->has('academyTest.theme_id') ? ' is-invalid' : '' }}"
                                            name="academyTest[theme_id]"
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
                                <span class="invalid-feedback">{{ $errors->first('academyTest.theme_id') }}</span>
                    </div>       
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::academy.test.annotation')</label>
                                    <input type="text" name="academyTest[annotation]"
                                           id="annotation"
                                           class="form-control{{ $errors->has('annotation') ? ' is-invalid' : '' }}"
                                           value="{{ old('academyTest[annotation]') }}">
                                    <span class="invalid-feedback">{{ $errors->first('annotation') }}</span>
                                </div>
                    </div>
                    
                    <div class="row mb-3">        
                            <div class="col-4">
                                <label class="control-label" for="name">@lang('site::academy.test.count_questions')</label>
                            </div>
                            <div class="col-1"> 
                                <input type="number" name="academyTest[count_questions]"
                                           id="count_questions"
                                           class="form-control {{ $errors->has('count_questions') ? ' is-invalid' : '' }}"
                                           value="{{ old('academyTest[count_questions]', '10') }}">
                                <span class="invalid-feedback">{{ $errors->first('count_questions') }}</span>
                            </div>
                    </div>
                    <div class="row mb-3">        
                            <div class="col-4">
                                <label class="control-label" for="name">@lang('site::academy.test.count_correct')</label>
                            </div>
                            <div class="col-1"> 
                                <input type="number" name="academyTest[count_correct]"
                                           id="count_correct"
                                           class="form-control {{ $errors->has('count_correct') ? ' is-invalid' : '' }}"
                                           value="{{ old('academyTest[count_correct]', '8') }}">
                                <span class="invalid-feedback">{{ $errors->first('count_correct') }}</span>
                            </div>
                    </div>
                    <div class="row">        
                            <div class="col-5">
                                <fieldset class="border p-3" id="questions-search-fieldset">
                                    <div class="form-group required">
                                        <label class="control-label"
                                                   for="questions_search">@lang('site::academy.add_question')</label>
                                            <select data-limit="10" id="questions_search" class="form-control">
                                                <option value=""></option>
                                            </select>
                                            <span id="questions_searchHelp" class="d-block form-text text-success">
                                            @lang('site::academy.question_help')
                                        </span>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="col-7">
                                <div class="form-group">
                                    <label class="control-label"
                                           for="">@lang('site::academy.test.questions')</label>
                                    <div class="list-group" id="questions">
                                        @foreach($questions as $question)
                                            @include('site::academy-admin.test-question.create', ['question' => $question])
                                        @endforeach
                                    </div>
                                   
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
                        <a href="{{ route('academy-admin.tests.index') }}" class="btn btn-secondary mb-1">
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

            let question = $('#question_id'),
                questions_search = $('#questions_search'),
                questions = $('#questions'),
                selected = [];
            
            $(document)
                .on('click', '.question-delete', (function () {
                    let index = selected.indexOf($(this).data('id'));
                    
                        selected.splice(index, 1);
                        $('.question-' + $(this).data('id')).remove();
                   
                }))
                
            questions_search.select2({
                theme: "bootstrap4",
                placeholder: "-- Выбрать --",
                ajax: {
                    url: '/api/test-questions',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_question]': params.term,
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.data,
                        };
                    }
                },
                minimumInputLength: 3,
                templateResult: function (question) {
                    if (question.loading) return "...";
                    let markup = question.text;
                    return markup;
                },
                templateSelection: function (question) {
                    console.log(question);
                    if (question.id !== "") {
                        return question.text;
                    } else {
                    return "-- выберите вопрос --";
                    }


                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            questions_search.on('select2:select', function (e) {
                let question_id = $(this).find('option:selected').val();
                if (!selected.includes(question_id)) {
                    questions_search.removeClass('is-invalid');
                    selected.push(question_id);
                    axios
                        .get("/api/test-questions/create/" + question_id)
                        .then((response) => {

                            questions.append(response.data);
                            $('[name=question_id]').focus();
                            questions_search.val(null)
                        })
                        .catch((error) => {
                            this.status = 'Error:' + error;
                        });
                } else {
                    questions_search.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush