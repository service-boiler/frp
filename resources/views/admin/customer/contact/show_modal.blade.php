            <h5>Контактное лицо</h5> 
            
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
            

