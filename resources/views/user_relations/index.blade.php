@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.relations')</li>
        </ol>

        @alert()@endalert
        <div class=" border p-3 mb-2">
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_home')</span>
            </a>
        </div>
        <div class="border p-3 mb-2">
        @lang('site::user.relation_faq')
        </div>
        
        <div class="border p-3 mb-2">
        <h5>Добавление привязки Вашего пользователя к зарегистрированной компании.</h5>
        <form id="register-form" action="{{ route('user_relations.create') }}">
                    @csrf
                    @method('PUT')
            <div class="row">
                <div class="col">
                    <span id="usersHelp" class="d-block form-text text-success">
                        Введите название или ИНН Вашей компании и выберите вариант из выпадающего списка.
                    </span>
                    <fieldset id="users-search-fieldset"
                              style="display: block; padding-left: 5px;">
                        <div class="form-row">
                            <select class="form-control" id="users_search"  name="contact[user_id]">
                                <option></option>
                            </select>
                            
                        </div>
                    </fieldset>
                </div>
            </div>
            <button class="btn btn-ms" type="submit">@lang('site::user.relation_request_send')</button>
         </form>
        </div>
        
        
        @if($user->type_id!=3)
        <div class="border p-3 mb-2">
        <h5>Заявки на должность от Ваших сотрудников</h5>
        
        @foreach($userRelationChilds as $userRelationChild)
        
            
            @foreach($userRelationChild->child->UserFlRoleRequests()->where('accepted','0')->where('decline','0')->get() as $rr)
            <div class="row"> 
             <div class="col-3 mb-1"> {{$userRelationChild->child->name}}</div>
             <div class="col-2 mb-1"> {{$rr->role->description}} </div>
             <div class="col-6 mb-1"> <form class="d-sm-inline-block" action="{{route('user-relations.update',$userRelationChild)}}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" class="form-control" name="roleRequest[role_id]" value="{{$rr->role->id}}"/>
                                            <input type="hidden" class="form-control" name="roleRequest[role_name]" value="{{$rr->role->name}}"/>
                                            <input type="hidden" class="form-control" name="roleRequest[role_request_id]" value="{{$rr->id}}"/>
                                            <button class="btn btn-primary" name="roleRequest[accepted]" value="1" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить должность">
                                            Подтвердить должность</button>
                                            <button class="btn btn-danger" name="roleRequest[decline]" value="1" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить должность">
                                            Отклонить</button>
                                        
                                            </form>
            </div> </div>
            @endforeach
        
        @endforeach
       
        </div>
        @endif
        
        
        @if($user->type_id==3)
        <div class="border p-3 mb-2">
        <h5>Должность или специализация</h5>
        <div class="row">
            @foreach($roles_fl as $role_fl)
               
                <div class="col-sm-3">
                    <span style=" @if(!$user->hasRole($role_fl->name)) text-decoration: line-through;@endif font-size: 1.5em; margin-bottom: 0.5em" > @lang('site::user.roles.' .$role_fl->name)
                    </span>
                    @if(!$user->hasRole($role_fl->name))
                        <form action="{{ route('user_relations.create') }}">
                        <input type="hidden" class="form-control" name="role" value="{{$role_fl->id}}"/>
                        <button class="btn btn-ms" 
                            @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('decline','0')->first())) 
                                disabled 
                            @endif
                            @if(!$user->parents()->exists())
                                disabled
                            @endif
                        type="submit">@lang('site::user.relation_request_send')</button> 
                        
                        </form>
                            @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()))
                            Заявка отправлена <br />{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',0)->where('decline','0')->first()->created_at}}
                        @endif
                        @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',1)->where('decline','0')->first()))
                            Заявка одобрена <br />{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('accepted',1)->where('decline','0')->first()->created_at}}
                        @endif
                        @if(!empty($user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('decline','1')->first()))
                            Заявка отклонена <br />{{$user->UserFlRoleRequests()->where('role_id',$role_fl->id)->where('decline','1')->first()->created_at}}
                        @endif
                        @if(!$user->parents()->exists())
                                Сначала отправьте заявку на привязку к Вашей компании
                            @endif
                    @else
                        <div class="d-block form-text text-success">Должность подтверждена</div>
                    
                    @endif
                </div>
                
             
            @endforeach
        </div>
        </div>
        @endif
        
        
       <div class="card-deck mb-4">
        <table class="table table-bordered bg-white table-sm table-hover ml-3"><thead>
        <th colspan=3>@lang('site::user.relation_childs')</th>
        </thead>
        <tbody>
            @foreach($userRelationChilds as $userRelationChild)
            <tr id="relation-{{$userRelationChild->id}}">
                
                  <td class="align-middle"> {{$userRelationChild->child->name}}</td>
                  
                  <td class="align-middle"> @foreach($userRelationChild->child->roles as $role) {{$role->description}}<br /> @endforeach</td>
                       
                      <td class="align-middle"> <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::user.relation_accepted_help')"> @if($userRelationChild->accepted)
                        @lang('site::user.relation_accepted')
                        @else
                        @lang('site::user.relation_not_accepted')
                        @endif
                        @bool(['bool' => $userRelationChild->accepted])@endbool </span>
                       </td>            
                    
                       <td class="align-middle"> 
                      @if(!$userRelationChild->accepted) 
                        <form class="d-sm-inline-block" action="{{route('user-relations.update',$userRelationChild)}}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" class="form-control" name="userRelation[accepted]" value="1"/>
                        <button class="btn btn-primary" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="Подтвердить связь">@lang('site::user.relation_accept')</button>
                    
                        </form>
                       @endif
                       <a class="btn btn-danger btn-row-delete"
                           title="@lang('site::messages.delete')"
                           href="javascript:void(0);"
                           data-form="#relation-delete-form-{{$userRelationChild->id}}"
                           data-btn-delete="@lang('site::messages.delete')"
                           data-btn-cancel="@lang('site::messages.cancel')"
                           data-label="@lang('site::messages.delete_confirm')"
                           data-message="@lang('site::messages.delete_sure') {{ $userRelationChild->child->name }}?"
                           data-toggle="modal"
                           data-target="#form-modal">
                            <i class="fa fa-close"></i>
                            @lang('site::messages.delete')
                        </a>
                        <form id="relation-delete-form-{{$userRelationChild->id}}"
                              action="{{route('user-relations.destroy', $userRelationChild)}}"
                              method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                        </td>
                
             </tr>   
            @endforeach
            </tbody>
            </table>
            </div>
                  
       <div class="card-deck mb-4">
        <table class="table table-bordered bg-white table-sm table-hover ml-3"><thead>
       
        <th colspan=3>@lang('site::user.relation_parents')</th>
            @foreach($userRelationParents as $userRelationParent)
            <tr id="relation-{{$userRelationParent->id}}">
                  <td class="align-middle"> {{$userRelationParent->parent->name}}</td>
                       
                      <td class="align-middle"> <span data-toggle="tooltip" data-placement="top" data-original-title="@lang('site::user.relation_accepted_parent_help')"> @if($userRelationParent->accepted)
                            @lang('site::user.relation_accepted')
                            @else
                            @lang('site::user.relation_not_accepted')
                            @endif
                            @bool(['bool' => $userRelationParent->accepted])@endbool </span>
                       </td>            
                    
                       <td class="align-middle"> 
                           <a class="btn btn-danger btn-row-delete"
                               title="@lang('site::messages.delete')"
                               href="javascript:void(0);"
                               data-form="#relation-delete-form-{{$userRelationParent->id}}"
                               data-btn-delete="@lang('site::messages.delete')"
                               data-btn-cancel="@lang('site::messages.cancel')"
                               data-label="@lang('site::messages.delete_confirm')"
                               data-message="@lang('site::messages.delete_sure') {{ $userRelationParent->parent->name }}?"
                               data-toggle="modal"
                               data-target="#form-modal">
                                <i class="fa fa-close"></i>
                                @lang('site::messages.delete')
                            </a>
                            <form id="relation-delete-form-{{$userRelationParent->id}}"
                                  action="{{route('user-relations.destroy', $userRelationParent)}}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
             </tr>   
            @endforeach
          </tbody>  
          </table>
        </div>
        
        
    </div>
    
@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {

            let region = $('#address_sc_region_id'),
                users_search = $('#users_search'),
                users = $('#users'),
                selected = [];
            
            users_search.select2({
                theme: "bootstrap4",
                ajax: {
                    url: '/api/users',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            'filter[search_user]': params.term,
                            'filter[region_id]': region.val(),
                        };
                    },
                    processResults: function (data, params) {
                        return {
                            results: data,
                        };
                    }
                },
                
                minimumInputLength: 3,
                templateResult: function (user) {
                    if (user.loading) return "...";
                    let markup = user.name + ' (' + user.locality + ', ' + user.region_name + ')';
                    return markup;
                },
                templateSelection: function (user) {
                    if (user.id !== "") {
                        return user.name + ' (' + user.locality + ', ' + user.region_name + ')';
                    }
                },
                escapeMarkup: function (markup) {
                    return markup;
                }
            });
            users_search.on('select2:select', function (e) {
                let id = $(this).find('option:selected').val();
                if (!selected.includes(id)) {
                    users_search.removeClass('is-invalid');
                    selected.push(id+'aaaaaa');
                } else {
                    users_search.addClass('is-invalid');
                }
            });


        });
    } catch (e) {
        console.log(e);
    }

</script>
@endpush
@endsection
