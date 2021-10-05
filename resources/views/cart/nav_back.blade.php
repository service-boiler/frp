<li class="nav-item ml-sm-3">
<a href="{{route('cart')}}#cart" class="cart-nav">
    <i class="fa fa-2x ti-shopping-cart-full" style="line-height: 40px;"></i>
	 @if(Cart::quantity()>0)
    Корзина: <span class="badge" style="font-size: 1.3em;">{{ Cart::quantity() }}</span>
	 @endif
</a>
@include('site::cart.modal.add')
</li>
