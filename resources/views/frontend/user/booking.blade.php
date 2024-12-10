@extends('frontend.layouts.main')

@section('title', __('Ready to wear'))

@section('content')
    <booking-component csrf="{{ csrf_token() }}" :user="{{ $logged_in_user }}" route_edit_profile="{{ route('frontend.user.account') }}" route_logout="{{ route('frontend.auth.logout') }}"></booking-component>
@endsection
