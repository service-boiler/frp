@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('ferroli-user.home') }}">@lang('site::messages.admin')</a>
            </li>
            <li class="breadcrumb-item active">@lang('site::user.users')</li>
        </ol>
        @alert()@endalert

        <div class=" border p-3 mb-2">

            <a href="{{ route('admin.users.create') }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 mb-sm-0 btn-ms">
                <i class="fa fa-plus"></i>
                <span>Создать пользователя</span>
            </a>
            <a href="{{ route('admin.mailings.create') }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 mb-sm-0 btn-ms">
                <i class="fa fa-@lang('site::mailing.icon')"></i>
                <span>@lang('site::messages.create') @lang('site::mailing.mailing')</span>
            </a>
            <a href="{{ route('admin.messagings.create') }}"
               class="d-block d-sm-inline-block btn mr-0 mr-sm-1 mb-1 mb-sm-0 btn-green">
                <i class="fa fa-send"></i>
                <span>Создать рассылку сообщений в ЛК</span>
            </a>
            
            <button form="repository-form" type="submit" name="filter" class="d-none"> <!-- Для того, чтобы по Enter фильтр применялся, а не другие кнопки -->
                <i class="fa fa-search"></i>
            </button>
            
            <button form="repository-form"
                    type="submit"
                    name="excel"
                    class="d-block d-sm-inline-block mr-0 mr-sm-1 mb-1 mb-sm-0 btn btn-primary">
                <i class="fa fa-upload"></i>
                <span>@lang('site::messages.upload') @lang('site::messages.to_excel')</span>
            </button>
            <a href="{{ route('ferroli-user.home') }}" class="d-block d-sm-inline-block btn btn-secondary">
                <i class="fa fa-reply"></i>
                <span>@lang('site::messages.back_admin')</span>
            </a>
        </div>

        {{$users->render()}}
        @filter(['repository' => $repository])@endfilter
        @pagination(['pagination' => $users])@endpagination

        <table class="table table-bordered bg-white table-sm table-hover">
            <tr>
                <th rowspan="2">Имя</th>
                <th rowspan="2">Основной регион, <br />Город</th>
                <th rowspan="2">Организация</th>
                <th rowspan="2">Тип</th>
                <th rowspan="2">Роли пользователя</th>
                <th rowspan="2">Сертификаты</th>
                <th rowspan="2">Монта<br />жей</th>
                <th rowspan="2">Email</th>
                <th colspan="2">На карте</th>
                <th rowspan="2">Дата рег.</th>
                <th rowspan="2">Вход</th>
                            </tr>
                            <tr>
                <th>срв</th>
                <th>длр</th></tr>
            @foreach($users as $user)
                @include('site::admin.user.index.row')
            @endforeach
        </table>
            

        {{$users->render()}}
    </div>
@endsection
