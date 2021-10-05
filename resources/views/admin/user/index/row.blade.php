<tr>
    <td>@include('site::admin.user.component.link')
    @if($user->hasRole('asc') && empty($user->contragents->where('contract','<>','')->first()->id))<br /><span class="text-danger">нет номера договора</span>@endif
    </td>
    <td>{{ optional($user->region)->name }}<br />@if(($addresses = $user->addresses()->where('type_id', 2)->get())->isNotEmpty()) {{ $addresses[0]->locality }} @endif</td>
    <td>@if(!empty($user->parents->first()))<a href="{{route('admin.users.show',$user->parents->first())}}">{{$user->parents->first()->name}}</a>@endif</td>

    <td class="text-center" style="color: #000;">
    @if($user->type_id == 3) <i class="fa fa-id-badge"></i> @else <i class="fa fa-users"></i> @endif
    </td>
    <td class="text-center" style="color: #000; line-height: 1.5em;">
        @if($user->hasRole('buyer'))        [Покупатель]@endif
        @if($user->hasRole('montage_fl'))   &nbsp;<span style="background-color: #a9dc93;">[Монтаж ФЛ]</span>@endif
        @if($user->hasRole('service_fl'))   &nbsp;<span style="background-color: #61fd7f;">[Сервис ФЛ]</span>@endif
        @if($user->hasRole('sale_fl'))      &nbsp;<span style="background-color: #befac9;">[Продавец ФЛ]</span>@endif
        @if($user->hasRole('montage'))      &nbsp;<span style="background-color: #719561;">[ИЦ]</span>@endif
        @if($user->hasRole('asc'))          &nbsp;<span style="background-color: #faa6a6;">[АСЦ] </span>@endif
        @if($user->hasRole('csc'))          &nbsp;<span style="background-color: #fb3939;">[Склад ЗЧ] </span>@endif
        @if($user->hasRole('dealer'))       &nbsp;<span style="background-color: #98cdfb;">[Дилер] </span>@endif
        @if($user->hasRole('distr'))        &nbsp;<span style="background-color: #;">[Дистр] </span>@endif
        @if($user->hasRole('gendistr'))     &nbsp;<span style="background-color: #;">[ГенДистр] </span>@endif
        @if($user->hasRole('eshop'))        &nbsp;<span style="background-color: #;">[Eshop] </span>@endif
        
    </td>
    <td class="text-center" style="color: #000; line-height: 1.5em;">
        @if($user->certificates()->where('type_id','3')->count())       [П]@endif
        @if($user->certificates()->where('type_id','2')->count())   &nbsp;<span style="background-color: #a9dc93;">[М]
        @if($user->copletedStages('1')->count()>3) <i class="fa fa-graduation-cap"></i> @endif
        </span>@endif
        @if($user->certificates()->where('type_id','1')->count())   &nbsp;<span style="background-color: #61fd7f;">[С] 
            @if($user->copletedStages('2')->count()>3) <i class="fa fa-graduation-cap"></i> @endif
        </span>@endif
       
        
    </td>
    <td class="text-center">
        @if($user->mountings()->count()>0) <a href="{{ route('admin.mountings.index', ['filter[user_id]='.$user->id]) }}">{{$user->mountings()->count()}}</a>@endif
    </td>
    <td class="text-center">
        <span class="text-normal @if($user->verified) text-success @else text-danger @endif">@lang('site::user.verified_ico_'.($user->verified))</span>
    </td>
    <td class="text-center">
        @if($user->show_map_service==1)
            <span class="text-normal  text-success"> ✔</span>
        @else
            <span class="text-normal  text-danger"> ✖</span>
        @endif
    </td>
    <td class="text-center">
        @if($user->show_map_dealer==1)
            <span class="text-normal  text-success"> ✔</span>
        @else
            <span class="text-normal  text-danger"> ✖</span>
        @endif
    </td>
    <td>
        @if($user->created_at)
            {{$user->created_at->format('d.m.Y')}}
        @endif
    </td>
    <td>
        @if($user->logged_at)
            {{$user->logged_at->format('d.m.Y')}}
        @endif
    </td>
   
</tr>
