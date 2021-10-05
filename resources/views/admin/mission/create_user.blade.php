<div class="mb-2 list-group-item p-1 user-{{$user->id}}" data-id="{{$user->id}}">
    <div class="row">
        <div class="col-xl-7 col-sm-7">
            <button type="button" class="btn btn-danger btn-sm user-delete" data-id="{{$user->id}}"
                            data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true"><i class="fa fa-close"></i></span>
                    </button>
            <span class="font-weight-bold">{{$user->name}}</span>
             
        
                    
                
        </div>
        <div class="col-xl-5 col-sm-5">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       @if(old('users.main') || !empty($main)) checked
                                       @endif
                                       class="custom-control-input{{  $errors->has('users.main') ? ' is-invalid' : '' }}"
                                       id="main-{{$user->id}}"
                                       name="users[{{$user->id}}][main]">
                                <label class="custom-control-label"
                                       for="main-{{$user->id}}">@lang('site::admin.mission.main')</label>
                                <span class="invalid-feedback">{{ $errors->first('users.main') }}</span>
                            </div>
                        </div> 
                        <input type="hidden" name="users[{{$user->id}}][user_id]" value="{{$user->id}}">
    </div>
</div>
