@extends('frontend.layouts.main')

@section('title', __('Custom made shirt'))

@section('content')
    <semi-custom
        csrf="{{ csrf_token() }}"
        :data_custom_shirt="{{ $dataCustomShirt }}"
        :data_custom_request="{{ $dataCustomRequest }}"
        :data_semi_custom="{{ $dataSemiCustom }}"
        api_store_order="{{ secure_url('api/store-order') }}"
        api_customer_size="{{ secure_url('api/semi-custom/customer-size') }}"
        :user="{{ $logged_in_user }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_logout="{{ route('frontend.auth.logout') }}">
    </semi-custom>
@endsection
