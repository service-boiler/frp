@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">Предварительная регистрация монтажников</li>
        </ol>

        @alert()@endalert()
        <div class="justify-content-start border p-3 mb-2">
            <div class="row">
            <div class="col-3">
            <a class="btn btn-ms"
               href="{{ route('ferroli-user.user_prereg.create_mounters') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') предрегистрацию</span>
            </a>
            
            
            </div>
            
                           
                            <div class="col-2">
                                    <button form="xls" type="submit" class="btn btn-ms">
                                        <i class="fa fa-download"></i>
                                        <span>@lang('site::messages.load') из Excel</span>
                                    </button>
                                    
                                
                            </div>
                            <div class="col-7">
                                <form id="xls" enctype="multipart/form-data" action="{{route('ferroli-user.user_prereg.userpreregs_mounters_xls')}}" method="post">
                                    @csrf
                                    <div class="form-group mb-0">
                                    <input type="file"
                                           name="path"
                                           accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                           class="{{  $errors->has('path') ? ' is-invalid' : '' }} mb-0"
                                           id="path">
                                    <span class="invalid-feedback {{  $errors->has('path') ? ' d-block' : '' }}">{!! $errors->first('path') !!}</span>
                                    <span id="pathHelp" class="d-block form-text text-success">
                                        Выберите Excel файл и нажмите кнопку "Загрузить. <a href="/up/preregs.xlsx">Пример xlsx</a>
                                    </span>
                                    </div>
                                </form>
                            </div>
                        
            
        </div>   
        </div>   @filter(['repository' => $repository])@endfilter

      
        
         <form id="form-content"  method="POST" action="{{ route('ferroli-user.user_prereg.invite_mounters') }}">

                    @csrf
        <div class="row mb-3 mt-3">
            <div class="col-12 ml-4">
                <input type="checkbox" id="mark_all" checked class="form-check-input"/>
                <label for="mark_all" class="form-check-label">@lang('site::messages.mark') @lang('site::messages.all') / @lang('site::messages.unmark'). <span class="text-success">Для рассылки выбранным пользователям заполните форму ниже.</span></label>
            </div>
        </div>
         <div class="row mb-2 font-weight-bold">
            <div class="col-1 ml-4">Статусы</span></div>
            <div class="col-3">ФИО</div>
            <div class="col-2">Телефон, email</div>
            <div class="col-3">Регион, город</div>
            <div class="col-2">Компания</div>
            
        </div>
        @foreach($userPreregs as $prereg)
            <div class="row mb-2" id="prereg-{{$prereg->id}}">
                <div class="col-1 ml-4">
                    @if(!$prereg->user_id)
                                <input type="checkbox"
                                       name="recipient[]"
                                       value="{{$prereg['id']}}"
                                       @if(!$prereg->invited)
                                       checked
                                       form="form-content"
                                       @endif
                                       class="form-check-input" id="recipient-{{$prereg['id']}}">
                                
                    @endif
                            
                    <span class="text-normal @if($prereg->invited) text-success @else text-danger @endif">
                           <i class="fa fa-envelope" data-toggle="tooltip"
                                      data-placement="top"
                                      title="Приглашение @if(!$prereg->invited) не @endif отправлено" ></i>
                    </span>
                     @if($prereg->user_id)<i class="fa fa-user text-success"  data-toggle="tooltip"
                                      data-placement="top"
                                      title="Пользователь зарегистрировался"></i>
                                      @else <i class="fa fa-circle-o text-danger"data-toggle="tooltip"
                                      data-placement="top"
                                      title="Пользователь не зарегистрировался"></i>@endif
                     <a href="{{ route('ferroli-user.user_prereg.edit',$prereg) }}"><i class="fa fa-edit"></i></a>
                     
                    
                </div>

                    <div class="col-3">
                    @if($prereg->user_id)<i class="fa fa-user text-success"></i>
                    <a href="{{route('admin.users.show',$prereg->user_id)}}" class="mr-3 ml-0"> 
                    @endif
                        {{$prereg->lastname}} {{$prereg->firstname}} {{$prereg->middlename}} 
                    @if($prereg->user_id)                      
                     </a>
                    @endif
                    @if($prereg->role)<br />{{$prereg->role->title}}@endif
                    </div>
                    <div class="col-2">
                    {{$prereg->phone}}
                     <br/>
                    {{$prereg->email}}
                    </div>
                    <div class="col-3">
                    @if(!empty($prereg->region)){{$prereg->region->name}} @endif<br/> {{$prereg->locality}}
                    </div>
                    <div class="col-2">
                    @if(!empty($prereg->parentUser)){{$prereg->parentUser->name }}@else {{$prereg->parent_name}} @endif
                    </div>
            </div>
        @endforeach
        
        
        <hr />
        <h3 class="mt-5 mb-0 font-weight-bold">Рассылка приглашений завершить регистрацию</h3>
       
            <div class="card mb-2">
        <div class="card-body text-muted" id="summernote">        
           
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::mailing.title')</label>
                                    <input type="text"
                                           name="title"
                                           id="title"

                                           class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::mailing.placeholder.title')"
                                           value="{{ old('title') }}">
                                    <span class="invalid-feedback">{{ $errors->first('title') }}</span>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3 required">

                                    <label class="control-label"
                                           for="template_id">@lang('site::template.help.load_from_template')</label>
                                    <select class="form-control"
                                            name="template_id"
                                            id="template_id">
                                        <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($templates as $template)
                                            <option
                                                    data-action="{{route('admin.templates.show', $template)}}"
                                                    @if(old('template_id') == $template->id)
                                                    selected
                                                    @endif
                                                    value="{{ $template->id }}">{{ $template->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label" for="content">@lang('site::mailing.content')</label>
                            <textarea id="content"
                                      class="summernote form-control{{ $errors->has('content') ? ' is-invalid' : '' }}"
                                      placeholder="@lang('site::mailing.placeholder.content')"
                                      name="content">{{ old('content') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('content') }}</span>
                        </div>
                    </div>
                   <div class="row">
                        <div class="col">
                    <span class="text-success">Письмо будет дополнено строками:</span> <br />
                    <span class="font-weight-bold">Для завершения регистрации перейдите по ссылке и дополните необходимые данные: <br />
                        http://service.ferroli.ru.test/register/prereg/cc35453c-34dc-40bf-8b8b-c7fe28f4dffa </span>
                        </div>
                    </div>
                   <div class="row mt-3">
                        <div class="col">
                    
             <button form="form-content" type="submit" class="btn btn-ms d-page d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0">
                            <i class="fa fa-envelope"></i>
                            <span>Отправить приглашение выбранным</span>
                        </button>
                    </div>
                    </div>
                        
                    
        </div>
    </div>
        
        
        
        
        
        </form>
        
    </div>
@endsection

@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {
            $(document).ready(function () {
                checkEmails = function () {
                    let emails = [];
                    $('input[name="recipient[]"]:checked').each(function (i, e) {
                        emails.push($(e).val());
                    });
                    $('#emails').html(emails.join(', '));
                };

               // checkEmails();

                $('body')
                    
                    .on('change', '#mark_all', function () {
                        $("input:checkbox").prop('checked', $(this).prop("checked"));
                    })
                    .on('click', '.attachment-add', function () {

                        let list = $('#attachments-list'),
                            action = $(this).data('action');

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: action,
                            type: 'GET',
                            dataType: 'html',
                            contentType: false,
                            processData: false,
                            success: function (response) {
                                list.append(response);
                            },
                        });
                    })
                    .on('change', '#template_id', function (e) {
                        let select = $(this), option = select.find('option:selected');

                        if (option.val() !== '') {

                            $.ajax({
                                type: 'GET',
                                dataType: 'json',
                                url: option.data('action'),
                                data: [],
                                success: function (template) {
                                    if (template !== false && template !== null) {
                                        $('#title').val(template.title);
                                        $('#content').summernote('code', template.content);
                                    }
                                }
                            });
                        }
                    })
                    
                    
            });
        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush
