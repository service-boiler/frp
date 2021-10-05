@extends('layouts.app')
@section('title')Тендер № {{$tender->id}} ({{$tender->distributor->name}})@endsection
@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.tenders.index') }}">Тендеры</a>
            </li>
            <li class="breadcrumb-item active">№ {{ $tender->id }}</li>
        </ol>
        @alert()@endalert()
        
        
        @if($statuses->isNotEmpty())
        <div class=" border p-2 mb-3">
            
            <form id="tender_status"
                      method="POST"
                      enctype="multipart/form-data"
                      action="{{ route('admin.tenders.status', $tender) }}">
                    @csrf
                    @method('PUT')
                    
                <a href="{{route('admin.tenders.edit', $tender)}}"
                   class="d-block d-sm-inline mr-0 mr-sm-1 btn btn-ms p-2 @cannot('update',$tender) disabled @endcannot">
                    <i class="fa fa-pencil"></i>
                    <span>Редактировать тендер</span>
                </a>
                @if(in_array($tender->status->id,[4,5,6]))
                <a href="{{ route('admin.tenders.pdf', $tender) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-primary">
                        <i class="fa fa-print"></i>
                        <span>@lang('site::messages.print')</span>
                </a>
                @endif
                @if(in_array($tender->status->id,[4,5]) && !$tender->order_id)
                <a href="{{ route('admin.tenders.order-create', $tender) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-green">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Создать заказ для 1С</span>
                </a>
                @endif
                @if($tender->order_id)
                <a href="{{ route('admin.orders.show', $tender->order_id) }}"
                    class="d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0 p-2 btn btn-ms">
                        <i class="fa fa-shopping-basket"></i>
                        <span>Перейти к заказу</span>
                </a>
                @endif
            <a href="{{ route('admin.tenders.index') }}" class="d-block d-sm-inline btn btn-secondary p-2 mr-3  mr-sm-3 ">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back')</span>
            </a> <br />
              
                        @foreach($statuses as $key => $status)
                            <button type="submit"
                                    name="status_id"
                                    value="{{$status->id}}"
                                    form="tender_status"
                                    style="background-color: {{$status->color}};color:white"
                                    class="d-block d-sm-inline mr-0 mt-3 mr-sm-1  mb-2 mb-sm-2  btn mt-2">
                                <i class="fa fa-{{$status->icon}}"></i>
                                <span>{{$status->button}}</span>
                            </button>
                        @endforeach
                
            
            <div class="row no-gutters">
                            <div class="d-flex col flex-column">
                                <div class="flex-grow-1 position-relative">
                                    <div class="form-group">
                                        <textarea
                                                  name="message[text]"
                                                  id="message_text"
                                                  rows="2"
                                                  placeholder="Оставить комментари при смене стстуса"
                                                  class="form-control{{  $errors->has('message.text') ? ' is-invalid' : '' }}"></textarea>
                                        <span class="invalid-feedback">{{ $errors->first('message.text') }}</span>
                                    </div>
                                    
                                </div>
                            </div>
                            
                        </div>
                       <div class="row no-gutters"> <div class="col-sm"><a href="#end">Комментарии <i class="fa fa-arrow-down"></i></a></div></div>
                        </form>
            
        </div>
        @endif <!--{{--$statuses->isNotEmpty()--}} -->
        
        @if(!$tender->contragent)
        <div class="card mb-2 card-body bg-danger text-white px-2 d-inline-block">
        <div class="row"><div class="col">Плательщик (Контрагент) дистрибьютора не выбран. При создании заказа будет использоваться последний созданный контрагент этого дистрибьютора.
        
        @if($tender->distributor->contragents()->count() < 1)
        <br /> У дистрибьютора не добавлены юридические лица в личном кабинете.
        @endif
        
        </div>
        </div>
        </div>
        @endif
        <div class="card mb-2">
            <div class="card-body pb-0">
                <dl class="row mb-0">

                    <dt class="col-sm-4 text-left text-sm-right">Дата создания заявки</dt>
                    <dd class="col-sm-8">{{ $tender->created_at->format('d.m.Y H:i') }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Создатель заявки</dt>
                    <dd class="col-sm-8">{{ $tender->user->name }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Руководитель отдела</dt>
                    <dd class="col-sm-8">@if($tender->user->chief){{ $tender->user->chief->name }}@endif</dd>

                    <dt class="col-sm-4 text-left text-sm-right">Статус заявки</dt>
                    <dd class="col-sm-8">
                        <span class="badge text-normal badge-{{$tender->status->color}}">
                            <i class="fa fa-{{$tender->status->icon}}"></i>
                            {{ $tender->status->name }}
                        </span>
                    </dd>
                </dl>   
            </div>
        </div>
        
        <div class="card mb-2">
            <div class="card-body pb-0">
            <h5 class="card-title">Объект тендера, информация о закупке.</h5>
                <dl class="row mb-0">

                    <dt class="col-sm-4 text-left text-sm-right">Планируемая дата закупки</dt>
                    <dd class="col-sm-8">{{ $tender->planned_purchase_year }} @lang('site::messages.months_cl.' .$tender->planned_purchase_month) </dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Цена действительна до:</dt>
                    <dd class="col-sm-8"> {{$tender->date_price->format('d.m.Y')}} </dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Курс для дистрибьютора</dt>
                    <dd class="col-sm-8"> {{round($tender->rates,2)}} 
                    @if($tender->cb_rate)
                        <span style="background-color:#fec145; font-weight: 600;">
                        @lang('site::tender.cb_rate')</span>
                    @else
                    (коридор курса от {{round($tender->rates_min,2)}}, до {{round($tender->rates_max,2)}})
                    @endif
                    </dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Курс для объекта</dt>
                    <dd class="col-sm-8"> {{round($tender->rates_object,2)}} (коридор курса от {{round($tender->rates_object_min,2)}}, до {{round($tender->rates_object_max,2)}})</dd>
                    
                    
                    @foreach($tender->tenderProducts as $item)
                    <hr />
                        <dt class="col-sm-4 text-left text-sm-right">Оборудование</dt>
                        <dd class="col-sm-8"><a target="_blank" href="{{route('products.show',$item->product)}}">{{$item->product->name}}</a>, </dd>
                        <dt class="col-sm-4 text-left text-sm-right">Количество:</dt><dd class="col-sm-8">{{$item->count}} шт, </dd>
                        <dt class="col-sm-4 text-left text-sm-right">РРЦ:</dt>
                            <dd class="col-sm-8">€ {{$item->product->retailPriceEur->valueRaw}}, </dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">Запрашиваемая скидка:</dt>
                            <dd class="col-sm-8">{{$item->discount}}% <span style="background-color:@lang('site::tender.tender_price_color.' .$item->approved_status); font-weight: 600;">@lang('site::tender.tender_price_status.' .$item->approved_status)</span></dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">Cкидка для объекта:</dt>
                            <dd class="col-sm-8">{{$item->discount_object}}% </dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">Цена со скидкой для дистрибьютора:</dt>
                            <dd class="col-sm-8">€ {{round($item->price_distr_euro,0)}} ({{round($item->price_distr_euro * $tender->rates,0)}} руб.)</dd>
                        
                        <dt class="col-sm-4 text-left text-sm-right">Цена со скидкой для объекта:</dt>
                            <dd class="col-sm-8">€ {{round($item->price_object_euro,0)}} ({{round($item->price_object_euro * $tender->rates_object,0)}} руб.)</dd>
                        
                        
                        <dt class="col-sm-4 text-left text-sm-right">Доходность дистрибьютора:</dt>
                        <dd class="col-sm-8">€  {{$item->profit_euro}} 
                        
                        ({{
                        round($item->profit_euro*100 / $item->price_distr_euro,1) }}%
                        
                         {{$tender->rates* $item->profit_euro}} руб. )
                        </dd>
                    
                    </dd>
                     <hr />
                    @endforeach
                    
                    
                    <dt class="col-sm-4 text-left text-sm-right">Конкурентные предложения на тендере</dt>
                    <dd class="col-sm-8">{{ $tender->rivals }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right">Примечания</dt>
                    <dd class="col-sm-8">{{ $tender->comment }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right mt-2">Результат тендера</dt>
                    <dd class="col-sm-8 mt-2">{{ $tender->result }}</dd>
                    
                    <dt class="col-sm-4 text-left text-sm-right mt-2">Откуда поступила заявка</dt>
                    <dd class="col-sm-8 mt-2">@lang('site::tender.tender_source.'.$tender->source_id)</dd>
                    

                </dl>   
            </div>
        </div>
        <div class="card mb-2">
            <div class="card-body pb-0">
            <h5 class="card-title">Адрес объекта строительства</h5>
                <dl class="row mb-0">

                    <dt class="col-sm-4 text-left text-sm-right">Регион, город</dt>
                    <dd class="col-sm-8">{{ $tender->region->name }}, {{ $tender->city }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Адрес</dt>
                    <dd class="col-sm-8">{{ $tender->address }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Дополнение к адресу</dt>
                    <dd class="col-sm-8">{{ $tender->address_addon }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Название объекта</dt>
                    <dd class="col-sm-8">{{ $tender->address_name }}</dd>
                    <dt class="col-sm-4 text-left text-sm-right">Категория объекта</dt>
                    <dd class="col-sm-8">{{ $tender->category->name }}</dd>

                </dl>   
            </div>
        </div>
        
        <div class="card mb-2">
            <div class="card-body pb-0">
            <h5 class="card-title">Субъекты строительства</h5>
                <dl class="row mb-0">

                    
                    @foreach($tender->roleCustomers('investor') as $customer)
                    
                    <dt class="col-sm-4 text-left text-sm-right">Инвестор</dt>
                    <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif
                                                                    </dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('customer') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Заказчик</dt>
                    <dd class="col-sm-8">{{ $customer->name }} <a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif
                                                                    </dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('gen_contractor') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Генподрядчик</dt>
                    <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif</dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('gen_designer') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Генпроектировщик</dt>
                   <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif</dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('designer') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Проектировщики</dt>
                    <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif</dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('contractor') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Подрядчики</dt>
                    <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif</dd>
                    @endforeach
                    
                    @foreach($tender->roleCustomers('picker') as $customer)
                    <dt class="col-sm-4 text-left text-sm-right">Комплектовщик</dt>
                    <dd class="col-sm-8">{{ $customer->name }}<a class="dynamic-modal-form-button"  data-form-action="{{route('admin.customers.show', $customer)}}"  
                                                                    href="javascript:void(0);"> <i class="fa fa-external-link"></i></a>
                                                                    @if(!empty($customer->contacts()->find($customer->pivot->customer_contact_id)))
                                                                       Контакт: <a class="dynamic-modal-form-button"  
                                                                       data-form-action="{{ route('admin.customer_contact.show', $customer->contacts()->find($customer->pivot->customer_contact_id)) }}"
                                                                       href="javascript:void(0);">{{ $customer->contacts()->find($customer->pivot->customer_contact_id)->name }}</a>
                                                                    @endif</dd>
                    @endforeach
                    
                    <dt class="col-sm-4 text-left text-sm-right">Дистрибьютор</dt>
                    <dd class="col-sm-8"><a href="{{route('admin.users.show',$tender->distributor)}}">{{ $tender->distributor->name }}</a></dd>

                    <dt class="col-sm-4 text-left text-sm-right">Плательщик дистрибьютора</dt>
                    <dd class="col-sm-8">
                    @if(!empty($tender->contragent))
                    <a href="{{route('admin.contragents.show',$tender->contragent)}}">{{ $tender->contragent->name }}</a>
                    @endif
                    @if($tender->distributor->contragents()->count() > 0)
        
                        <a onclick="document.getElementById('contragent-edit-form').classList.toggle('d-none')"
                           class="py-0 px-1 btn btn-ms btn-sm"
                           href="javascript:void(0)">
                            <i class="fa fa-pencil"></i>
                            @lang('site::messages.edit')
                        </a>
                        <div class="d-none" id="contragent-edit-form">
                       
                            <form method="POST"
                                  action="{{route('admin.tenders.contragent_update', $tender)}}">
                                @csrf
                                @method('PATCH')
                                        <select required
                                                id="contragent_id"
                                                class="form-control"
                                                name="distr_contragent_id">
                                            @foreach($tender->distributor->contragents as $contragent)
                                                <option @if(old('distr_contragent_id', $tender->distr_contragent_id) == $contragent->id) selected
                                                        @endif
                                                        value="{{$contragent->id}}">
                                                    {{$contragent->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        
                                    
                                
                                <button class="btn btn-success py-1" type="submit">
                                    <i class="fa fa-save"></i>
                                    @lang('site::messages.save')
                                </button>
                            </form>
                        
                        </div>
                        @else
                           <span class="text-danger"> У дистрибьютора не добавлены юридические лица в личном кабинете.</span>
                        @endif                        
                    </dd>
                    <dt class="col-sm-4 text-left text-sm-right">Телефон контактного лица дистибьютора</dt>
                    <dd class="col-sm-8">{{ $tender->distr_contact_phone }}</dd>

                    <dt class="col-sm-4 text-left text-sm-right">Контакты дистрибьютора</dt>
                    <dd class="col-sm-8">{{ $tender->distr_contact }}</dd>


                </dl>   
            </div>
        </div>
        
        <div class="card mb-2">
            <div class="card-body pb-0">
                <h5 class="card-title">Прикрепленные файлы</h5>
                @include('site::file.files')
            </div>
        </div>
        <h3 id="end"></h3>   
                @include('site::message.comment', ['commentBox' => $commentBox])
                        
                 
        
    </div>
@endsection

