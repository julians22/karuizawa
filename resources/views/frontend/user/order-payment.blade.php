@extends('frontend.layouts.main')

@section('title', __('Payment'))

@section('content')
    <payment-component
    csrf="{{ csrf_token() }}"
    :user="{{ $logged_in_user }}"
    route_edit_profile="{{ route('frontend.user.edit-profile') }}"
    route_logout="{{ route('frontend.auth.logout') }}"
    :order="{{ $order }}"></payment-component>
@endsection
