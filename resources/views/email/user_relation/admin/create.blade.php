@extends('layouts.email')

@section('title')
    @lang('site::user.relation_email.title')
@endsection

@section('h1')
    @lang('site::user.relation_email.h1')
@endsection

@section('body')
    <p> 
            {{ $userRelation->child->name }} => {{ $userRelation->parent->name }} <br />
        <a class="btn btn-ms" href="{{ route('admin.users.show', $userRelation->parent) }}">
          {{ route('admin.users.show', $userRelation->parent) }} </a>
    </p>
@endsection