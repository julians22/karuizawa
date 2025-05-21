@extends('frontend.layouts.main')

@section('title', __('Payment'))

@section('content')
    <payment-component
    csrf="{{ csrf_token() }}"
    :user="{{ $logged_in_user }}"
    route_edit_profile="{{ route('frontend.user.edit-profile') }}"
    route_my_target="{{ route('crew.target') }}"
    route_payment="{{ secure_url('api/send-payment') }}"
    route_logout="{{ route('frontend.auth.logout') }}"
    booking_route="{{ route('frontend.user.booking') }}"
    :order="{{ $order }}"></payment-component>
@endsection
