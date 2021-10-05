@foreach($catalog->catalogs()->where('enabled', 1)->where(config('site.check_field'), 1)->where(config('site.check_field_second'),1)->orderBy(config('site.sort_order.catalog'))->get() as $children)
    @if($children->catalogs()->count() == 0 && $children->equipments()->where('enabled', 1)->where(config('site.check_field'), 1)->where(config('site.check_field_second'),1)->count() > 0)	
						@foreach($children->equipments()->where('enabled', 1)->where(config('site.check_field'), 1)->where(config('site.check_field_second'),1)->orderBy(config('site.sort_order.equipment'))->get() as $equipment)
							  <li><div class="img-block" style="text-align:center;"><a href="{{route('equipments.show', $equipment)}}">
							  <img src="{{$equipment->images->isNotEmpty() ? $equipment->images->first()->src() : 'http://placehold.it/250x150'}}" alt="{{$equipment->name}}" title="{{$equipment->name}}"></a></div>
							  <h5>{{$equipment->name}}</h5>
							  @if($equipment->price->value!=0)<b>от <span class="rng">{{ Site::format($equipment->price->value) }}</span></b>@endif
							  <p class="menu_annotation">{!! $equipment->menu_annotation !!}</p>
							  </li>
						@endforeach
					@else
        @include('site::catalog.show.children_slide_menu', ['catalog' => $children])
    @endif
@endforeach