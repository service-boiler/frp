@foreach($users as $user)
    <div class="row mb-2">
        <div class="col-12"> <button type="button" class="btn btn-primary btn-sm client-add"
                                                        data-id="{{$user->id}}"
                                                        data-name="{{$user->public_name}}"
                >
                <span aria-hidden="true"><i class="fa fa-plus"></i></span>
            </button> {{$user->public_name}}
            <small>(
            @foreach($user->addresses()->where('type_id',2)->groupBy('locality')->pluck('locality') as $locality)
                {{$locality}}
                @if(!$loop->last) ,&nbsp @endif
            @endforeach
            )</small>
        </div>
    </div>
@endforeach
