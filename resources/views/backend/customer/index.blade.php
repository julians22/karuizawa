@extends('backend.layouts.app')

@section('title', __('Product Management'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Customer Management')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                href="#"
                :text="__('Create Customer')"
            />
        </x-slot>
    @endif

    <x-slot name="body">
        @livewire('backend.customer-table')
    </x-slot>

</x-backend.card>

@endsection
