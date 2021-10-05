@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.events.index') }}">@lang('site::event.events')</a>
            </li>

            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.events.show', $event) }}">{{ $event->date_from->format('d.m.Y') }} {{$event->city}} {{$event->title}}</a>
            </li>

            <li class="breadcrumb-item active">@lang('site::participant.participants')</li>
        </ol>

        @alert()@endalert

        <div class="card mb-5">
            <div class="card-body">

                <form id="form-content" method="POST" action="{{ route('ferroli-user.events.store_participants', $event) }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label" for="name">@lang('site::participant.name')</label>
                                    <input type="text"
                                           name="participant[name]"
                                           id="name"
                                           required
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.name')"
                                           value="{{ old('participant.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.name') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                    
                        <div class="col-sm-6">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label"
                                           for="company">Компания</label>
                                    <input type="text"
                                           name="participant[company]"
                                           id="company"
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.company') ? ' is-invalid' : '' }}"
                                           placeholder=""
                                           value="{{ old('participant.company') }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.company') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="role_id">Специализация</label>
                                    <select required
                                            name="participant[role_id]"
                                            id="role_id"
                                            class="form-control{{  $errors->has('participant.role_id') ? ' is-invalid' : '' }}">
                                        @if($roles->count() == 0 || $roles->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($roles as $role)
                                            <option @if(old('participant.role_id') == $role->id)
                                                    selected
                                                    @endif
                                                    value="{{ $role->id }}">
                                                {{ $role->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label"
                                           for="headposition">@lang('site::participant.headposition')</label>
                                    <input type="text"
                                           name="participant[headposition]"
                                           id="headposition"
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.headposition') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.headposition')"
                                           value="{{ old('participant.headposition') }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.headposition') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label" for="email">@lang('site::participant.email')</label>
                                    <input type="email"
                                           autocomplete="off"
                                           name="participant[email]"
                                           id="email"
                                           maxlength="50"
                                           class="form-control{{ $errors->has('participant.email') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.email')"
                                           value="{{ old('participant.email') }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.email') }}</span>
                                    
                                </div>
                                
                            </div>
                            <div class="row ml-3 mt-5" id="email_wrapper"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row ">
                                <div class="col">
                                    <label class="control-label"
                                           for="phone">@lang('site::participant.phone')</label>
                                    <input type="tel"
                                           name="participant[phone]"
                                           id="phone"
                                           oninput="mask_phones()"
                                           pattern="{{config('site.phone.pattern')}}"
                                           maxlength="{{config('site.phone.maxlength')}}"
                                           title="{{config('site.phone.format')}}"
                                           data-mask="{{config('site.phone.mask')}}"
                                           class="phone-mask form-control{{ $errors->has('participant.phone') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.phone')"
                                           value="{{ old('participant.phone') }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.phone') }}</span>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="participant[country_id]" value="{{config('site.country')}}">
                    <input type="hidden" id="user_id" name="participant[user_id]" value="">
                </form>
                </div>
            </div>
            <div class="card mb-5">
                <div class="card-body">
                    <div class="form-row">
                    
                    <div class="col-6 text-left">
                        <form enctype="multipart/form-data" action="{{route('ferroli-user.events.participants_xls', $event)}}" method="post">
                            @csrf
                            <div class="form-row border p-3">
                                <div class="form-group mb-0">
                                    <label for="path">@lang('site::messages.xls_file')</label>
                                    <input type="file"
                                           name="path"
                                           accept="application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                                           class="{{  $errors->has('path') ? ' is-invalid' : '' }}"
                                           id="path">
                                    <span class="invalid-feedback">{!! $errors->first('path') !!}</span>
                                    <button type="submit" class="btn btn-ms">
                                        <i class="fa fa-download"></i>
                                        <span>@lang('site::messages.load')</span>
                                    </button>
                                    <span id="pathHelp" class="d-block form-text text-success">
                                        Выберите Excel файл и нажмите кнопку "Загрузить. <a href="/up/participant-example.xlsx">Пример xlsx</a>
                                    </span>
                                </div>
                            </div>
                        </form>
                        </div>
                        <div class="col-6 text-right">
                    
                        <button name="_create" form="form-content" value="1" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save_add')</span>
                        </button>
                        <button name="_create" form="form-content" value="0" type="submit" class="btn btn-ms mb-1">
                            <i class="fa fa-check"></i>
                            <span>@lang('site::messages.save')</span>
                        </button>

                        <a href="{{ route('ferroli-user.members.show', $event) }}" class="btn btn-secondary mb-1">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::messages.cancel')</span>
                        </a>

                    </div>

                </div>

                <hr/>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ФИО</th>
                        <th>Компания</th>
                        <th>Специализация</th>
                        <th>Телефон</th>
                        <th>Email</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($event->participants as $participant)
                        <tr id="participant-{{$participant->id}}">
                            <td>@if($participant->user_id)<a href="{{route('admin.users.show',$participant->user)}}"><i class="fa fa-external-link"></i></a> @endif{{$participant->name}}</td>
                            <td>{{$participant->company}}</td>
                            <td>@if($participant->role){{$participant->role->title}} <br /> @endif{{$participant->headposition}}</td>
                            <td>
                                {{$participant->phoneNumber}}
                            </td>
                            <td>{{$participant->email}}</td>
                            <td><a class="btn btn-sm btn-secondary" href="{{route('ferroli-user.events.edit_participant', ['event'=>$event, 'participant'=>$participant])}}">
                            Изменить</a>
                                <button
                                class=" btn btn-sm btn-danger btn-row-delete"
                                        data-form="#participant-delete-form-{{$participant->id}}"
                                        data-btn-delete="@lang('site::messages.delete')"
                                        data-btn-cancel="@lang('site::messages.cancel')"
                                        data-label="@lang('site::messages.delete_confirm')"
                                        data-message="@lang('site::messages.delete_sure') @lang('site::participant.participant')? "
                                        data-toggle="modal" data-target="#form-modal"
                                        href="javascript:void(0);" title="@lang('site::messages.delete')">
                                    @lang('site::messages.delete')
                                </button>
                                <form id="participant-delete-form-{{$participant->id}}"
                                      action="{{route('ferroli-user.participants.destroy', $participant)}}"
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


    </div>
@endsection



@push('scripts')
<script>
var suggest_count = 0;
var input_initial_value = '';
var suggest_selected = 0;

 try {
        window.addEventListener('load', function () {

	$("#email").keyup(function(I){
		switch(I.keyCode) {
			// игнорируем нажатия 
			case 13:  // enter
			case 27:  // escape
			case 38:  // стрелка вверх
			case 40:  // стрелка вниз
			break;

			default:
				if($(this).val().length>2){

					input_initial_value = $(this).val();
					$.get("/api/user-search", { "filter[search_user]":$(this).val(),  "filter[event_id]":{{$event->id}}},function(data){
						var list = JSON.parse(data);
                        suggest_count = list.data.length;
                        if(suggest_count > 0){
							$("#email_wrapper").html("").show();
							for(var i in list.data){
								if(list.data[i] != ''){
                                
									$('#email_wrapper').append('<div class="variant" data-key="'+i+'" id="result_id-'+list.data[i].id+'">'+list.data[i].email+' '+list.data[i].name+'</div>');
                                    $('#email_wrapper').find('#result_id-'+list.data[i].id).click(function() {
                                        console.log(list.data[$(this)[0].getAttribute('data-key')]);
                                        document.getElementById('name').value = list.data[$(this)[0].getAttribute('data-key')].name;
                                        document.getElementById('phone').value = list.data[$(this)[0].getAttribute('data-key')].phone.number;
                                        document.getElementById('company').value = list.data[$(this)[0].getAttribute('data-key')].parent.name;
                                        document.getElementById('user_id').value = list.data[$(this)[0].getAttribute('data-key')].id;
                                        document.getElementById('role_id').value = list.data[$(this)[0].getAttribute('data-key')].role.id;
                                        $('#email').val(list.data[$(this)[0].getAttribute('data-key')].email);
                                        
                                        // прячем слой подсказки
                                        $('#email_wrapper').fadeOut(2350).html('');
                                    });
								}
							}
						}
					}, 'html');
				}
			break;
		}
	});

	
	$("#email").keydown(function(I){
		switch(I.keyCode) {
			case 27: // escape
				$('#email_wrapper').hide();
				return false;
			break;
			
		}
	});

	 $('html').click(function(){
    	$('#email_wrapper').hide();
	}); 
	
    // если кликаем на поле input и есть пункты подсказки, то показываем скрытый слой
	$('#email').click(function(event){
    	if(suggest_count)
			$('#email_wrapper').show();
		event.stopPropagation();
	});
        
    });
        
    function key_activate(n){
        $('#email_wrapper div').eq(suggest_selected-1).removeClass('active');

        if(n == 1 && suggest_selected < suggest_count){
            suggest_selected++;
        }else if(n == -1 && suggest_selected > 0){
            suggest_selected--;
        }

        if( suggest_selected > 0){
            $('#email_wrapper div').eq(suggest_selected-1).addClass('active');
            $("#email").val( $('#email_wrapper div').eq(suggest_selected-1).text() );
        } else {
            $("#email").val( input_initial_value );
        }
    }
        
    } catch (e) {
        console.log(e);
    }

</script>
@endpush