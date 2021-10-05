@extends('layouts.app')

@section('content')
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('index') }}">@lang('site::messages.index')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('home') }}">@lang('site::messages.home')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('contragents.index') }}">@lang('site::contragent.contragents_user')</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('contragents.show', $contragent) }}">{{ $contragent->name }}</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::messages.edit')</li>
        </ol>
        <h1 class="header-title mb-4">@lang('site::messages.edit') {{ $contragent->name }}</h1>

        @alert()@endalert()

        <div class="card mt-2 mb-2">
            <div class="card-body">
                <form id="contragent-form" method="POST"
                      action="{{ route('contragents.update', $contragent) }}">

                    @csrf
                    @method('PUT')

                    <h4 class=" mt-3" id="sc_info">@lang('site::contragent.header.contragent')</h4>

                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="contragent_name">@lang('site::contragent.help.name')</label>
                            <input type="text"
                                   name="contragent[name]"
                                   id="contragent_name" required
                                   class="form-control{{ $errors->has('contragent.name') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contragent.placeholder.name')"
                                   value="{{ old('contragent.name', $contragent->name) }}">
                            <span class="invalid-feedback">{{ $errors->first('contragent.name') }}</span>
                        </div>
                    </div>
                    <div class="form-row required">
                        <div class="col mb-3">
                            <label class="control-label"
                                   for="contragent_name_short">@lang('site::contragent.help.name')</label>
                            <input type="text"
                                   name="contragent[name_short]"
                                   id="contragent_name_short" required
                                   class="form-control{{ $errors->has('contragent.name_short') ? ' is-invalid' : '' }}"
                                   placeholder="@lang('site::contragent.placeholder.name_short')"
                                   value="{{ old('contragent.name', $contragent->short_name) }}">
                            <span class="invalid-feedback">{{ $errors->first('contragent.name_short') }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_type_id">@lang('site::contragent.type_id')</label>
                                    @foreach($types as $type)
                                        <div class="custom-control custom-radio">
                                            <input type="radio"
                                                   id="contragent_type_id_{{ $type->id }}"
                                                   @if(old('contragent.type_id', $contragent->type_id) == $type->id)
                                                   checked
                                                   @endif
                                                   name="contragent[type_id]"
                                                   value="{{ $type->id }}"
                                                   class="custom-control-input">
                                            <label class="custom-control-label"
                                                   for="contragent_type_id_{{ $type->id }}">{{ $type->name }}</label>
                                            <span class="invalid-feedback">{{ $errors->first('contragent.type_id') }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="contragent_nds">@lang('site::contragent.nds')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_1"
                                                       name="contragent[nds]"
                                                       required
                                                       @if(old('contragent.nds', $contragent->nds) == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('contragent.nds') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_0"
                                                       name="contragent[nds]"
                                                       required
                                                       @if(old('contragent.nds', $contragent->nds) == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('contragent.nds') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('contragent.nds') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-row required">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="contragent_nds_act">@lang('site::contragent.nds_act')</label>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_act_1"
                                                       name="contragent[nds_act]"
                                                       required
                                                       @if(old('contragent.nds_act', $contragent->nds_act) == 1) checked @endif
                                                       value="1"
                                                       class="custom-control-input {{$errors->has('contragent.nds_act') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_act_1">@lang('site::messages.yes')</label>
                                            </div>
                                            <div class="custom-control custom-radio">
                                                <input type="radio"
                                                       id="contragent_nds_act_0"
                                                       name="contragent[nds_act]"
                                                       required
                                                       @if(old('contragent.nds_act', $contragent->nds_act) == 0) checked @endif
                                                       value="0"
                                                       class="custom-control-input {{$errors->has('contragent.nds_act') ? ' is-invalid' : ''}}">
                                                <label class="custom-control-label"
                                                       for="contragent_nds_act_0">@lang('site::messages.no')</label>
                                            </div>
                                            <span class="invalid-feedback">{{ $errors->first('contragent.nds_act') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-4 mt-2" id="company_info">@lang('site::contragent.header.legal')</h4>

                            <div class="form-row required">
                                <div class="col-9 mb-3">
                                    <label class="control-label"
                                           for="contragent_inn">@lang('site::contragent.inn') (ИНН изменять нельзя! Добавьте новое юр.лицо.</label>
                                    <input type="text" disabled
                                           id="contragent_inn"
                                           maxlength="12"
                                           name="contragent[inn]"
                                           class="form-control{{ $errors->has('contragent.inn') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.inn')"
                                           value="{{ old('contragent.inn', $contragent->inn) }}">
                                    <span class="invalid-feedback">{{ $errors->first('contragent.inn') }}</span>
                                </div>
                                <div class="col-3 mb-3">
                                    <label class="control-label"
                                           for="contragent_refresh">Обн. из ФНС</label>
                                    <button id="contragent_refresh" type="button" class="btn btn-green w-100"><i class="fa fa-refresh"></i></button>
                                </div>
                            </div>


                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_ogrn">@lang('site::contragent.ogrn')</label>
                                    <input type="text"
                                           name="contragent[ogrn]"
                                           id="contragent_ogrn"
                                           maxlength="15"
                                           required
                                           class="form-control{{ $errors->has('contragent.ogrn') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.ogrn')"
                                           value="{{ old('contragent.ogrn', $contragent->ogrn) }}">
                                    <span class="invalid-feedback">{{ $errors->first('contragent.ogrn') }}</span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_okpo">@lang('site::contragent.okpo')</label>
                                    <input type="text"
                                           name="contragent[okpo]"
                                           id="contragent_okpo"
                                           maxlength="10"
                                           required
                                           class="form-control{{ $errors->has('contragent.okpo') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.okpo')"
                                           value="{{ old('contragent.okpo', $contragent->okpo) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.okpo') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_kpp">@lang('site::contragent.kpp')</label>
                                    <input type="text"
                                           name="contragent[kpp]"
                                           id="contragent_kpp"
                                           class="form-control{{ $errors->has('contragent.kpp') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.kpp')"
                                           value="{{ old('contragent.kpp', $contragent->kpp) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.kpp') }}</strong>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-4 mt-2"
                                id="company_info">@lang('site::contragent.header.payment')</h4>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_rs">@lang('site::contragent.rs')</label>
                                    <input type="text"
                                           name="contragent[rs]"
                                           required
                                           id="contragent_rs" maxlength="20"
                                           class="form-control{{ $errors->has('contragent.rs') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.rs')"
                                           value="{{ old('contragent.rs', $contragent->rs) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.rs') }}</strong>
                                    </span>
                                </div>
                            </div>


                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_bik">@lang('site::contragent.bik')</label>
                                    <input type="text"
                                           name="contragent[bik]"
                                           id="contragent_bik"
                                           required
                                           maxlength="11"
                                           class="form-control{{ $errors->has('contragent.bik') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.bik')"
                                           value="{{ old('contragent.bik', $contragent->bik) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.bik') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_bank">@lang('site::contragent.bank')</label>
                                    <input type="text"
                                           name="contragent[bank]"
                                           id="contragent_bank"
                                           required
                                           maxlength="255"
                                           class="form-control{{ $errors->has('contragent.bank') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.bank')"
                                           value="{{ old('contragent.bank', $contragent->bank) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.bank') }}</strong>
                                    </span>
                                </div>
                            </div>

                            <div class="form-row required">
                                <div class="col mb-3">
                                    <label class="control-label"
                                           for="contragent_ks">@lang('site::contragent.ks')</label>
                                    <input type="text"
                                           name="contragent[ks]"
                                           id="contragent_ks"
                                           maxlength="20"
                                           
                                           required
                                           class="form-control{{ $errors->has('contragent.ks') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::contragent.placeholder.ks')"
                                           value="{{ old('contragent.ks', $contragent->ks) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('contragent.ks') }}</strong>
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="row">
                        <div class="col">

                            {{-- ЮРИДИЧЕСКИЙ АДРЕС --}}

                            <h4 class="mb-2 mt-4">@lang('site::address.header.legal')</h4>
                            <input type="hidden" name="address[legal][type_id]" value="1">
                            <input type="hidden" name="address[legal][country_id]" value="{{config('site.country')}}">

                            <div class="form-row required">
                                <div class="col-sm-6 mb-3">

                                    <label class="control-label"
                                           for="address_legal_region_id">@lang('site::address.region_id')</label>
                                    <select class="form-control{{  $errors->has('address_legal.region_id') ? ' is-invalid' : '' }}"
                                            name="address[legal][region_id]"
                                            required
                                            id="address_legal_region_id">
                                        @if($regions->count() == 0 || $regions->count() > 1)
                                            <option value="">@lang('site::messages.select_from_list')</option>
                                        @endif
                                        @foreach($regions as $region)
                                            <option
                                                    @if(old('address_legal.region_id',optional($addressLegal)->region_id) == $region->id) selected
                                                    @endif
                                                    value="{{ $region->id }}">{{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address_legal.region_id') }}</strong>
                                    </span>
                                </div>

                                <div class="col-sm-6 mb-3">
                                    <label class="control-label"
                                           for="address_legal_locality">@lang('site::address.locality')</label>
                                    <input type="text"
                                           name="address[legal][locality]"
                                           id="address_legal_locality"
                                           required
                                           class="form-control{{ $errors->has('address_legal.locality') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.locality')"
                                           value="{{ old('address_legal.locality',optional($addressLegal)->locality) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address_legal.locality') }}</strong>
                                    </span>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-sm-4 mb-3">
                                    <label class="control-label"
                                           for="address_legal_street">@lang('site::address.street')</label>
                                    <input type="text"
                                           name="address[legal][street]"
                                           id="address_legal_street"
                                           class="form-control{{ $errors->has('address_legal.street') ? ' is-invalid' : '' }}"
                                           placeholder="@lang('site::address.placeholder.street')"
                                           value="{{ old('address_legal.street',optional($addressLegal)->street) }}">
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('address_legal.street') }}</strong>
                                    </span>
                                </div>
                                <div class="col-sm-3 form-group requiered">
                                    <label class="control-label"
                                               for="address_legal_building">@lang('site::address.building')</label>
                                        <input type="text"
                                               name="address[legal][building]"
                                               id="address_legal_building"
                                               required
                                               class="form-control{{ $errors->has('address_legal.building') ? ' is-invalid' : '' }}"
                                               placeholder="@lang('site::address.placeholder.building')"
                                               value="{{ old('address_legal.building',optional($addressLegal)->building) }}">
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('address_legal.building') }}</strong>
                                        </span>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_legal_apartment">@lang('site::address.apartment')</label>
                                            <input type="text"
                                                   name="address[legal][apartment]"
                                                   id="address_legal_apartment"
                                                   class="form-control{{ $errors->has('address.legal.apartment') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.apartment')"
                                                   value="{{ old('address.legal.apartment',optional($addressLegal)->apartment) }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address.legal.apartment') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-row">
                                        <div class="col mb-3">
                                            <label class="control-label"
                                                   for="address_legal_postal">@lang('site::address.postal')</label>
                                            <input type="text"
                                                   name="address[legal][postal]"
                                                   id="address_legal_postal"
                                                   class="form-control{{ $errors->has('address_legal.postal') ? ' is-invalid' : '' }}"
                                                   placeholder="@lang('site::address.placeholder.postal')"
                                                   value="{{ old('address_legal.postal',optional($addressLegal)->postal) }}">
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('address_legal.postal') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </form>
                <hr/>
                <div class=" mb-2 text-right">
                    <button form="contragent-form" type="submit"
                            class="btn btn-ms mb-1">
                        <i class="fa fa-check"></i>
                        <span>@lang('site::messages.save')</span>
                    </button>
                    <a href="{{ route('contragents.show', $contragent) }}" class="btn btn-secondary mb-1">
                        <i class="fa fa-close"></i>
                        <span>@lang('site::messages.cancel')</span>
                    </a>

                </div>
            </div>
        </div>

    </div>
@endsection
@push('scripts')
<script>

 try {
            window.addEventListener('load', function () {

                let contragent_type_id_1 = $('#contragent_type_id_1');
                let contragent_type_id_2 = $('#contragent_type_id_2');
                
                 
                contragent_type_id_1.on('change', function (e) {
                    $('#contragent_kpp').prop('disabled', false);
                }); 
                contragent_type_id_2.on('change', function (e) {
                    $('#contragent_kpp').prop('disabled', true);
                });


                $(document)
                    .on('click', '#contragent_refresh', (function(I){

                        $.get("/api/dadata/inn", { "str":$('#contragent_inn').val() },function(data) {
                          console.log(data[0]['alldata']);
                          let alldata=data[0]['alldata'];
                          if(data[0]['alldata']){
                              document.getElementById('contragent_ogrn').value = alldata.ogrn;
                              if(alldata.okpo){document.getElementById('contragent_okpo').value = alldata.okpo;}
                              if(alldata.kpp){document.getElementById('contragent_kpp').value = alldata.kpp;}

                              let address = alldata.address.data;

                              if(address.region_iso_code){document.getElementById('address_legal_region_id').value = address.region_iso_code;}
                              if(address.city_with_type){document.getElementById('address_legal_locality').value = address.city_with_type;}
                              if(address.street_with_type){document.getElementById('address_legal_street').value = address.street_with_type;}
                              if(address.house){document.getElementById('address_legal_building').value = address.house_type + ' ' +address.house;}
                              if(address.block){document.getElementById('address_legal_building').value = document.getElementById('address_legal_building').value + ' ' +address.block_type + ' ' +address.block;}
                              if(address.flat){document.getElementById('address_legal_apartment').value = address.flat_type + ' ' +address.flat;}
                              if(address.postal_code){document.getElementById('address_legal_postal').value = address.postal_code;}
                          }
                        })

                    }))
                ;

                

            });
        } catch (e) {
            console.log(e);
        }


</script>
@endpush