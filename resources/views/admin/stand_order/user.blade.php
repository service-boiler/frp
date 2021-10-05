@extends('layouts.app')
@section('title')Заказ на витрину выбор дилера@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.stand-orders.index') }}">@lang('site::stand_order.orders')</a>
            </li>
            <li class="breadcrumb-item active">Новый заказ на витрину выбор дилера</li>
        </ol>
        <h1 class="header-title mb-4">Создать новый заказ на витрину</h1>
        @alert()@endalert()

        <div class="card mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                    <label class="control-label" for="user_id">Дилер (кому будут поставлятся витринные образцы)</label>
                    <span id="usersHelp" class="d-block form-text text-success">
                        Введите название или ИНН и выберите вариант из выпадающего списка.
                    </span>
                                <fieldset id="users-search-fieldset"
                                          style="display: block; padding-left: 5px;">
                                    <div class="form-row">
                                        <select class="form-control" id="users_search"  name="[user_id]">
                                        @if(old('user_id')) 
                                        <option selected value="{{old('user_id')}}"> {{old('user_id')}}</option>@endif
                                            <option></option>
                                        </select>
                                        
                                    </div>
                                </fieldset>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col">
                    <a disabled href="#" class="disabled btn btn-secondary" id="create_button">Далее</a>
                    </div>
                </div>
               
            </div>
        </div>

@endsection

@push('scripts')
<script>
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
    try {
        window.addEventListener('load', function () {
           
           let users_search = $('#users_search'),
            selected = [];
                    
                    users_search.select2({
                        theme: "bootstrap4",
                        ajax: {
                            url: '/api/user-search',
                            dataType: 'json',
                            delay: 250,
                            data: function (params) {
                                return {
                                    'filter[search_user]': params.term,
                                    
                                };
                            },
                            processResults: function (data, params) {
                                return {
                                    results: data.data,
                                };
                            }
                        },
                        
                        minimumInputLength: 3,
                        templateResult: function (user) {
                            if (user.loading) return "...";
                            let markup = user.name;
                            return markup;
                        },
                        templateSelection: function (user) {
                            if (user.id !== "") {
                                return user.name;
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
                            selected.push(id);
                        } else {
                            users_search.addClass('is-invalid');
                        }
                        $('#create_button').replaceWith('<a href="/admin/stand-orders/create/'+ id +'" class="btn btn-ms" id="create_button"> Далее</a>');
                    });

        
        
        });
        
                
        
        
  
            
        
    } catch (e) {
        console.log(e);
    }

</script>
@endpush