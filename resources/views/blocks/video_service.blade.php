	<section class="video-block">

      <div class="owl-carousel js-videoBlock">
         @foreach($videoBlocks as $videoBlock)
			
			<div class="iframe-wrapper">
           <iframe src="{{$videoBlock->url}}" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
         </div>

			@endforeach
      </div>
		<div style="text-align: center; padding-top: 5px;"><a href="{{config('site.youtube_channel')}}" target="_blank">Наш канал на Youtube</a></div>
   </section>