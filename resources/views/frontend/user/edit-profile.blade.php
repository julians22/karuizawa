@extends('frontend.layouts.main')

@section('title', __('Edit Profile'))

@section('content')
    <edit-profile csrf="{{ csrf_token() }}" :user="{{ $logged_in_user }}" route_update_user={{secure_url('api/profile/update')}} route_logout="{{ route('frontend.auth.logout') }}"></edit-profile>
@endsection
