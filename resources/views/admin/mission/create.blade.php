@extends('layouts.app')
@section('title')@lang('site::admin.mission.add')@lang('site::messages.title_separator')@endsection
@section('content')
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.home')</a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{ route('admin.missions.index') }}">@lang('site::admin.mission.index')</a>
        </li>
        <li class="breadcrumb-item active">@lang('site::messages.add')</li>
    </ol>
  
    @alert()@endalert

<form id="form" method="POST" action="{{ route('admin.missions.store') }}">
@csrf
    <div class="card mb-5">
        <div class="card-body">
            
                     
            <div class="form-row">
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="date_from">@lang('site::admin.mission.date_from')</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_from"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="mission[date_from]"
                                           id="date_from"
                                           maxlength="10"
                                           required
                                          
                                           data-target="#datetimepicker_date_from"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('mission.date_from') ? ' is-invalid' : '' }}"
                                           value="{{ old('mission.date_from') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_from"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>       
                </div>     
                <div class="col-md-3">
                    <div class="form-row">
                        <div class="col">
                            <label class="control-label" for="date_to">@lang('site::admin.mission.date_to')</label>
                            <div class="input-group date datetimepicker" id="datetimepicker_date_to"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="mission[date_to]"
                                           id="date_to"
                                           maxlength="10"
                                           required
                                          
                                           data-target="#datetimepicker_date_to"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('mission.date_to') ? ' is-invalid' : '' }}"
                                           value="{{ old('mission.date_to') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_date_to"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>       
                </div> 
                

                                   
            </div>       
                     
            
            <div class="row required">        
                <div class="col-6 required">
                    <label class="control-label"
                                       for="search_address">Город<br/><span class="text-success text-small">Начните ввод и выберите из списка</span></label>
                            <input class="form-control" type="text" name="search_address" id="search_address" value="" autocomplete="off">
                        
                        
                    <div class="ml-3 mt-5" id="search_address_wrapper"></div>
                </div>
                
                <div class="col-6 required">

                            <label class="control-label" for="region_id">Регион<br /><span class="text-success text-small">Установится автоматически после выбора города</span></label>
                            <select class="form-control{{  $errors->has('mission.region_id') ? ' is-invalid' : '' }}"
                                    name="mission[region_id]"
                                    required 
                                    id="region_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($regions as $region)
                                    <option
                                            @if(old('mission.region_id') == $region->id)
                                            selected
                                            @endif
                                            value="{{ $region->id }}">{{ $region->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('mission.region_id') }}</span>
                </div>
               
                
            </div>
             
             <input type="hidden" id="locality" name="mission[locality]" value="{{ old('mission.locality') }}">
            
            
            <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="target">@lang('site::admin.mission.target')</label>
                            <textarea
                                  name="mission[target]"
                                  id="target"
                                  class="form-control{{ $errors->has('mission.target') ? ' is-invalid' : '' }}"
                                  >{{ old('mission.target') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('mission.target') }}</span>
                        </div>
            </div>   
            
            
            
            
            <div class="row">        
                
                <div class="col-4">

                            <label class="control-label" for="division_id">@lang('site::admin.mission.division')</label>
                            <select class="form-control{{  $errors->has('mission.division_id') ? ' is-invalid' : '' }}"
                                    name="mission[division_id]"
                                  
                                    id="division_id">
                                <option value="">@lang('site::messages.select_from_list')</option>
                                @foreach($divisions as $division)
                                    <option
                                            @if(old('mission.division_id') == $division->id)
                                            selected
                                            @endif
                                            value="{{ $division->id }}">{{ $division->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('mission.division_id') }}</span>
                </div>
                <div class="col-4">

                            <label class="control-label" for="project_id">@lang('site::admin.mission.project')</label>
                            <select class="form-control{{  $errors->has('mission.project_id') ? ' is-invalid' : '' }}"
                                    name="mission[project_id]"
                                  
                                    id="project_id">
                                <option value="1">Без проекта</option>
                                @foreach($projects as $project)
                                    <option
                                            @if(old('mission.project_id') == $project->id)
                                            selected
                                            @endif
                                            value="{{ $project->id }}">{{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('mission.project_id') }}</span>
                </div>
                <div class="col-2">

                            <label class="control-label" for="budget">@lang('site::admin.mission.budget')</label>
                            <input class="form-control" type="number" name="mission[budget]" value="0" >
                            <span class="invalid-feedback">{{ $errors->first('mission.budget') }}</span>
                </div>
                <div class="col-2">

                            <label class="control-label" for="budget_currency_id">@lang('site::admin.mission.budget_currency')</label>
                            <select class="form-control{{  $errors->has('mission.budget_currency_id') ? ' is-invalid' : '' }}"
                                    name="mission[budget_currency_id]"
                                  
                                    id="budget_currency_id">
                                <option value="643"  @if(old('mission.budget_currency_id') != 978) selected @endif>RUB</option>
                               
                                    <option @if(old('mission.budget_currency_id') == 978) selected @endif value="978">EUR</option>
                                
                            </select>
                            <span class="invalid-feedback">{{ $errors->first('mission.budget_currency_id') }}</span>
                </div>
               
                
            </div>
            
            <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="result">@lang('site::admin.mission.result')</label>
                            <textarea
                                  name="mission[result]"
                                  id="result"
                                  class="form-control{{ $errors->has('mission.result') ? ' is-invalid' : '' }}"
                                  >{{ old('mission.result') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('mission.result') }}</span>
                        </div>
            </div>   
            
            
            <div class="form-row">
                        <div class="col mb-3">
                            <label class="control-label" for="comments">@lang('site::admin.mission.comments')</label>
                            <textarea
                                  name="mission[comments]"
                                  id="comments"
                                  class="form-control{{ $errors->has('mission.comments') ? ' is-invalid' : '' }}"
                                  >{{ old('mission.comments') }}</textarea>
                            <span class="invalid-feedback">{{ $errors->first('mission.comments') }}</span>
                        </div>
            </div>      
            
            
            <h5 class="card-title">Сотрудники</h5>
                
                <div class="form-row">
                            
                           <div class="col-sm-5">
                                    <div class="form-group">
                                        <label class="control-label" for="user_id">
                                            Поиск сотрудника
                                        </label>
                                        <select 
                                                id="user_id"
                                               
                                                style="width:100%"
                                                class="form-control">
                                            <option></option>
                                            @foreach($users as $user)
                                                <option @if(old('mission.user_id') == $user->id) selected @endif value="{{$user->id}}"> {{$user->name}} </option>
                                            @endforeach
                                        </select>
                                        <small id="user_idHelp" class="d-block form-text text-success">
                                            Введите имя и выберите из предложенного списка
                                        </small>
                                        <span class="invalid-feedback">{{ $errors->first('mission.user_id') }}</span>
                                    </div>
                            </div>
                            <div class="col-sm-7">
                                <div class="form-group">  
                                    <div class="list-group" id="users">
                                    @include('site::admin.mission.create_user', ['user' => $creator, 'main' => 1])
                                    </div>
                                </div>
                            </div>
                                
                            
                </div>
               
         <hr/>
            <div class="form-row">
                <div class="col text-right">
                    <button name="_create" form="form" value="0" type="submit" class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('admin.missions.index') }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>
                </div>

            </div>
        </div>
    </div>
    
