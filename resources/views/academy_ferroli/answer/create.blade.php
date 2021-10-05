        <div class="row answer-item mt-1">
            <div class="col-md-1 text-right">
                
                    
                        <div class="custom-control custom-checkbox">
                        <input type="checkbox"
                               name="answer[{{$random}}][is_correct]"
                               id="is_correct_{{$random}}"
                              class="custom-control-input"
                              @if(old('answer.'.$random.'.is_correct')) checked  @endif>
                              <label class="custom-control-label mb-2" for="is_correct_{{$random}}"></label>
                    
                
            </div>
            </div>
            <div class="col-md-9">
                
                        <input type="text"
                               name="answer[{{$random}}][text]"
                               id="text_{{$random}}"
                               required
                               maxlength="100"
                               class="form-control{{ $errors->has('answer.'.$random.'.text') ? ' is-invalid' : '' }}"
                               placeholder="@lang('site::academy.answer.text')"
                               value="{{ old('answer.'.$random.'.text') }}">
                        <span class="invalid-feedback">{{ $errors->first('answer.'.$random.'.text') }}</span>
                
            </div>
           
            <div class="col-md-2">
            <a href="javascript:void(0);" onclick="$(this).parent().parent().remove();" class="btn btn-sm btn-danger">@lang('site::messages.delete')</a>
            </div>
            
        </div>
