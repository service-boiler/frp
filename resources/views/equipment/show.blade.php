@extends('layouts.app')
@section('title'){!! $equipment->title ?: $equipment->catalog->parentRoot()->name. ' '.$equipment->name !!} @lang('site::messages.title_separator')@endsection
@section('description'){!! $equipment->metadescription ?: $equipment->catalog->parentRoot()->name. ' '.$equipment->name !!}@endsection
@section('header')
    @include('site::header.front',[
        'h1' => '',
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['url' => route('catalogs.index'), 'name' => __('site::catalog.catalogs')],
            ['url' => route('catalogs.show', $equipment->catalog->parentRoot()),'name' => $equipment->catalog->parentRoot()->name_plural],
            ['name' => $equipment->name]
        ]
    ])
@endsection
@section('content')
<script>
	try {
        window.addEventListener('load', function () {
        $('.rating.control-mode .fa').bind('click', function() {
		var $this = $(this);
			$this
				.nextAll()
				.removeClass('fa-rate');
			$this
				.addClass('fa-rate')
				.prevAll()
				.addClass('fa-rate');
			rateCounter();    
        });

	function rateCounter(){
		$('.rating').each(function(){
			var $this = $(this),
				_counter = $this.find('.fa-rate').length;

				if(_counter){
					$this.find('input.rate-counter').val(0);
				}
				$this.find('input.rate-counter').val(_counter);
		});
		return this;
	}
	rateCounter();
    });
    
    window.addEventListener('load', function(){
  if(window.location.hash){
    $('a[href="'+window.location.hash+'"]').trigger('click');
  }
});
    
    } catch (e) {
        console.log(e);
    }

