@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-contract-templates.index') }}">@lang('site::user.esb_contract_template.index')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.add') @lang('site::user.esb_contract_template.contract_template')</h1>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">
                <form id="form" method="POST" action="{{ route('esb-contract-templates.update',$esbContractTemplate) }}">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-6">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('esb_contract_template.enabled',$esbContractTemplate->enabled)) checked @endif
                                       class="custom-control-input{{  $errors->has('esb_contract_template.enabled') ? ' is-invalid' : '' }}"
                                       id="enabled"
                                       name="esb_contract_template[enabled]">
                                <label class="custom-control-label"
                                       for="enabled">@lang('site::user.esb_contract_template.enabled')</label>
                                <span class="invalid-feedback">{{ $errors->first('esb_contract_template.enabled') }}</span>
                            </div>
                        </div>
                        @if(auth()->user()->hasPermission('admin_esb_super') || auth()->user()->admin)
                            <div class="col-6">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           @if(old('esb_contract_template.shared',$esbContractTemplate->shared)) checked @endif
                                           class="custom-control-input{{  $errors->has('esb_contract_template.shared') ? ' is-invalid' : '' }}"
                                           id="shared"
                                           name="esb_contract_template[shared]">
                                    <label class="custom-control-label"
                                           for="shared">@lang('site::user.esb_contract_template.shared')</label>
                                    <span class="invalid-feedback">{{ $errors->first('esb_contract_template.shared') }}</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="form-row required mt-3">
                        <div class="col">
                            <label class="control-label" for="name">@lang('site::user.esb_contract_template.name')</label>
                            <input type="text"
                                   name="esb_contract_template[name]"
                                   id="name" required
                                   class="form-control{{ $errors->has('esb_contract_template.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.esb_contract_template.name_placeholder')"
                                   value="{{ old('esb_contract_template.name',$esbContractTemplate->name) }}">
                            <span class="invalid-feedback">{{ $errors->first('esb_contract_template.name') }}</span>
                        </div>
                    </div>

                    <div class="form-row required mt-3">
                        <div class="col">
                            <label class="control-label" for="prefix">@lang('site::user.esb_contract_template.prefix')</label>
                            <input type="text"
                                   name="esb_contract_template[prefix]"
                                   id="prefix"
                                   required
                                   class="form-control{{ $errors->has('esb_contract_template.prefix') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::user.esb_contract_template.prefix_placeholder')"
                                   value="{{ old('esb_contract_template.prefix',$esbContractTemplate->prefix) }}">
                            <span class="invalid-feedback">{{ $errors->first('esb_contract_template.prefix') }}</span>
                        </div>
                    </div>
                </form>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="form-row mt-2">
                            <div class="col">
                                <label class="control-label" class="control-label"
                                       for="file_id">@lang('site::user.esb_contract_template.file_id')</label>

                                <form method="POST" enctype="multipart/form-data"
                                      action="{{route('files.store-single')}}">
                                    @csrf
                                    <input type="hidden"
                                           name="type_id"
                                           value="35"/>
                                    <input type="hidden"
                                           name="storage"
                                           value="esb_contract_templates"/>
                                    <input class="d-inline-block form-control-file{{ $errors->has('file_id') ? ' is-invalid' : '' }}"
                                           type="file"
                                           accept="{{config('site.esb_contract_templates.accept')}}"
                                           name="path"/>

                                    <input type="button" class="btn btn-ms file-upload-button"
                                           value="@lang('site::messages.load')"/>
                                    <span class="invalid-feedback">{{ $errors->first('file_id') }}</span>
                                </form>


                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="files" class="row bg-white">
                            @include('site::admin.file.edit')
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-row">
                    <div class="col-sm-6">
                        <span class="font-weight-bold">Предопределенные поля для подстановки в шаблон:</span>
                        <dl class="row mt-3">
                            @foreach($presetFields as $field)
                                <dt class="col-sm-4 text-left text-sm-right">{{'${'.$field->name.'}'}}</dt>
                                <dd class="col-sm-8">{{$field->title}}</dd>
                            @endforeach
                        </dl>
                    </div>
                    <div class="col-sm-6">
                        <span class="font-weight-bold">Дополнительные поля для подстановки в шаблон:</span>
                        <dl class="row mt-3" id="template-fileds">
                            @foreach($esbContractTemplate->esbContractFields as $field)
                                <dt class="col-sm-4 text-left text-sm-right field-{{$field->id}}">
                                    <span class="text-danger delete-field" data-field="{{$field->id}}"><i class="fa fa-close"></i></span> &nbsp; &nbsp;{{'${'.$field->name.'}'}}</dt>
                                <dd class="col-sm-8 field-{{$field->id}}">{{$field->title}}
                                    <input type="hidden" form="form" name="templfields[{{$field->id}}][name]" value="{{$field->name}}">
                                    <input type="hidden"  form="form" name="templfields[{{$field->id}}][title]" value="{{$field->title}}">
                                </dd>
                            @endforeach
                        </dl>
                        <div class="form-row required mt-3">
                            <div class="col-sm-6">
                                <label class="control-label" for="add_field_name">Код без фигурных скобок и пробелов</label>
                                <input type="text" form="add_field" name="[add_field_name]" id="add_field_name" class="form-control" value="" placeholder="например: новое_поле">

                            </div>
                            <div class="col-sm-6">
                                <label class="control-label" for="add_field_title">Заголовок (комментарий)</label>
                                <input type="text" form="add_field" name="[add_field_title]" id="add_field_title" class="form-control" value="" placeholder="например: новое_поле">

                            </div>
                            <div class="col-sm-6">
                                <button id="add_field_btn" class="btn btn-primary" type="button"><i class="fa fa-level-up"></i> Добавить поле</button>


                            </div>
                            <span id="field-exists" class="text-danger mt-3 d-none">Такое предопределенное поле существует</span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-row">
                    <div class="col text-right">
                        <button form="form" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>
                        <a href="{{ route('esb-contract-templates.index') }}" class="btn btn-secondary mb-1">
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
                $(document)
                    .on('click', '.delete-field', (function(I){
                        $('.field-'+$(this)[0].dataset.field).remove();
                    }))

                    .on('click', '#add_field_btn', (function(I){
                            let add_field_name=$('#add_field_name').val();
                            let add_field_title=$('#add_field_title').val();
                            if(add_field_name) {
                                axios

                                    .post("/api/esb-contract-template-add-field", {"add_field_name": add_field_name, "add_field_title": add_field_title})
                                    .then((response) => {
                                        if(response.data['exists']) {
                                            $('#field-exists').removeClass('d-none')
                                        } else {
                                            $('#template-fileds').append(response.data);
                                            $('#field-exists').addClass('d-none')
                                        }

                                    })
                                    .catch((error) => {
                                        this.status = 'Error:' + error;
                                    });
                            }

                        })
                    );



            });

        } catch (e) {
            console.log(e);
        }

    </script>
@endpush
