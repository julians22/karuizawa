@extends('backend.layouts.app')

@section('title', __('Product Management'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Product Management')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.product.create')"
                :text="__('Create Product')"
            />

            {{-- Fetch Stock Product --}}
            <x-utils.link
                icon="c-icon cil-sync"
                class="card-header-action"
                :href="route('admin.product.fetch-stock')"
                :text="__('Fetch Stock')"
            />

            {{-- Fetch Price Product --}}
            <x-utils.link
                icon="c-icon cil-sync"
                class="card-header-action"
                :href="route('admin.product.fetch-price')"
                :text="__('Fetch Product')"
            />

        </x-slot>
    @endif

    <x-slot name="body">
        @livewire('backend.product-table')
    </x-slot>

</x-backend.card>

@endsection
