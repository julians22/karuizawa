@extends('frontend.layouts.main')

@section('title', __('Customer Booking'))

@section('content')
    <booking-component
        csrf="{{ csrf_token() }}"
        api_booking_url="{{ secure_url('api/orders') }}"
        :user="{{ $logged_in_user }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_logout="{{ route('frontend.auth.logout') }}"></booking-component>
@endsection
