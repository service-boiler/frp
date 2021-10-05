@extends('layouts.email')

@section('title')
    @lang('site::user.relation_email.title')
@endsection

@section('h1')
    @lang('site::user.relation_email.h1')
@endsection

@section('body')
    <p> @lang('site::user.relation_email.text_user')<br />
        {{ $userRelation->child->name }} <br />
        <a class="btn btn-ms" href="{{ route('user_relations.index') }}">
            {{ route('user_relations.index') }} </a>
    </p>
@endsection