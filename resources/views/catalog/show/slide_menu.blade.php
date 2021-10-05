<div class="container-fluid">
<div class="main-container">

	<div class="container">
        <ul class="category-tabs js-categoryTabs">
            @foreach($catalogs as $catalog)
			<li><span>{{ $catalog->name_for_menu }}</span></li>
			@endforeach
		</ul>
    </div>
   
        <div class="category-list">

		@foreach($catalogs as $key=>$catalog)
		  <div class="tab-group">
		   <div class="tab-group-header js-tabGroupHeader" data-group-index="{{$key}}">
              <span>{{ $catalog->name_for_menu }}</span>
            </div>
            <ul class="category-list__child owl-carousel js-categoryList @if($catalog->id == 10) is-open  @endif">
				
				@foreach($catalog->childrensEquipments() as $equipment)
								  <li><div class="img-block" style="text-align:center;"><a href="{{route('equipments.show', $equipment)}}">
								  <img src="{{$equipment->images->isNotEmpty() ? $equipment->images->first()->src() : 'http://placehold.it/250x150'}}" alt="{{$equipment->name}}" title="{{$equipment->name}}"></a></div>
								  <h5>{{$equipment->name}}</h5>
								  @if($equipment->price->value!=0)<b>от <span class="rng">{{ Site::format($equipment->price->value) }}</span></b>@endif
								  <p>{!! $equipment->menu_annotation !!}</p>
								  </li>
					
				@endforeach	
			
            </ul>
            <div class="arrows-container"></div>
		  </div>
		@endforeach

        
      </div> <!-- end main menu -->
</div>
</div> 