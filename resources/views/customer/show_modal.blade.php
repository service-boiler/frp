        <div class="card mb-2">
            <div class="card-body">
                    <dl class="row mb-1">
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.name_show'):</dt>
                    <dd class="col-sm-9"><a href="{{route('admin.customers.show',$customer)}}">{!!$customer->name!!}</a></dd>
                    
                    
                    @foreach($customer->customerRoles as $role)
                                    <dt class="col-sm-3 text-left text-sm-right"></dt><dd class="col-9"><i class="fa fa-check text-success"></i> {{$role->title}}</dd>
                                @endforeach
                     
                    
                    
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.region_locality'):</dt>
                    <dd class="col-sm-9">@if($customer->region){{$customer->region->name}}, {{$customer->locality}}@endif</dd>
                    
                    @if(!empty($customer->phone))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.phone'):</dt>
                    <dd class="col-sm-9">{!!$customer->phone!!}</dd>
                    @endif
                    
                    @if(!empty($customer->email))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.email'):</dt>
                    <dd class="col-sm-9">{!!$customer->email!!}</dd>
                    @endif
                    @if(!empty($customer->any_contacts))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.any_contacts'):</dt>
                    <dd class="col-sm-9">{{$customer->any_contacts}}</dd>
                    @endif
                    @if(!empty($customer->comment))
                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.comment'):</dt>
                    <dd class="col-sm-9">{!!$customer->comment!!}</dd>
                    @endif
                     
                </dl>
                
                
                </div>
            </div>
            <h5>Контактные лица</h5> 
            @foreach($customer->contacts as $contact)
            <div class="card mb-2" id="contact-{{$contact->id}}">
                <div class="card-body">
                    <dl class="row mb-1">
                                    <dt class="col-sm-3 text-left text-sm-right">Имя</dt><dd class="col-9"> <b>{{$contact->name}}</b></dd>
                                    <dt class="col-sm-3 text-left text-sm-right">Должность</dt><dd class="col-9"> {{$contact->position}} @if($contact->lpr)<span class="text-success">@lang('site::admin.customer.lpr')</span>@endif</dd>
                                    @if(!empty($contact->phone))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.phone_contact'):</dt>
                                    <dd class="col-sm-9">{!!$contact->phone!!}</dd>
                                    @endif
                                    
                                    @if(!empty($contact->email))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.email_contact'):</dt>
                                    <dd class="col-sm-9">{!!$contact->email!!}</dd>
                                    @endif
                                    @if(!empty($contact->any_contacts))
                                    <dt class="col-sm-3 text-left text-sm-right">@lang('site::admin.customer.any_contacts_contact'):</dt>
                                    <dd class="col-sm-9">{{$contact->any_contacts}}</dd>
                                    @endif
                                   
                      
                     
                </dl>
                </div>
            </div>
             @endforeach

