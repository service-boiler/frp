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
                <a href="{{ route('ferroli-user.events.show', $event) }}">{{$event->title}}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{$event->title}}</h1>
        @alert()@endalert
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body" id="summernote">
                        <form method="POST" id="form"
                              action="{{ route('ferroli-user.events.update', $event) }}">

                            @csrf

                            @method('PUT')

                            <div class="form-row">
                                <div class="col-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('event.show_ferroli', $event->show_ferroli)) checked @endif
                                               class="custom-control-input{{  $errors->has('event.show_ferroli') ? ' is-invalid' : '' }}"
                                               id="show_ferroli"
                                               name="event[show_ferroli]">
                                        <label class="custom-control-label"
                                               for="show_ferroli">@lang('site::messages.show_ferroli')</label>
                                        <span class="invalid-feedback">{{ $errors->first('event.show_ferroli') }}</span>
                                    </div>
                                </div>
                           
                                <div class="col-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               @if(old('event.show_lamborghini', $event->show_lamborghini)) checked
                                               @endif
                                               class="custom-control-input{{  $errors->has('event.show_lamborghini') ? ' is-invalid' : '' }}"
                                               id="show_lamborghini"
                                               name="event[show_lamborghini]">
                                        <label class="custom-control-label"
                                               for="show_lamborghini">@lang('site::messages.show_lamborghini')</label>
                                        <span class="invalid-feedback">{{ $errors->first('event.show_lamborghini') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row required mt-3">
                                <div class="col">
                                    <label class="control-label" for="title">@lang('site::event.title')</label>
                                    <input type="text"
                                           name="event[title]"
                                           id="title"
                                           required
                                           class="form-control{{ $errors->has('event.title') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::event.placeholder.title')"
                                           value="{{ old('event.title', $event->title) }}">
                                    <span class="invalid-feedback">{{ $errors->first('event.title') }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col required">

                                            <label class="control-label"
                                                   for="type_id">@lang('site::event.type_id')</label>
                                            <select class="form-control{{  $errors->has('event.type_id') ? ' is-invalid' : '' }}"
                                                    name="event[type_id]"
                                                    required
                                                    id="type_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($event_types as $event_type)
                                                    <option
                                                            @if(old('event.type_id', $event->type_id) == $event_type->id)
                                                            selected
                                                            @endif
                                                            value="{{ $event_type->id }}">{{ $event_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">{{ $errors->first('event.type_id') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col required">

                                            <label class="control-label"
                                                   for="status_id">@lang('site::event.status_id')</label>
                                            <select class="form-control{{  $errors->has('event.status_id') ? ' is-invalid' : '' }}"
                                                    name="event[status_id]"
                                                    required
                                                    id="status_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($statuses as $status)
                                                    <option
                                                            @if(old('event.status_id', $event->status_id) == $status->id)
                                                            selected
                                                            @endif
                                                            value="{{ $status->id }}">{{ $status->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">{{ $errors->first('event.status_id') }}</span>
                                        </div>
                                    </div>


                                </div>
                            </div>
                            
                            <div class="row">        
                                <div class="col-12">
                                    <label class="control-label"
                                                       for="search_address">Поиск адреса. Наберите адрес в свободном формате и выберите подходящий из предложенных</label>
                                            <input class="form-control" type="text" name="search_address" id="search_address" value="" autocomplete="off">
                                        
                                        <div class="row ml-3 mt-3" id="search_address_wrapper"></div>
                                    
                                </div>
                                
                            </div> 

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col required">

                                            <label class="control-label"
                                                   for="region_id">@lang('site::event.region_id')</label>
                                            <select class="form-control{{  $errors->has('event.region_id') ? ' is-invalid' : '' }}"
                                                    name="event[region_id]"
                                                    required
                                                    id="region_id">
                                                <option value="">@lang('site::messages.select_from_list')</option>
                                                @foreach($regions as $region)
                                                    <option
                                                            @if(old('event.region_id', $event->region_id) == $region->id)
                                                            selected
                                                            @endif
                                                            value="{{ $region->id }}">{{ $region->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">{{ $errors->first('event.region_id') }}</span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col">
                                            <label class="control-label" for="city">@lang('site::event.city')</label>
                                            <input type="text"
                                                   name="event[city]"
                                                   id="city"
                                                   required
                                                   class="form-control{{ $errors->has('event.city') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::event.placeholder.city')"
                                                   value="{{ old('event.city', $event->city) }}">
                                            <span class="invalid-feedback">{{ $errors->first('event.city') }}</span>
                                            <input type="hidden"
                                           name="event[city]" 
                                           id="city_hidden"
                                           value="{{ old('event.city', $event->city) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label" for="address">@lang('site::event.address')</label>
                                    <input type="text"
                                           name="event[address]"
                                           id="address"
                                           class="form-control{{ $errors->has('event.address') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::event.placeholder.address')"
                                           value="{{ old('event.address', $event->address) }}">
                                    <span class="invalid-feedback">{{ $errors->first('event.address') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col">
                                    <label class="control-label" for="address">Дополнение к адресу (офис, этаж и т.п.)</label>
                                    <input type="text"
                                           name="event[address_addon]"
                                           id="address_addon" 
                                           class="form-control{{ $errors->has('event.address') ? ' is-invalid' : '' }}"
                                           placeholder="Например, БЦ Мега, этаж 2, офис 13"
                                           value="{{ old('event.address', $event->address_addon) }}">
                                    <span class="invalid-feedback">{{ $errors->first('event.address') }}</span>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="date_from">@lang('site::event.date_from')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_from"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="event[date_from]"
                                                   id="date_from"
                                                   maxlength="10"
                                                   required
                                                   placeholder="@lang('site::event.placeholder.date_from')"
                                                   data-target="#datetimepicker_date_from"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('event.date_from') ? ' is-invalid' : '' }}"
                                                   value="{{ old('event.date_from', $event->date_from->format('d.m.Y')) }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_from"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('event.date_from') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group required">
                                        <label class="control-label"
                                               for="date_to">@lang('site::event.date_to')</label>
                                        <div class="input-group date datetimepicker" id="datetimepicker_date_to"
                                             data-target-input="nearest">
                                            <input type="text"
                                                   name="event[date_to]"
                                                   id="date_to"
                                                   maxlength="10"
                                                   required
                                                   placeholder="@lang('site::event.placeholder.date_to')"
                                                   data-target="#datetimepicker_date_to"
                                                   data-toggle="datetimepicker"
                                                   class="datetimepicker-input form-control{{ $errors->has('event.date_to') ? ' is-invalid' : '' }}"
                                                   value="{{ old('event.date_to', $event->date_to->format('d.m.Y')) }}">
                                            <div class="input-group-append"
                                                 data-target="#datetimepicker_date_to"
                                                 data-toggle="datetimepicker">
                                                <div class="input-group-text">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="invalid-feedback">{{ $errors->first('event.date_to') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" @if(old('event.confirmed', $event->confirmed) == 1) checked @endif
                                class="custom-control-input{{  $errors->has('event.confirmed') ? ' is-invalid' : '' }}"
                                       id="confirmed"
                                       name="event[confirmed]">
                                <label class="custom-control-label"
                                       for="confirmed">@lang('site::event.confirmed')</label>
                                <span class="invalid-feedback">{{ $errors->first('event.confirmed') }}</span>
                            </div>
                            <div class="form-row required">
                                <div class="col">
                                    <label class="control-label"
                                           for="annotation">@lang('site::event.annotation')</label>
                                    <textarea class="form-control{{ $errors->has('event.annotation') ? ' is-invalid' : '' }}"
                                              placeholder="@lang('site::event.placeholder.annotation')"
                                              required
                                              name="event[annotation]"
                                              id="annotation">{{ old('event.annotation', $event->annotation) }}</textarea>
                                    <span class="invalid-feedback">{{ $errors->first('event.annotation') }}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label"
                                       for="description">@lang('site::event.description')</label>
                                <textarea name="event[description]"
                                          id="description"
                                          class="summernote form-control{{ $errors->has('event.description') ? ' is-invalid' : '' }}"
                                          placeholder="@lang('site::event.placeholder.description')">{{ old('event.description', $event->description) }}</textarea>
                                <span class="invalid-feedback">{{ $errors->first('event.description') }}</span>
                            </div>

                        </form>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="form-row mt-2">
                                    <div class="col">
                                        <label class="control-label" class="control-label"
                                               for="image_id">@lang('site::event.image_id')</label>

                                        <form method="POST" enctype="multipart/form-data"
                                              action="{{route('ferroli-user.images.store')}}">
                                            @csrf
                                            <input type="hidden"
                                                   name="storage"
                                                   value="events"/>
                                            <input class="d-inline-block form-control-file{{ $errors->has('image_id') ? ' is-invalid' : '' }}"
                                                   type="file"
                                                   accept="{{config('site.events.accept')}}"
                                                   name="path"/>

                                            <input type="button" class="btn btn-ms image-upload-button"
                                                   value="@lang('site::messages.load')"/>
                                            <span class="invalid-feedback">{{ $errors->first('image_id') }}</span>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div id="images" class="row bg-white">
                                    @include('site::admin.image.edit')
                                </div>
                            </div>
                        </div>

                        <hr/>
                        <div class=" text-right">
                            <button form="form" type="submit" class="btn btn-ms">
                                <i class="fa fa-check"></i>
                                <span>@lang('site::messages.save')</span>
                            </button>
                            <a href="{{ route('ferroli-user.events.show', $event) }}"
                               class="d-block d-sm-inline btn btn-secondary">
                                <i class="fa fa-close"></i>
                                <span>@lang('site::messages.cancel')</span>
                            </a>
                        </div>

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
					$.get("/api/dadata/address", { "str":$(this).val() },function(data){
						var list = JSON.parse(data);
                        
						suggest_count = list.length;
						if(suggest_count > 0){
							$("#search_address_wrapper").html("").show();
							for(var i in list){
								if(list[i] != ''){
									$('#search_address_wrapper').append('<div class="address_variant" data-key="'+i+'" id="result_id-'+list[i].id+'">'+list[i].name+'</div>');
                                    $('#search_address_wrapper').find('#result_id-'+list[i].id).click(function() {
                                        console.log($(this)[0].getAttribute('data-key'));
                                        console.log(list[$(this)[0].getAttribute('data-key')].alldata);
                                        document.getElementById('city').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                        document.getElementById('city_hidden').value = list[$(this)[0].getAttribute('data-key')].alldata.city;
                                        document.getElementById('address').value = list[$(this)[0].getAttribute('data-key')].name;
                                        document.getElementById('region_id').value = list[$(this)[0].getAttribute('data-key')].alldata.region_iso_code;
                                        $('#search_address').val($(this).text());
                                        
                                        // прячем слой подсказки
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
        
    });
        
        function key_activate(n){
	$('#search_address_wrapper div').eq(suggest_selected-1).removeClass('active');

	if(n == 1 && suggest_selected < suggest_count){
		suggest_selected++;
	}else if(n == -1 && suggest_selected > 0){
		suggest_selected--;
	}

	if( suggest_selected > 0){
		$('#search_address_wrapper div').eq(suggest_selected-1).addClass('active');
		$("#search_address").val( $('#search_address_wrapper div').eq(suggest_selected-1).text() );
	} else {
		$("#search_address").val( input_initial_value );
	}
}
        
    } catch (e) {
        console.log(e);
    }

</script>
@endpush