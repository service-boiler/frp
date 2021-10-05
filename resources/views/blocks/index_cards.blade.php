<div class="main-container index-cards">
	<div class="container">
    <div class="row justify-content-sm-center">
    @foreach($indexCardsBlocks as $indexCardsBlock)
       
         <!-- Card -->
         <div class="col-12 col-xl-4 col-lg-4 col-md-6 col-sm-10">
           <article class="event-card">
             <div class="event-card__back img-block ec-c-index"><a href="{{$indexCardsBlock->url}}"><img src="{{$indexCardsBlock->image->src()}}" alt=""></a></div>
             <div class="index-card__desc-block">
               
               <div class="index-card__desc">
                 <p>
                  {{$indexCardsBlock->text}}
                 </p>
               </div>
             </div>
           </article>
		   <div class="index-card-footer">
		   <a href="{{$indexCardsBlock->url}}"><button class="btn btn-ms index-card-button">Подробнее</button></a>
		   </div>
         </div>
    @endforeach     
     </div>
	</div>
</div>