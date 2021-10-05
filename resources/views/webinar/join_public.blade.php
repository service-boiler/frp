<div class="card-body">
     
     
     <form id="webinar-register-form-{{$webinar->id}}"
                action="{{route('events.webinars.public_join', $webinar)}}"
                method="POST">
             @csrf
             @method('POST')
             
             <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">Фамилия Имя Отчество</label>
                                    <input type="text" name="participant[name]" id="name" required
                                           class="form-control{{ $errors->has('participant.name') ? ' is-invalid' : '' }}"
                                           placeholder="Например, Иван Петров"
                                           value="{{ old('participant.name') }}">
                                    <span class="invalid-feedback">{{ $errors->first('name') }}</span>
                                </div>
             </div>
             <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="company">Компания</label>
                                    <input type="text" name="participant[company]" id="name"
                                           class="form-control{{ $errors->has('participant.company') ? ' is-invalid' : '' }}"
                                           placeholder="Например, ТеполЭнергоМонтаж"
                                           value="{{ old('participant.company') }}">
                                    <span class="invalid-feedback">{{ $errors->first('company') }}</span>
                                </div>
             </div> 
             <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="email">Email</label>
                                    <input type="text" name="participant[email]" id="email"
                                           class="form-control{{ $errors->has('participant.email') ? ' is-invalid' : '' }}"
                                           placeholder="Например, ivan@mail.ru"
                                           value="{{ old('participant.email') }}">
                                    <span class="invalid-feedback">{{ $errors->first('email') }}</span>
                                </div>
             </div> 
             <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="phone">Телефон</label>
                                    <input type="tel" name="participant[phone]" id="phone"
                                    oninput="mask_phones()"
                                       pattern="{{config('site.phone.pattern')}}"
                                       maxlength="{{config('site.phone.maxlength')}}"
                                       title="{{config('site.phone.format')}}"
                                       data-mask="{{config('site.phone.mask')}}"
                                       class="phone-mask form-control{{ $errors->has('participant.phone') ? ' is-invalid' : '' }}"
                                       
                                           placeholder="Например, (905)123-45-67"
                                           value="{{ old('participant.phone') }}">
                                    <span class="invalid-feedback">{{ $errors->first('phone') }}</span>
                                </div>
             </div>  
             <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="city">Город</label>
                                    <input type="text" name="participant[city]" id="name"
                                           class="form-control{{ $errors->has('participant.city') ? ' is-invalid' : '' }}"
                                           placeholder="Например, Ижевск"
                                           value="{{ old('participant.city') }}">
                                    <span class="invalid-feedback">{{ $errors->first('city') }}</span>
                                </div>
             </div> 
             <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="vocation">Специализация</label>
                                    <input type="text" name="participant[vocation]" id="name"
                                           class="form-control{{ $errors->has('participant.vocation') ? ' is-invalid' : '' }}"
                                           placeholder="Например, Продажи / Сервис / Монтаж"
                                           value="{{ old('participant.vocation') }}">
                                    <span class="invalid-feedback">{{ $errors->first('vocation') }}</span>
                                </div>
             </div> 
             
        </form>
        <button type="submit" form="webinar-register-form-{{$webinar->id}}" 
                                
                                class="btn btn-ms d-block d-sm-inline  mb-4">
								<i class="fa fa-check-square-o"></i> <span class="d-none d-sm-inline-block">@lang('site::admin.webinar.register')</span>
							</button>
</div>
