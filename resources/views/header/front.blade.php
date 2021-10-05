<header class="page-header-one">
    <div class="container">
        <div class="row">
            
                @if($page_h1)
					 <div class="col-md-12 col-lg-7">
                    <h1 class="uppercase mb-0">{{$page_h1}}</h1>
					 </div>
                @elseif($h1)
					 <div class="col-md-12 col-lg-7">
                    <h1 class="uppercase mb-0">{!! $h1 !!}</h1>
					  </div>
                @endif
            
            <div class="col-md-12 col-lg@if($page_h1 || $h1)-5@else-12@endif">
                <ol class="breadcrumb">
                    @foreach ($breadcrumbs as $breadcrumb)
                        @if(is_array($breadcrumb))
                            @if (!empty($breadcrumb['url']))
                                <li><a href="{{ $breadcrumb['url'] }}">{!! $breadcrumb['name'] !!}</a></li>
                            @else
                                <li class="active">{!! $breadcrumb['name'] !!}</li>
                            @endif
                        @endif
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
</header>