</script>



    <div class="container">
        <div class="card mb-3">
            <div class="card-body">
                <div class="media flex-wrap flex-lg-nowrap">
                    <div id="carouselEquipmentIndicators" class="carousel slide col-12 col-md-3 col-lg-4 p-0"
                         data-ride="carousel">
                        @if($equipment->images()->count() > 1)
                            <ol class="carousel-indicators">
                                @foreach($equipment->images as $key => $image)
                                    <li data-target="#carouselEquipmentIndicators" data-slide-to="{{$key}}"
                                        @if($key == 0) class="active" @endif></li>
                                @endforeach
                            </ol>
                        @endif
                        <div class="carousel-inner">
                            @foreach($equipment->images as $key => $image)
                                <div class="carousel-item @if($key == 0) active @endif">
                                    <img class="d-block w-100"
                                         src="{{ $image->src() }}"
                                         alt="{{$equipment->name}}">
                                </div>
                            @endforeach

                        </div>
                        @if($equipment->images()->count() > 1)
                            <a class="carousel-control-prev" href="#carouselEquipmentIndicators" role="button"
                               data-slide="prev">
                                <span class="carousel-control-prev-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.prev')</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselEquipmentIndicators" role="button"
                               data-slide="next">
                                <span class="carousel-control-next-icon dark" aria-hidden="true"></span>
                                <span class="sr-only">@lang('site::messages.next')</span>
                            </a>
                        @endif
                    </div>
                    <div class="media-body p-md-5 px-4 pt-5 pb-4 col-md-9 col-lg-8">

                        <h1>{!! $equipment->h1 ?: $equipment->catalog->parentRoot()->name. ' '.$equipment->name !!}</h1>
                        @admin()
                        <p>
                            <a href="{{route('admin.equipments.show', $equipment)}}">
                                <i class="fa fa-folder-open"></i>
                                @lang('site::messages.open') @lang('site::messages.in_admin')
                            </a>
                        </p>
                        @endadmin()
                        <p>
                        @if(env('MIRROR_CONFIG')=='sfby' || env('MIRROR_CONFIG')=='mfby' || env('MIRROR_CONFIG')=='lmby')
                            {!! $equipment->annotation_by !!}
                        @else
                            {!! $equipment->annotation !!}
                        @endif
                        </p>
                        @if(!empty($modelises) && $modelises->count() > 0)
                        <p class="mt-4"><a href="#tabs" class="get-datasheet-tab">
                        Доступны 3D-модели котлов в разделе Документация</a></p>
                        @endif
                    </div>
                </div>
                @alert()@endalert()
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Ошибка отправки отзыва.</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
                <a name="tabs"></a>
                <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">

                    <li class="nav-item"> 
                        <a class="nav-link 
						@if($equipment->availableProducts()->count()!=0 && !$errors->any())
						active
						@endif
						" id="equipments-tab" data-toggle="tab" href="#equipments" role="tab"
                           aria-controls="equipments" aria-selected="true">@lang('site::equipment.equipments')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link 
						@if($equipment->availableProducts()->count()==0  && !$errors->any())
						active
						@endif
						" id="description-tab" data-toggle="tab" href="#description" role="tab"
                           aria-controls="description" aria-selected="true">@lang('site::equipment.description')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" 
                        id="specification-tab" data-toggle="tab" href="#specification" role="tab"
                           aria-controls="specification"
                           aria-selected="false">@lang('site::equipment.specification')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="products-tab" data-toggle="tab" href="#products" role="tab"
                           aria-controls="products" aria-selected="false">@lang('site::product.products')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="datasheet-tab" data-toggle="tab" href="#datasheet" role="tab"
                           aria-controls="datasheet" aria-selected="false">@lang('site::datasheet.datasheets')</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if($errors->any()) active @endif" id="feedbacks-tab" data-toggle="tab" href="#feedbacks" role="tab"
                           aria-controls="feedbacks" aria-selected="true">@lang('site::equipment.feedbacks')</a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">

                <!-- Products-->
                    <div class="tab-pane fade p-3 @if($equipment->availableProducts()->count()!=0)show active @endif"
                         id="equipments"
                         role="tabpanel"
                         aria-labelledby="equipments-tab">
                        @foreach($equipment->availableProducts()->get() as $product)
                            <div class="row border-bottom py-2">
                                <div class="col-12 col-md-6">
                                    <a class="d-block text-large"
                                       href="{{route('products.show', $product)}}">{{$product->name}}</a><span class="text-small text-muted">артикул: {{$product->sku}}</span>
                                </div>
                                <div class="row col-12 col-md-3 mb-2 mb-md-0">
                                    @if($product->hasPrice)
                                        @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby'])) 
												
                                                        @if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                                        <div class="col-6 col-md-6 mb-2 mb-md-0">
                                                            <div class="text-tiny text-muted">{{ $product->price->type->display_name ?: __('site::price.price')}}</div>
                                                            <div class="text-large"><span class="old-price">{{ Site::format($product->price->value) }}</span></div>
                                                        </div>
                                                        @endif
                                                	
                                                
													@if($product->pricepromo->value<>0 && $product->price->value !=$product->pricepromo->value)
                                                        <div class="col-6 col-md-6 mb-2 mb-md-0"> 
                                                            <div class="text-tiny text-muted text-nowrap">{{ $product->pricepromo->type->display_name ?: __('site::price.price')}}</div>
                                                            <div class="text-large"><b>{{ Site::format($product->pricepromo->value) }}</b></div>
                                                        </div>
                                                    @else
                                                        <div class="col-12 mb-2 mb-md-0"> 
                                                            <div class="text-tiny text-muted text-nowrap">{{ $product->price->type->display_name ?: __('site::price.price')}}</div>
                                                            <div class="text-large">{{ Site::format($product->price->value) }}</div>
                                                        </div>
                                                	@endif
												
                                        @endif
                                        @if(in_array(env('MIRROR_CONFIG'),['sfru','sfby']))
                                            <div class="col-12 mb-2 mb-md-0"> 
                                                <div class="text-tiny text-muted">{{ $product->price->type->display_name ?: __('site::price.price')}}</div>
                                                <div class="text-large">{{ Site::format($product->price->value) }}</div>
                                            </div>
                                        @endif
                                    @else
                                        
                                        <div class="text-tiny text-muted"></div>
                                        <div class="text-large text-muted">Цена по запросу</div>
                                        
                                    @endif
                                </div>
                                <div class="col-12 col-md-3">
                                    @if(in_array(env('MIRROR_CONFIG'),['sfru','sfby']))
                                        @can('buy', $product)
                                            @include('site::cart.buy.large', $product->toCart())
                                        @endcan
                                    @elseif(in_array(env('MIRROR_CONFIG'),['marketru','marketby']))    
                                        @include('site::cart.buy.large', $product->toCart())
                                    @endif
                                </div>
                            </div>

                        @endforeach
                        @if(in_array(env('MIRROR_CONFIG'),['marketru','marketby']))  
                        <span class="rng">* Специальное предложение действует только при заказе оборудования на сайте market.ferroli.ru
                        
                        {{--* Акционная цена действительна:

                            <li> - только при заказе через сайт market.ferroli.ru </li>
                            @if($equipment->id=='222')<li> - только на котлы серии Vitabel от 10 до 24кВт</li>@endif
                            <li> - не более 2 котлов в одном заказе</li>
                            <li> - заказчиком должно выступать физическое лицо, приобретающее оборудование для собственных нужд и не на перепродажу. В случае выявления злоупотреблений, обмана и мошенничества компания Ферроли оставляет за собой право аннулировать/отменить заказ</li>
                            <li> - поставка котлов осуществляется официальными дилерами компании Ферроли в регионах России под строгим контролем Московского офиса Ferroli</li>
                            <li> - после размещения заказа, в случае его положительной проверки, компания Ферроли вышлет подтверждение заказа на электронную почту клиента, указанную при размещении заказа с уточнением срока поставки. В любом случае подтвержденный заказ должен будет быть выполнен в срок не более 20 дней с момента его подтверждения</li>
                        --}}
                        </span>
                        @endif
                    </div>
                    
                    <!-- Description -->
                    <div class="tab-pane fade @if($equipment->availableProducts()->count()==0 && !$errors->any())show active @endif p-3" id="description" role="tabpanel"
                         aria-labelledby="home-tab">{!!$equipment->description!!}
                            @if(!empty($equipment_description_addon))<br /><p><strong>{!!$equipment_description_addon!!}</p></strong>@endif
                    </div>
     
                    <!-- Specification      
                         {!!$equipment->specification!!} -->
                    <div class="tab-pane fade p-3" id="specification" role="tabpanel"
                         aria-labelledby="specification-tab">
                         
                         @if($equipment->specs->count())
                         <div id="container-specifications" data-action="{{route('api.equipment_specifications', ['equipment'=>$equipment])}}"></div>
                        @else
                        {!!$equipment->specification!!}
                        @endif
                         
                         </div>
                    
                    <div class="tab-pane fade p-3" id="products" role="tabpanel"
                         aria-labelledby="products-tab">
                        <div class="row py-1 border-bottom">
                            <div class="col-sm-12 mb-3"> По вопросам приобретения запасных частей необходимо обращаться в сервисные центры. <br />
                            Ближайший авторизованный сервисный центр Вы можете найти в разделе <a href="{{ route('service-centers') }}">СЕРВИСНЫЕ ЦЕНТРЫ</a>
                            </div>
                        </div>
                        @foreach($products as $product)
                            <div class="row py-1 border-bottom">
                                <div class="col-sm-6">
                                    <span>@lang('site::product.products') {!! $product->name !!}</span>
                                </div>
                                <div class="col-sm-6 text-left text-sm-right">
                                    <a class="btn btn-sm btn-ms"
                                       href="{{route('products.index', ['filter[boiler_id]' => $product->id])}}">
                                        @lang('site::messages.show') <span
                                                class="badge badge-light">{{$product->availableDetails()->count()}}</span></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- datasheets -->
                    <div class="tab-pane fade p-3" id="datasheet" role="tabpanel" aria-labelledby="datasheet-tab">
                      
                            @foreach($modelises as $datasheet)
                                <div class="card item-hover mb-1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-9">
                                                <a class="text-large mb-1"
                                                   href="{{ route('datasheets.show', $datasheet) }}">{{ $datasheet->name ?: $datasheet->file->name }}</a>
                                                <span class="text-muted d-block">@include('site::datasheet.date')</span>
												@if(($products = $datasheet->products()->where('enabled', 1)->orderBy('equipment_id')->orderBy('name'))->exists())
													@include('site::datasheet.index.row.products')
												@endif
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                <a class="btn btn-success btn-block" href="{{$datasheet->cloud_link}}" target="_blank"><i class="fa fa-cloud-download"></i> @lang('site::messages.download')</a>
                                            </div>

                                            
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                            
                            @foreach($datasheets as $datasheet)
                                <div class="card item-hover mb-1">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a class="text-large mb-1"
                                                   href="{{ route('datasheets.show', $datasheet) }}">{{ $datasheet->name ?: $datasheet->file->name }}</a>
                                                <span class="text-muted d-block">@include('site::datasheet.date')</span>
												@if(($products = $datasheet->products()->where('enabled', 1)->orderBy('equipment_id')->orderBy('name'))->exists())
													@include('site::datasheet.index.row.products')
												@endif
                                            </div>

                                        <div class="col-sm-3 text-right">
                                                @if($datasheet->schemes()->exists())
                                                    @if($products->exists())
                                                        @if($products->count() > 1)

                                                            <div class="dropdown">
                                                                <a class="btn btn-ms dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                                    @lang('site::scheme.schemes') ({{$products->count() }})
                                                                </a>

                                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                                                    @foreach($products->get() as $product)
                                                                    <a class="dropdown-item" href="{{route('products.scheme', [$product, $datasheet->schemes()->first()])}}">{!! $product->name !!}</a>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @else
                                                            <a class="btn btn-ms"
                                                            href="{{route('products.scheme', [$products->first(), $datasheet->schemes()->first()])}}">@lang('site::messages.open') @lang('site::scheme.scheme')</a>
                                                        @endif
                                                    @endif
												@endif
                                            </div>
                                            <div class="col-sm-3 text-right">
                                                @include('site::file.download', ['file' => $datasheet->file])
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                      
                    </div>
                    
                                        
                    <!-- Reviews-->
                    <div class="tab-pane fade @if($errors->any())show active @endif p-3" id="feedbacks" role="tabpanel" aria-labelledby="home-tab">
                         <h5>Отзывы о Ferroli {{$equipment->name}}</h5>
                         
                    @foreach($reviews as $review)
                                
                    <div class="row border-bottom py-2" id="review" itemprop="review" itemscope="" itemtype="http://schema.org/Review">
                            <div class="col-xl-12 col-sm-6"  itemprop="author">{!!$review->name!!}</div>
                            <div class="col-xl-12 col-sm-6" itemprop="reviewRating" itemscope="" itemtype="http://schema.org/Rating">
								<meta itemprop="ratingValue" content="{{$review->rate}}">
								<meta itemprop="worstRating" content="1">
								<meta itemprop="bestRating" content="5">
                            @for($i = 1; $i <= $review->rate; $i++)
                            <i class="fa fa-star fa-rate"></i>
                            @endfor
                            </div>
                            
                            
                        <div class="review-text col-xl-12 col-sm-6">{!!$review->message!!}</div>
						<div itemprop="datePublished" class="review-date col-xl-12 col-sm-6">
                            {{$review->created_at->format('d.m.Y')}}
                        </div>
                        
                    </div>
                    
                    @endforeach     
                    
                    <h6 class=" mt-3">Вы пользователь оборудования или имеете опыт работы с ним? <br />Оставьте свой отзыв! Мы обязательно учтем Ваши пожелания и предложения.</h6>
                    <form id="review"
                    action="/review" method="POST" autocomplete="on">
                        @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="name">Ваше имя</label>
                                    <input
                                           type="text"
                                           name="review[name]"
                                           id="name"
                                           required
                                           maxlength="49"
                                           class="form-control"
                                           placeholder=""
                                           value="{{ old('review.name') }}">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label" for="city">Город</label>
                                    <input type="text"
                                           name="review[city]"
                                           id="city"
                                           required
                                           maxlength="49"
                                           class="form-control"
                                           placeholder=""
                                           value="{{ old('review.city') }}">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="phone">Телефон для связи (не публикуется на сайте)</label>
                                    <input 
                                           type="text"
                                           name="review[phone]"
                                           id="phone"
                                           maxlength="20"
                                           class="form-control"
                                           placeholder=""
                                           value="{{ old('review.phone') }}">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-row ">
                                <div class="col mb-3">
                                    <label class="control-label" for="email">E-mail (не публикуется на сайте)</label>
                                    <input type="email"
                                           name="review[email]"
                                           id="email"
                                           
                                           maxlength="49"
                                           class="form-control"
                                           placeholder=""
                                           value="{{ old('review.email') }}">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-row required">
                                <div class="col mb-3 rating control-mode"> Оценка:
                                    
                                    <i class="fa fa-star fa-rate star-rate"></i>
                                    <i class="fa fa-star fa-rate star-rate"></i>
                                    <i class="fa fa-star fa-rate star-rate"></i>
                                    <i class="fa fa-star fa-rate star-rate"></i>
                                    <i class="fa fa-star fa-rate star-rate"></i>
                        <input type="hidden" class="rate-counter" name="review[rate]" value="5">
                        
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-row required">
                                    <div class="col mb-3">
                                        <label class="control-label"
                                               for="name">Отзыв</label>
                                        <textarea required
                                               rows="5"
                                               name="review[message]"
                                               id="message"
                                               maxlength="1255"
                                               class="form-control"
                                              >
                                               {{ old('review.message') }}
                                        </textarea>
                                        <span class="invalid-feedback"></span>
                                    </div>
                                </div>
                            </div>
                           
                        </div>
                        
                        
                        <div class="form-row required">
                            <div class="col mb-3">
                                <label class="control-label"
                                       for="captcha">@lang('site::mounter.captcha')</label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text"
                                               name="captcha"
                                               required
                                               id="captcha"
                                               class="form-control{{ $errors->has('captcha') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::mounter.placeholder.captcha')"
                                               value="">
                                        <span class="invalid-feedback">{{ $errors->first('captcha') }}</span>
                                    </div>
                                    <div class="col-md-9 captcha">
                                        <span>{!! captcha_img('flat') !!}</span>
                                        <button data-toggle="tooltip"
                                                data-placement="top"
                                                title="@lang('site::messages.refresh')"
                                                type="button"
                                                class="btn btn-outline-secondary"
                                                id="captcha-refresh">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>
                        </div>
                        
                        <input type="hidden" name="review[reviewable_id]" value="{{$equipment->id}}">
                        <input type="hidden" name="review[reviewable_type]" value="equipments">
                        @if(!empty(auth()->user()))
                        <input type="hidden" name="review[user_id]" value="{{auth()->user()->id}}">
                        @endif
                        <div class="control-group form-group">
                                <button type="submit" class="btn btn-ms"><i
                                            class="fa fa-send"></i> @lang('site::messages.send')</button>
                            </div>
                        </form>
                         
                    </div>
                    
               
                    
                    
                </div>

            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    try {
        window.addEventListener('load', function () {
            document.getElementById('captcha-refresh').addEventListener('click', function () {
                fetch('/captcha/flat')
                    .then(response => {
                        response.blob().then(blobResponse => {
                            const urlCreator = window.URL || window.webkitURL;
                            document.querySelector('.captcha span img').src = urlCreator.createObjectURL(blobResponse);
                        });
                    });
            });

            
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush
