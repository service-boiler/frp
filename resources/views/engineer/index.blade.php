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
            <li class="breadcrumb-item active">@lang('site::engineer.engineers')</li>
        </ol>
        <h1 class="header-title mb-4"><i
                    class="fa fa-@lang('site::engineer.icon')"></i> @lang('site::engineer.engineers')</h1>
        @alert()@endalert
        <div class="card-deck mb-4">
            
            <a href="{{ route('home') }}" class="d-block d-sm-inline btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_home')</span>
            </a>
        </div>
        <div class="card-deck mb-4">
        <table class="table table-bordered bg-white table-sm table-hover"><thead>
        <th>@lang('site::engineer.engineer_id')</th>
        <th>@lang('site::engineer.name')</th>
        <th>@lang('site::engineer.address')</th>
        <th>@lang('site::engineer.phone')</th>
        <th>@lang('site::engineer.email')</th>
        
        <th>@lang('site::certificate.certificate')<br />{{$certificate_types->where('id','1')->first()->name}} и {{$certificate_types->where('id','2')->first()->name}}</th>
        <th>@lang('site::certificate.certificate')<br />{{$certificate_types->where('id','2')->first()->name}}</th>
        
        <th></th>
        </thead>
        <tbody>
        @foreach($engineers as $engineer)
        <tr>
        
        <div id="engineer-{{$engineer->id}}">
            <td class="align-middle">{{$engineer->id}}</td>
            <td class="align-middle">{{$engineer->name}}</td>
            <td class="align-middle">{{$engineer->address}}</td>
            <td class="align-middle">{{$engineer->country->phone}}{{$engineer->phone}}</td>
            <td class="align-middle">{{$engineer->email}}</td>
            
            <td class="align-middle">
            @if($engineer->user->type_id=='3')
                Доступен только <br /> для юр.лиц.
            @else
                @if(!empty($engineer->certificateService()))
                {{$engineer->certificateService()->id}}
                    <br />
                    @if($engineer->user->hasRole('asc'))
                    <a class="btn btn-success" href="{{route('certificates.show', $engineer->certificateService()->id)}}" target="_blank">
                    @else
                    <a class="btn btn-success" href="javascript:void(0);" data-toggle="tooltip" data-placement="top" data-original-title="Необходима авторизация сервисного центра. Пройдите процедуру авторизации.">
                    @endif
                    <i class="fa fa-download"></i>PDF</a>
                    
                @endif
             {{--
                <form class="d-sm-inline-block" action="{{route('certificates_service.store')}}" method="POST">
                    @csrf
                        <input type="hidden" class="form-control{{ $errors->has('engineer_id') ? ' is-invalid' : '' }}" name="engineer_id" value="{{$engineer->id}}"/>
                        <button class="btn btn-primary" type="submit" data-toggle="tooltip" data-placement="top" data-original-title="полчить результаты тестирования"><i class="fa fa-download"></i></button>
                    
                </form>
                <a class="btn btn-warning d-sm-inline-block" href="
                {{route('testservice', [$engineer->id, '1','open'])}}" target="_blank"  data-toggle="tooltip" data-placement="top" data-original-title="Ссылка для прохождения теста.">
                        <i class="fa fa-check-square-o"></i></a>
                <a class="btn btn-dark d-sm-inline-block" href="
                {{route('testservice', [$engineer->id, '1','send'])}}"  data-toggle="tooltip" data-placement="top" data-original-title="Отправить ссылку на тест инженеру.">
                        <i class="fa fa-envelope-o"></i></a> --}}
                @endif
            </td>
            
            <td class="align-middle">
            @if($engineer->user->type_id!='3')
                        Доступен только <br /> для физ.лиц.
            @else
            
                    @if(!empty($engineer->certificateMounter()))
                    {{$engineer->certificateMounter()->id}}
                    <br /><a class="btn btn-success" href="{{route('certificates.show', $engineer->certificateMounter()->id)}}" target="_blank">
                        <i class="fa fa-download"></i>PDF</a>
                    
                    @endif
                
                <a class="btn btn-warning d-sm-inline-block" href="
                {{route('home_academy')}}" target="_blank"  data-toggle="tooltip" data-placement="top" data-original-title="Обучение и сертификаты">
                        <i class="fa fa-external-link"></i>Обучение</a>
             
                @endif
            </td>
               
            <td class="align-middle"> <a href="{{route('engineers.edit', $engineer)}}"
                               class="@cannot('edit', $engineer) disabled @endcannot btn btn-sm btn-secondary">
                                <i class="fa fa-pencil"></i>
                                @lang('site::messages.edit')
                            </a>
                            <br />
                            <a class="@cannot('delete', $engineer) disabled @endcannot btn btn-sm btn-danger btn-row-delete"
                               title="@lang('site::messages.delete')"
                               href="javascript:void(0);"
                               data-form="#engineer-delete-form-{{$engineer->id}}"
                               data-btn-delete="@lang('site::messages.delete')"
                               data-btn-cancel="@lang('site::messages.cancel')"
                               data-label="@lang('site::messages.delete_confirm')"
                               data-message="@lang('site::messages.delete_sure') {{ $engineer->name }}?"
                               data-toggle="modal"
                               data-target="#form-modal">
                                <i class="fa fa-close"></i>
                                @lang('site::messages.delete')
                            </a>
                            <form id="engineer-delete-form-{{$engineer->id}}"
                                  action="{{route('engineers.destroy', $engineer)}}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                            </form>   
            </td>
        </div>
        </tr>
        @endforeach
        
        <tbody>
        </table>
        <strong>Внимание! </strong> <br />Инженеру необходимо зарегистрироваться как физическое лицо на сайте, пройти обучение и выполнить задания тестов.
        Также необходимо в личном кабинете инженера отправить заявку на привязку к Вашей организации.
        После успешного прохождения online-обучения инженер автоматически появится в Вашем списке инженеров. 
        <br />
        <br />
        С 1 января 2021 года отчеты о гарантийном ремонте будут приниматься только при наличии авторизованных сервисных инженеров (зарегистрированных и прошедших online-тестирование)
        
        <br /><br />
        <a class="disabled btn btn-ms d-block d-sm-inline mr-0 mr-sm-1 mb-1 mb-sm-0"
               href="{{ route('engineers.create') }}"
               role="button">
                <i class="fa fa-plus"></i>
                <span>@lang('site::messages.add') @lang('site::engineer.engineer')</span>
            </a>
        </div>
        <div class="card-deck mb-4">
        <table>
        <tbody>
        <tr>
        <td class="align-middle"><span class="btn btn-success"><i class="fa fa-download"></i>PDF</span></td>
        <td class="align-middle">кнопка для скачивания сертификата в формате PDF.</td>
        </tr>
       {{-- <tr>
        <td class="align-middle"><span class="btn btn-primary" href=""><i class="fa fa-download"></i></span></td>
        <td class="align-middle">Полчить результаты тестирования для заполнения сертификата</td>
        </tr>
        <tr>
        <td class="align-middle"><span  class="btn btn-warning" href=""><i class="fa fa-check-square-o"></i></span> </td>
        <td class="align-middle">Ссылка для прохождения теста.</td>
        </tr>
        <tr>
        <td class="align-middle"><span class="btn btn-dark"  href=""><i class="fa fa-envelope-o"></i></span> </td>
        <td class="align-middle">Отправить ссылку для прохождения теста инженеру на электронную почту.</td>
        </tr> --}}
        </tbody>
        </table>
        
    </div>
@endsection
