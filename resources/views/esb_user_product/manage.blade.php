@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('esb-user-products.index') }}">@lang('site::user.esb_user_product.index')</a>
            </li>
            <li class="breadcrumb-item active">{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</li>
        </ol>

        @alert()@endalert()
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">@lang('site::messages.has_error')</h4>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <a href="{{ route('home') }}" class="btn btn-secondary mb-2"><i class="fa fa-reply"></i> @lang('site::messages.home')</a>
        <div class="row justify-content-center mb-5">
            <div class="col">
                    <div class="card mb-2">
                        <div class="card-body">
                            <div class="form-row mb-3" id="product" data-device-id="{{$esbUserProduct->device_id}}">
                                <div class="col-sm-9">
                                <small>({{$esbUserProduct->device_id}})</small>
                                   <h4>{{$esbUserProduct->product ? $esbUserProduct->product->name : $esbUserProduct->product_no_cat}}</h4>
                                   <dl class="row mb-0"> 
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.current_temp')</dt>
                                        <dd class="col-sm-9 h3"><span  id="product_temp_{{$esbUserProduct->device_id}}"></span>&deg;C</dd>
                                            <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.preffered_temp')</dt>
                                            <dd class="col-sm-9 h3"><span id="product_prefferedTemp_{{$esbUserProduct->device_id}}"></span>&deg;C</dd>
                                        <dt class="col-sm-3 text-left text-sm-right">@lang('site::user.esb_user_product.flame')</dt>
                                        <dd class="col-sm-9 h3 rng d-none" id="product_flame_{{$esbUserProduct->device_id}}"><i class="fa fa-fire"></i> горелка включена</dd>
                                        <dd class="col-sm-9 h3" id="product_flame_off_{{$esbUserProduct->device_id}}"><i class="fa fa-fire"></i> в ожидании</dd>
                                    <dt class="col-sm-3 text-left text-sm-right mt-5">Установить температуру:</dt>
                                        
                                            <dd class="col-sm-9 h3 mt-5">
                                            <input class="mx-0 w-50" data-toggle="popover" data-placement="top"
                                                   data-trigger="focus"
                                                   id="temp_set_{{$esbUserProduct->device_id}}"
                                                   data-content="@lang('site::cart.item_hint_content', ['max' => '90'])"
                                                   min="1" max="90"
                                                   pattern="([1-9])|([1-9][0-9])" type="number"
                                                   value="" required />&deg;C
                                                   <button type="submit" class="btn btn-ms temp_sub" data-device-id="{{$esbUserProduct->device_id}}">Сохранить</button>
                                                   
                                                   <small class="text-success text-small d-none" id="temp_set_help_{{$esbUserProduct->device_id}}">
                                                    <br />Команда будет отправлена в течение 5 секунд</small>
                                                   </dd>
                                        
                                    
                                    
                                    </dl>
                                
                                </div>
                                <div class="col-sm-3">
                                    @if($esbUserProduct->product)
                                        <img style="height:150px" src="{{$esbUserProduct->product->images()->first()->src()}}">
                                    @endif
                                </div>
                                
                            </div>
                            
                            
                                
                            
                            
                            <div class="form-row mb-3">
                                                      
                            
                            <a class="btn btn-sm btn-secondary" href="{{route('esb-user-products.edit',$esbUserProduct)}}">Изменить данные оборудования</a>
                            
                            </div>
                            
                         
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
                
            let deviceId=$('#product')[0].dataset.deviceId;
            
            function temperature(deviceId) {
            $.get("/api/mqtt/getstatusarc/"+deviceId,function(data){
                $('#product_temp_'+deviceId).html(data.temperature);
                $('#product_prefferedTemp_'+deviceId).html(data.prefferedTemp);
                
                if(data.flame==1) {
                    $('#product_flame_'+deviceId).removeClass('d-none');
                    $('#product_flame_off_'+deviceId).addClass('d-none');
                }else{
                $('#product_flame_'+deviceId).addClass('d-none');
                    $('#product_flame_off_'+deviceId).removeClass('d-none');
                }
            });
            }
            function temperatureload(deviceId) {
            $.get("/api/mqtt/getstatusarc/"+deviceId,function(data){
                $('#product_temp_'+deviceId).html(data.temperature);
                $('#product_prefferedTemp_'+deviceId).html(data.prefferedTemp);
                $('#temp_set_'+deviceId).val(data.prefferedTemp);
                
                if(data.flame==1) {
                    $('#product_flame_'+deviceId).removeClass('d-none');
                    $('#product_flame_off_'+deviceId).addClass('d-none');
                }else{
                $('#product_flame_'+deviceId).addClass('d-none');
                    $('#product_flame_off_'+deviceId).removeClass('d-none');
                }
            });
            }
            
            temperatureload(deviceId);
            
            let timerId = setInterval(() => temperature(deviceId), 5000);
            
            $(document).on('click', '.temp_sub', (function(I){
                let prefferedTemp=$('#temp_set_'+$(this)[0].dataset.deviceId).val();
                $.get("/api/mqtt/settemperature/"+deviceId,{ "temperature":prefferedTemp},function(data){
                    setTimeout(() => { temperature(deviceId); }, 5000);
                });
                $('#temp_set_help_'+deviceId).removeClass('d-none');
            }));             
        
        
        });

    } catch (e) {
        console.log(e);
    }

</script>
@endpush