</form>
</div>
@endsection


@push('scripts')
<script>
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;
try {
window.addEventListener('load', function () {
    let user = $('#user_id'),
        users = $('#users'),
        selected = [];
       
//---- поиск и добавление сотрудника ----               
    $(document)
        .on('click', '.user-delete', (function () {
            $('.user-' + $(this).data('id')).remove();
        }));
    

    user.select2({
        theme: "bootstrap4",
        placeholder: '@lang('site::messages.select_from_list')',
        selectOnClose: true,
        minimumInputLength: 1,
    });

    user.on('select2:select', function (e) {
        let user_id = $(this).find('option:selected').val();
        if (!selected.includes(user_id)) {
            user.removeClass('is-invalid');
            selected.push(user_id);
            axios
                .get("/api/users/create-mission/" + user_id)
                .then((response) => {

                    users.append(response.data);
                    
                    user.val(null)
                })
                .catch((error) => {
                    this.status = 'Error:' + error;
                });
        } else {
            user.addClass('is-invalid');
        }
    });
//---- Конец. Поиск и добавление сотрудника ----            
                
         
            $("#search_address").keyup(function(I){
                switch(I.keyCode) {
                    // игнорируем нажатия 
                    case 13:  // enter
                    case 27:  // escape
                    case 38:  // стрелка вверх
                    case 40:  // стрелка вниз
                    break;

                    default:
                        if($(this).val().length>3){

                            input_initial_value = $(this).val();
                            $.get("/api/dadata/address", { "str":$(this).val(), "bound":"city" },function(data){
                                var list = JSON.parse(data);
                                
                                suggest_count = list.length;
                                if(suggest_count > 0){
                                    $("#search_address_wrapper").html("").show();
                                    for(var i in list){
                                        if(list[i] != ''){
                                            $('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                            $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                                
                                                document.getElementById('locality').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                                document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                                $('#search_address').val($(this).text());
                                                
                                                $('#search_address_wrapper').fadeOut(2350).html('');
                                            });
                                        }
                                    }
                                }
                            }, 'html');
                        }
                    break;
                }
            });

            
            $("#search_address").keydown(function(I){
                switch(I.keyCode) {
                    case 27: // escape
                        $('#search_address_wrapper').hide();
                        return false;
                    break;
                    
                }
            });

             $('html').click(function(){
                $('#search_address_wrapper').hide();
            }); 
            
            // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
            $('#search_address').click(function(event){
                if(suggest_count)
                    $('#search_address_wrapper').show();
                event.stopPropagation();
            });
        
        /////////////////////////////////////////////////////////////////////////////////////////////////////////
     
    
            
            
        
        
});

} catch (e) {
console.log(e);
}

</script>
@endpush