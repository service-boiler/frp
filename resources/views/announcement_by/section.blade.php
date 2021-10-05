@if($announcements->isNotEmpty())
     <!-- Event section -->
	 
   <section class="news-index">
     <div class="title-block-index">
       <div class="container">
         <h2>@lang('site::announcement.announcements')</h2>
       </div>
     </div>
	 <div class="container">
       <div class="row justify-content-sm-center">
	 @foreach($announcements->chunk(3) as $items)
                        @foreach($items as $announcement)
                            @include('site::announcement.index.row', compact('announcement'))
                        @endforeach
                    
            @endforeach
        </div>
        
    <div class="row mb-5">
        <div class="col text-center">
            <a class="btn btn-outline-ferroli" href="{{route('announcements.index')}}">@lang('site::announcement.help.all')</a>
        </div>
    </div>
	</section>
@endif
