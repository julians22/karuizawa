@extends('backend.layouts.app')

@section('title', __('Order Management'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Order Management')
    </x-slot>

    <x-slot name="body">
        @livewire('backend.order-table', [
            'allowBulkActions' => $logged_in_user->can('admin.access.accurate') ? true : false
            ])
    </x-slot>

</x-backend.card>

@endsection
