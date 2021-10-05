<div class="module widget-handle left pt20-mobile">
<a href="{{route('cart')}}#cart" class="cart-nav">

	 @if(Cart::quantity()>0)
    <span class="badge" style="font-size: 1.3em;">{{ Cart::quantity() }}</span>
	 @endif
</a>
@include('site::cart.modal.add')
</div>