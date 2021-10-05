<h5 class="mb-0">Запланирован выезд на обслуживание:</h5>
<div id="planned-row-{{$visit->id}}">
<div class="row mb-3">
<div class="col-sm-4">
<small class="text-success">Дата и время выезда</small><br />
@if($visit->date_planned)
{{$visit->date_planned->format('d.m.Y H:i')}}
@lang('site::date.week.' .$visit->date_planned->dayOfWeek)
@endif
</div>
<div class="col-sm-4">
<small class="text-success">Стоимость ТО</small><br />
{!!moneyFormatRub($visit->cost_planned)!!}
</div>
<div class="col-sm-1" id="planned-date-button-{{$visit->id}}">
<a href="javascript:void(0);" class="btn btn-sm btn-primary" 
                        onclick="document.getElementById('planned-date-form-{{$visit->id}}').classList.toggle('d-none'); 
                                 document.getElementById('planned-row-{{$visit->id}}').classList.toggle('d-none'); 
                                 document.getElementById('planned-date-button-{{$visit->id}}').classList.toggle('d-none')">
                        Изменить</a>
</div>

</div>
<div class="row mb-3">
<div class="col-sm-4">
<small class="text-success">Тип выезда</small><br />

{{$visit->type ? $visit->type->name : ''}}
</div>
<div class="col-sm-4">
<small class="text-success">Инженер</small><br />
{{$visit->engineer_id ? $visit->engineer->public_name : 'не указан'}}
</div>
</div>
@if($visit->statuses()->exists())
                <div class="row mb-3">
                <div class="col" id="status-form-{{$visit->id}}">        
                        
                         Сменить статус:
                             @foreach($visit->statuses()->get() as $status)
                             <button form="request-{{$visit->id}}" type="submit" name="esbUserVisit[status_id]" value="{{$status->id}}" 
                             class="btn btn-sm text-normal text-white m-2" style="background-color: {{ $status->color }}">
                                {{ $status->button }}
                             </button>
                             @endforeach
                             
                        </div>
                    
                </div>
                @endif
                <form id="request-{{$visit->id}}" action="{{route('esb_user_visits.status', $visit)}}" method="POST">
                        @csrf
                        @method('PUT')
                </form>
</div>
@if(auth()->user()->type_id!=4)
                    <div class="row d-none mb-3" id="planned-date-form-{{$visit->id}}">
                    
                        <div class="col-sm-4">
                        <small class="text-success">Укажите ориентировочные дату и время. </small>
                        
                            <div class="input-group date datetimepickerfull planned_date" id="datetimepicker_date_{{$visit->id}}"
                                 data-target-input="nearest">
                                <input type="text"
                                       name="datetime" form="new_visit"
                                       id="planned_date_{{$visit->id}}"
                                       placeholder="@lang('site::admin.webinar.datetime_ph')"
                                       data-target="#datetimepicker_date_{{$visit->id}}"
                                       data-toggle="datetimepicker"
                                       class="datetimepicker-input form-control{{ $errors->has('datetime') ? ' is-invalid' : '' }}"
                                       value="{{ old('datetime',$visit->date_planned ? $visit->date_planned->format('d.m.Y H:i') : null)}}">
                                <div class="input-group-append"
                                     data-target="#datetimepicker_date_{{$visit->id}}"
                                     data-toggle="datetimepicker">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3"><small class="text-success">Стоимость ТО </small>
                            <div class="input-group">
                                <input type="text"
                                       name="cost_planned" form="new_visit"
                                       id="cost_{{$visit->id}}"
                                       class="form-control{{ $errors->has('cost_planned') ? ' is-invalid' : '' }}"
                                       value="{{ old('cost_planned',$visit->cost_planned)}}">
                                <div class="input-group-append">
                                     
                                    <div class="input-group-text">
                                        <i class="fa fa-money"></i>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="input-group col-sm-7">
                            <small class="text-success">Тип выезда</small>   
                            <div class="input-group">
                            <select class="form-control{{  $errors->has('type_id') ? ' is-invalid' : '' }}" id="type_id_{{$visit->id}}"
                                name="type_id" form="new_visit">
                            <option value="">@lang('site::messages.select_from_list')</option>
                            @foreach($types as $type)
                                <option
                                        @if(old('type_id',$visit->type_id) == $type->id)
                                        selected
                                        @endif
                                        value="{{ $type->id }}">{{ $type->name }}
                                </option>
                            @endforeach
                            </select>
                            
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fa fa-wrench"></i>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="input-group col-sm-7">
                            <small class="text-success">Инженер</small>   
                            <div class="input-group">
                                                
                            <select class="form-control{{  $errors->has('engineer_id') ? ' is-invalid' : '' }}" id="engineer_id_{{$visit->id}}"
                                name="engineer_id" form="new_visit">
                            <option value="">@lang('site::messages.select_from_list')</option>
                            @foreach($engineers as $engineer)
                                <option
                                        @if(old('engineer_id',$visit->engineer_id ) == $engineer->id)
                                        selected
                                        @endif
                                        value="{{ $engineer->id }}">{{ $engineer->name }}
                                </option>
                            @endforeach
                            </select>
                            
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <i class="fa fa-wrench"></i>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-sm-4"><small class="text-success">Уведомление будет отправлено клиенту </small>
                            <div class="col">
                            <a href="javascript:void(0);" class="btn btn-green planned-confirm" id="planned-confirm" 
                                       data-visit="{{$visit->id}}">
                                <i class="fa fa-check"></i> Запланировать</a>
                        </div>
                        
                        </div>
                    </div>
                    @endif
<hr />                