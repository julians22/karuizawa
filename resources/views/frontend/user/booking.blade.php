@extends('frontend.layouts.main')

@section('title', __('Customer Booking'))

@section('content')
    <booking-component
        csrf="{{ csrf_token() }}"
        api_booking_url="{{ secure_url('api/orders') }}"
        api_incoming_url="{{ secure_url('api/incoming-orders') }}"
        api_fitting_url="{{ secure_url('api/fitting-orders') }}"
        api_set_handling="{{ secure_url('api/set-handling-date') }}"
        api_set_status="{{ secure_url('api/set-status') }}"
        :user="{{ $logged_in_user }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_my_target="{{ route('crew.target') }}"
        route_logout="{{ route('frontend.auth.logout') }}"></booking-component>
@endsection
