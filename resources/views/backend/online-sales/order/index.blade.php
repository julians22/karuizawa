@extends('backend.layouts.app')

@section('title', __('Online Sales Management'))

@section('breadcrumb-links')
@endsection

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Online Sales Management')
    </x-slot>

    <x-slot name="body">
        @livewire('backend.online-sales.order-table')
    </x-slot>

</x-backend.card>

@endsection
