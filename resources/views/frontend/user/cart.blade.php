@extends('frontend.layouts.main')

@section('title', __('Cart'))

@section('content')
    <cart-component
        csrf="{{ csrf_token() }}"
        :user="{{ $logged_in_user }}"
        api_product_url="{{ secure_url('api/products') }}"
        api_store_order="{{ secure_url('api/store-order') }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_my_target="{{ route('crew.target') }}"
        :coupons='@json($coupons)'
        route_logout="{{ route('frontend.auth.logout') }}">
    </cart-component>
@endsection
