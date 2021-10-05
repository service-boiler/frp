

@extends('layouts.app')
@section('header')
    @include('site::header.front',[
        'h1' => __('site::cart.cart'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::cart.cart')]
        ]
    ])
@endsection
@section('content')

<div class="container">

        @if(!Cart::isEmpty())

            <div class=" border p-3 mb-2">
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{!! $error !!}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <a class="btn btn-secondary d-block d-sm-inline-block mr-0 mr-sm-1"
                           href="{{ route('products.index') }}"
                           role="button">
                            <i class="fa fa-reply"></i>
                            <span>@lang('site::cart.add_form_cancel')</span>
                        </a>
                        <a href="{{route('clearCart')}}" class="d-block d-sm-inline-block btn btn-danger">
                            <i class="fa fa-close"></i>
                            <span>@lang('site::cart.cart_clear')</span>
                        </a>

                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            @if($static_currency && in_array(env('MIRROR_CONFIG'),['sfru','sfby']))
                               <div class="mt-4 d-inline-block"> Внутренний курс Евро: {{$static_currency}} &#8381; / &#8364;</div>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-12">
                    @foreach(Cart::items() as $item)
                        @include('site::cart.item.row')
                    @endforeach
                    @include('site::cart.modal.delete')
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <form id="cart-form" action="{{route('cart_create_order')}}" method="post">
                                @csrf
                                <div class="card border-0">

                                    <div class="card-body">
                                        <h5 class="text-right">@lang('site::cart.total')</h5>
                                        <h2 class="text-right">
                                            <span id="cart-total">{{ Site::format(Cart::total()) }}</span>
                                        </h2>
                                        @if($user)
                                        <div class="form-row required">
                                            <label class="control-label"
                                                   for="contragent_id">@lang('site::order.contragent_id')</label>
                                            <select class="form-control{{  $errors->has('order.contragent_id') ? ' is-invalid' : '' }}"
                                                    required
                                                    name="order[contragent_id]"
                                                    id="contragent_id">
                                                @if($contragents->count() == 0 || $contragents->count() > 1)
                                                    <option value="">@lang('site::messages.select_from_list')</option>
                                                @endif
                                                @foreach($contragents as $contragent)
                                                    <option
                                                            @if(old('order.contragent_id') == $contragent->id)
                                                            selected
                                                            @endif
                                                            value="{{ $contragent->id }}">{{ $contragent->name }}</option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback">{{ $errors->first('order.contragent_id') }}</span>
                                        </div>


                                        <div class="form-row required">
                                            <label class="control-label" for="contacts_comment">
                                                @lang('site::order.contacts_comment')
                                            </label>
                                            <input required
                                                   type="text"
                                                   id="contacts_comment"
                                                   name="order[contacts_comment]"
                                                   class="form-control"
                                                   value="{{ old('order.contacts_comment') }}"
                                                   placeholder="@lang('site::order.placeholder.contacts_comment')">
                                            <span class="invalid-feedback">{{ $errors->first('order.contacts_comment') }}</span>
                                        </div>
                                        @else
                                            <div class="form-row">
                                                <div class="col-sm-6 form-group required">
                                                    <label class="control-label"
                                                           for="name">Контактные данные получателя заказа</label>
                                                    <div class="input-group">
                                                        <input required
                                                               type="text"
                                                               id="name"
                                                               name="order[name]"
                                                               class="form-control{{ $errors->has('order.name') ? ' is-invalid' : '' }}"
                                                               value="{{ old('order.name') }}"
                                                               placeholder="Например, Петров Петр Петрович">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-user"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="invalid-feedback">{{ $errors->first('order.name') }}</span>
                                                </div>
                                                <div class="col-sm-3 form-group required">
                                                <label class="control-label"
                                                       for="phone">Телефон мобильный</label>
                                                <div class="input-group">
                                                    <div class="input-group">
                                                        <input required
                                                               type="tel"
                                                               name="order[phone]"
                                                               id="phone"
                                                               oninput="mask_phones()"
                                                               pattern="{{config('site.phone.pattern_mobile')}}"
                                                               maxlength="{{config('site.phone.maxlength')}}"
                                                               title="{{config('site.phone.format')}}"
                                                               data-mask="{{config('site.phone.mask')}}"
                                                               class="phone-mask form-control{{ $errors->has('phone.number') ? ' is-invalid' : (old('phone.number') ? ' is-valid' : '') }}"
                                                               placeholder="@lang('site::phone.placeholder.number')"
                                                               value="{{ old('phone.number') }}">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <i class="fa fa-phone"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback">{{ $errors->first('repair.phone_primary') }}</span>
                                                </div>
                                                <div class="col-sm-3">
                                                <label class="control-label"
                                                       for="name">E-mail</label>
                                                <div class="input-group">
                                                    <input
                                                           type="email"
                                                           id="name"
                                                           name="order[email]"
                                                           class="form-control{{ $errors->has('order.name') ? ' is-invalid' : '' }}"
                                                           value="{{ old('order.name') }}"
                                                           placeholder="Не обязательно">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-envelope-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback">{{ $errors->first('repair.phone_primary') }}</span>
                                                </div>
                                            </div>

                                            <div class="form-row required mt-3">
                                                <div class="col-12">
                                                <label class="control-label" for="shipping_address">
                                                    Адрес доставки (полный адрес, либо город/район пункта выдачи заказов курьерской службы)
                                                </label>
                                                    <textarea placeholder="Например, Тульская обл, Веневский р-н, п. Северный, д. 13, кв 7.    Или: Тула, офис СДЭК на ул. Сиреневой"
                                                              class="form-control"
                                                              name="order[shipping_address]"
                                                              maxlength="5000"
                                                              rows="3"></textarea>
                                                <span class="invalid-feedback">{{ $errors->first('order.shipping_address') }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="form-row text-right">
                                            <div class="col-12">
                                            <textarea placeholder="@lang('site::cart.order_comment')"
                                                      class="form-control"
                                                      name="order[comment]"
                                                      maxlength="5000"
                                                      rows="3"></textarea>
                                            <input type="hidden" name="message[receiver_id]"
                                                   value="{{config('site.receiver_id')}}">
                                            <input type="hidden" name="order[status_id]" value="1">
                                        </div>
                                        </div>

                                    </div>
                                    <div class="card-footer text-muted text-center">
                                        <button type="submit" class="btn btn-ms btn-lg ">
                                            <i class="fa fa-check"></i> @lang('site::cart.order_confirm')</button>
                                    </div>
                                    @if($user && $user->hasPermission('orders'))
                                    @else
                                    @if($user && (empty($user->prices()->where('product_type_id','8')->first()) || $user->prices()->where('product_type_id','8')->first()->price_type_id == config('site.defaults.user.price_type_id')))
                                    <div class="card-footer">
                                         В корзине указаны розничные цены.
                                    </div>
                                    @endif
                                    @endif


                                </div>
                                
                                
                                    
                            </form>
                        </div>
                        
                    </div>
                        
                </div>
                
            </div>
        @else
            <div class="row my-5">
                <div class="col text-center">
                    <div class="mb-3" style="transform: rotate(15deg);font-size: 2rem;">
                        <i class="fa fa-shopping-cart fa-5x"></i>
                    </div>
                    <h1 class="font-weight-normal my-3">@lang('site::cart.cart_is_empty')</h1>
                    <a href="{{ route('products.index') }}" role="button"
                       class="btn btn-ms">@lang('site::cart.to_products')</a>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
    <script>
        try {
            window.addEventListener('load', function () {
                let checks = document.querySelectorAll('[type="checkbox"][name="pgt[]"]'), checked;
                for (i = 0; i < checks.length; i++) {
                    checks[i].addEventListener('click', function (e) {
                        checked = document.querySelectorAll('[data-product-group-type="' + e.target.value + '"]');
                        for (let i in checked) {
                            checked[i].checked = e.target.checked;
                        }
                    })
                }
            });
        } catch (e) {
            console.log(e);
        }
    </script>
@endpush
