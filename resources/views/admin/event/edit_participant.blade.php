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

                <form id="form-content" method="POST" action="{{ route('ferroli-user.events.update_participant', ['event'=>$event, 'participant'=>$participant]) }}">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row required">
                                <div class="col mb-1">
                                    <label class="control-label" for="name">@lang('site::participant.name')</label>
                                    <input type="text"
                                           name="participant[name]"
                                           id="name"
                                           required
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.name')"
                                           value="{{ old('participant.name', $participant->name) }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.name') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                        
                    <div class="row">
                    
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="company">Компания</label>
                                    <input type="text"
                                           name="participant[company]"
                                           id="company"
                                           
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.company') ? ' is-invalid' : '' }}"
                                           placeholder=""
                                           value="{{ old('participant.company', $participant->company) }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.company') }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-row required">
                                <div class="col mb-3">
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
                                            <option @if(old('participant.role_id', $participant->role_id) == $role->id)
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
                        <div class="col-md-4">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="headposition">@lang('site::participant.headposition')</label>
                                    <input type="text"
                                           name="participant[headposition]"
                                           id="headposition"
                                           
                                           maxlength="100"
                                           class="form-control{{ $errors->has('participant.headposition') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.headposition')"
                                           value="{{ old('participant.headposition', $participant->headposition) }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.headposition') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="email">@lang('site::participant.email')</label>
                                    <input type="email"
                                           autocomplete="off"
                                           name="participant[email]"
                                           id="email"
                                           maxlength="50"
                                           class="form-control{{ $errors->has('participant.email') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::participant.placeholder.email')"
                                           value="{{ old('participant.email', $participant->email) }}">
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
                                           value="{{ old('participant.phone', $participant->phone) }}">
                                    <span class="invalid-feedback">{{ $errors->first('participant.phone') }}</span>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="participant[country_id]" value="{{config('site.country')}}">
                    <input type="hidden" id="user_id" name="participant[user_id]" value="">
                </form>


                <hr/>
                <div class="form-row">
                    <div class="col text-right">
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