@extends('admin.app')

@section('content')
    @include('admin.includes.navbar')
{{--    @dd(Auth::guard('admin')->user()->unreadNotifications->count())--}}

@endsection('content')
