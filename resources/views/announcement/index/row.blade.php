<!-- Card -->
         <div class="col-12 col-xl-4 col-lg-4 col-md-6 col-sm-10">
           <article class="event-card">
             <div class="event-card__back img-block"><img src="{{Storage::disk($announcement->image->storage)->url($announcement->image->path)}}" alt=""></div>
             <div class="event-card__desc-block">
               <header class="event-card__header">
                 <time datetime="{{$announcement->date}}" class="date-block"><span class="day">{{$announcement->date->day}}</span> @lang('site::date.month.'.($announcement->date->month)) <br> {{$announcement->date->year}}</time>
                 <div class="tags-block">
                    <div class="d-none" style="width: 80px; top: 10px;">
				    <script src="https://yastatic.net/es5-shims/0.0.2/es5-shims.min.js"></script><script src="https://yastatic.net/share2/share.js"></script>
					<div class="ya-share2" data-services="vkontakte,facebook,whatsapp,telegram" data-url="{{route('announcements.index')}}#{{$announcement->id}}" data-title="{{$announcement->title }}"></div>
				   </div>
                   <a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('')" class="align-text-bottom text-right">
				   <img src="images/link-share.png" alt=""></a>
                 </div>
               </header>
               <div class="event-card__desc">
			   <h5>{{$announcement->title }}</h5>
                 <p>
                  {!! $announcement->annotation !!}
                 </p>
				 @if(!Empty($announcement->description))
               <div class="d-none">
                    {!! $announcement->description !!}
                </div>
				
				<a href="javascript:void(0);" onclick="$(this).prev().toggleClass('d-none');$(this).parent().parent().parent().toggleClass('fixed-height-450')" class="align-text-bottom text-right button button-ferroli">
                    Подробнее
                </a>
				@endif
				</div>
             </div>
           </article>
         </div>
<!-- End Card -->



