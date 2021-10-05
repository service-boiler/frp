@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>


            <li class="breadcrumb-item active">@lang('site::messages.create') рассылку сообщений в ЛК</li>
        </ol>
    
        @alert()@endalert

        @filter(['repository' => $repository])@endfilter

        <div class="card mb-2">
            <div class="card-body text-muted" id="summernote">
                <form id="form-content"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.messagings.store') }}">

                    @csrf


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
<!--
                    <h4>@lang('site::mailing.attachment')</h4>

                    <div class="form-row required">
                        <div class="col mb-3">
                            <div class="form-group">
                                <label for="attachment">@lang('site::mailing.help.attachment')</label>
                                <input type="file"
                                       multiple
                                       name="attachment[]"
                                       class="form-control-file{{ $errors->has('attachment.*') ? ' is-invalid' : '' }}"
                                       id="attachment">
                                <span class="invalid-feedback">{{ $errors->first('attachment.*') }}</span>
                            </div>
                        </div>
                    </div>
-->
                </form>

                <hr/>

                <div class="form-row">
                    <div class="col text-right">
                        <button form="form-content" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-send"></i>
                            <span>@lang('site::messages.send')</span>
                        </button>
                        <a href="{{ $route }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>
                    </div>
                </div>

                <h4>@lang('site::mailing.header.recipients')</h4>

                <table class="table bg-white table-hover">
                    <thead>
                    <tr>
                        <th>
                            <div class="form-check">
                                <input type="checkbox"
                                       id="mark_all"
                                       checked
                                       class="form-check-input"/>
                                <label for="mark_all" class="form-check-label">@lang('site::messages.mark') @lang('site::messages.all') / @lang('site::messages.unmark')</label>
                            </div>
                        </th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <div class="form-check">
                                    <input type="checkbox"
                                           name="recipient[]"
                                           value="{{$user->id}}"
                                           @if(!$errors->has('recipient') && (empty(old('recipient')) || in_array($user->id, old('recipient'))))
                                           checked
                                           form="form-content"
                                           @endif
                                           class="form-check-input" id="recipient-{{$user->id}}">
                                    <label class="form-check-label"
                                           for="recipient-{{$user->id}}">{{$user->name}} @if($user->region)({{$user->region->name}})@endif</label>
                                </div>
                            </td>
                            
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                @if($errors->has('recipient'))
                    <span class="text-danger">{{ $errors->first('recipient') }}</span>
                @endif

                <hr/>

                <p class="bg-light p-3" id="emails"></p>

            </div>
        </div>
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

                checkEmails();

                $('body')
                    .on('click', 'input[name="recipient[]"]', function (e) {
                        checkEmails();
                    })
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
