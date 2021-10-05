        <div class="row answer-item mt-1">
            <div class="col-md-1 text-right">
                
                    
                        <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               name="answer[{{$answer->id}}][is_correct]"
                               id="is_correct_{{$answer->id}}" class="custom-control-input" 
                              
                                @if(old('answer.'.$answer->id.'.is_correct', $answer->is_correct)) checked  @endif
                              >
                              <label class="custom-control-label mb-2" for="is_correct_{{$answer->id}}"></label>
                    
                
            </div>
            </div>
            <div class="col-md-9">
                
                        <input type="text"
                               name="answer[{{$answer->id}}][text]"
                               id="text_{{$answer->id}}"
                               required
                               maxlength="100"
                               class="form-control{{ $errors->has('answer.'.$answer->id.'.text') ? ' is-invalid' : '' }}"
                               placeholder="@lang('site::academy.answer.text')"
                               value="{{ old('answer.'.$answer->id.'.text',$answer->text) }}">
                        <span class="invalid-feedback">{{ $errors->first('answer.'.$answer->id.'.text') }}</span>
                
            </div>
           
            <div class="col-md-2">
            <a href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="btn btn-sm btn-danger">@lang('site::messages.delete')</a>
            </div>
            
        </div>
