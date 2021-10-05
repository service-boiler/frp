 <option value="">@lang('site::messages.select_from_list')</option>
                                        @foreach($promocodes as $promocode)
                                            <option @if(isset($promocode_id) && $promocode_id == $promocode->id)
                                                    selected
                                                    @endif
                                                    value="{{ $promocode->id }}">
                                                {{ $promocode->name }} {{ $promocode->bonuses }} @lang('site::admin.bonus_val')
                                            </option>
                                        @endforeach
                                        <optgroup>
                                            <option value="load">✚ @lang('site::messages.add')</option>
                                        </optgroup>