@extends('frontend.layouts.main')

@section('title', __('Custom made outer shirt'))

@section('content')
    <semi-custom-outer
        csrf="{{ csrf_token() }}"
        :data_semi_custom_outer="{{ $dataSemiCustomOuter }}"
        api_store_order="{{ secure_url('api/store-order') }}"
        api_customer_size="{{ secure_url('api/semi-custom/customer-size') }}"
        :user="{{ $logged_in_user }}"
        route_edit_profile="{{ route('frontend.user.edit-profile') }}"
        route_my_target="{{ route('crew.target') }}"
        route_logout="{{ route('frontend.auth.logout') }}">
    </semi-custom-outer>
@endsection
