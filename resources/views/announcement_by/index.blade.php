@extends('layouts.app')

@section('title')@lang('site::announcement.announcements')@lang('site::messages.title_separator')@endsection
@push('headers')
<meta property="og:url"           content="http://service.ferroli.by/announcements" />
  <meta property="og:type"          content="website" />
  <meta property="og:title"         content="Новости компании Ferroli" />
  <meta property="og:image"         content="http://service.ferroli.by//storage/images/banners/nGtryuL2QZ9VRU7Gjur2kIz2tIMi8NITEVivNTBB.jpeg" />
@endpush
@section('header')
    @include('site::header.front',[
        'h1' => __('site::announcement.announcement'),
        'breadcrumbs' => [
            ['url' => route('index'), 'name' => __('site::messages.index')],
            ['name' => __('site::announcement.announcement')]
        ]
    ])
@endsection
@section('content')
    <div class="container">
        {{$announcements->render()}}
        <div class="row announcement-list">
            @each('site::announcement.index.row', $announcements, 'announcement')
        </div>
        {{$announcements->render()}}
    </div>
@endsection