                <form  id="form-content" method="POST" action="{{ route('admin.promocodes.store') }}">
                    @csrf
                    <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="name">@lang('site::admin.promocodes.name')</label>
                                    <input type="text" name="promocode[name]"
                                           id="name"
                                           class="form-control{{ $errors->has('promocode.name') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::admin.placeholder.name')">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.name') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocodes.bonuses')</label>
                                    <input type="number" name="promocode[bonuses]"
                                           id="bonuses"
                                           required
                                           class="form-control{{ $errors->has('promocode.bonuses') ? ' is-invalid' : '' }}">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.bonuses') }}</span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label"
                                       for="expiry">@lang('site::admin.promocodes.expiry_at')</label>
                                <div class="input-group date datetimepicker" id="datetimepicker_expiry"
                                     data-target-input="nearest">
                                    <input type="text"
                                           name="promocode[expiry]"
                                           id="expiry"
                                           maxlength="10"
                                           placeholder="@lang('site::admin.promocodes.expiry_placeholder')"
                                           data-target="#datetimepicker_expiry"
                                           data-toggle="datetimepicker"
                                           class="datetimepicker-input form-control{{ $errors->has('promocode.expiry') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.expiry') }}">
                                    <div class="input-group-append"
                                         data-target="#datetimepicker_expiry"
                                         data-toggle="datetimepicker">
                                        <div class="input-group-text">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                </div>
                                <span class="invalid-feedback">{{ $errors->first('promocode.expiry') }}</span>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::admin.promocodes.short_token')</label>
                                    <input type="text" name="promocode[short_token]"
                                           id="short_token"
                                           class="form-control{{ $errors->has('promocode.short_token') ? ' is-invalid' : '' }}"
                                           value="{{ old('promocode.short_token') }}">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.short_token') }}</span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label" for="title">@lang('site::messages.comment')</label>
                                    <input type="text" name="promocode[comment]"
                                           id="comment"
                                           class="form-control{{ $errors->has('promocode.comment') ? ' is-invalid' : '' }}"
                                           ">
                                    <span class="invalid-feedback">{{ $errors->first('promocode.comment') }}</span>
                                </div>
                            </div>

                            
                </form>
					 
                
