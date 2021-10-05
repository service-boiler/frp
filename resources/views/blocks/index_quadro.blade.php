@if($indexQuadroBlocks->count()>4)
<div class="main-container">
 <!-- Images grid -->
    <div class="max-container">
      <div class="img-grid-container">
        <div class="img-grid-col img-grid-col--main">
          <a href="{!! $indexQuadroBlocks[0]->url !!}">
            <img src="{!! $indexQuadroBlocks[0]->image->src() !!}" alt="">
            <span class="img-grid-col__text">{!! $indexQuadroBlocks[0]->text_hover !!}</span>
            <span class="img-grid-col-start__text img-grid-col-start__text-top">{!! $indexQuadroBlocks[0]->text !!}</span>
          </a>
        </div>

        <div class="img-grid-col img-grid-col--min img-grid-col--up">
          <div class="img-grid-col--small">
            <a href="{!! $indexQuadroBlocks[1]->url !!}">
              <img src="{!! $indexQuadroBlocks[1]->image->src() !!}" alt="">
              <span class="img-grid-col__text">{!! $indexQuadroBlocks[1]->text_hover !!}</span>
              <span class="img-grid-col-start__text img-grid-col-start__text-bottom-small">{!! $indexQuadroBlocks[1]->text !!}</span>
            </a>
          </div>

          <div class="img-grid-col--large">
            <a href="{!! $indexQuadroBlocks[2]->url !!}">
              <img src="{!! $indexQuadroBlocks[2]->image->src() !!}" alt="">
              <span class="img-grid-col__text">{!! $indexQuadroBlocks[2]->text_hover !!}</span>
              <span class="img-grid-col-start__text img-grid-col-start__text-bottom-large">{!! $indexQuadroBlocks[2]->text !!}</span>
            </a>
          </div>
        </div>

        <div class="img-grid-col img-grid-col--min img-grid-col--down">
          <div class="img-grid-col--large">
            <a href="{!! $indexQuadroBlocks[3]->url !!}">
              <img src="{!! $indexQuadroBlocks[3]->image->src() !!}" alt="">
              <span class="img-grid-col__text">{!! $indexQuadroBlocks[3]->text_hover !!}</span>
              <span class="img-grid-col-start__text img-grid-col-start__text-bottom-large">{!! $indexQuadroBlocks[3]->text !!}</span>
            </a>
          </div>

          <div class="img-grid-col--small">
            <a href="{!! $indexQuadroBlocks[4]->url !!}">
              <img src="{!! $indexQuadroBlocks[4]->image->src() !!}" alt="">
              <span class="img-grid-col__text">{!! $indexQuadroBlocks[4]->text_hover !!}</span>
              <span class="img-grid-col-start__text img-grid-col-start__text-bottom-small">{!! $indexQuadroBlocks[4]->text !!}</span>
            </a>
          </div>
        </div>

      </div> <!-- end img-grid-container -->
    </div>
</div>  
@endif