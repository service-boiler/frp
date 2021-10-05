@if(!$user->active)
    <del>
        @endif
        <a class="" href="{{ route('admin.users.show', $user) }}">{{$user->name}}</a> @if($user->prereg->count()) <i class="fa fa-rocket"  data-toggle="tooltip"
                                      data-placement="top"
                                      title="Пользователь с предварительной регистрацией"></i> @endif
        {{--<span title="ID" class="text-muted">[ #{{ $user->id }} ]</span>--}}
        @if(!$user->active)
    </del>
@endif


