@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('academy-admin.questions.index') }}">@lang('site::academy.question.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') @lang('site::academy.question.add')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form" method="POST" action="{{ route('academy-admin.questions.update', $question) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="question[id]" value="{{$question->id}}">
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="text">@lang('site::academy.question.name')</label>
                                    <input type="text" name="question[text]"
                                           id="text"
                                           class="form-control{{ $errors->has('question.text') ? ' is-invalid' : '' }}"
                                           value="{{ old('question.text', $question->text) }}">
                                    <span class="invalid-feedback">{{ $errors->first('question.text') }}</span>
                                </div>
                    </div>
                    <div class="form-row ml-2">
                     @lang('site::academy.question.answers')
                     </div>
                     <div class="form-row ml-2 mt-2">
                     <div class="col-1">
                        @lang('site::academy.answer.is_correct')
                     </div>
                     </div>
                    <fieldset id="answers-list">
                        @if( is_array(old('answer')) )
                            @foreach(old('answer') as $random => $answer)
                                @include('site::academy-admin.answer.create', compact('random'))
                            @endforeach
                        @else
                        @foreach($answers as $answer)
                                @include('site::academy-admin.answer.row', compact('answer'))
                            @endforeach
                        @endif
                    </fieldset>

                    <div class="form-row mt-3">
                        <div class="col text-left">

                            <a href="javascript:void(0);" class="btn btn-ms mb-1 answer-add"
                               data-action="{{route('academy-admin.answers.create')}}">
                                <i class="fa fa-plus"></i>
                                <span>@lang('site::messages.add') @lang('site::academy.answer.answer_add')</span>
                            </a>
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
                        <a href="{{ route('academy-admin.questions.index') }}" class="btn btn-secondary mb-1">
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

@endpush
