@extends('frontend.layouts.main')

@section('title', __('Custom made Light Jacket'))

@section('content')
    <semi-custom-light-jacket
        csrf="{{ csrf_token() }}"
        :data_semi_custom_light_jacket="{{ $dataSemiCustomLightJacket }}"
        api_store_order="{{ secure_url('api/store-order') }}"
        api_customer_size="{{ secure_url('api/semi-custom/customer-size') }}"
        :user="{{ $logged_in_user }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_my_target="{{ route('crew.target') }}"
        route_logout="{{ route('frontend.auth.logout') }}">
    </semi-custom-light-jacket>
@endsection
