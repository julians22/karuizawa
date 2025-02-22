@extends('backend.layouts.app')

@section('title', __('Product Category Management'))

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Product Category Management')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.product.create')"
                :text="__('Create Product')"
            />
    @endif

    <x-slot name="body">
        @livewire('backend.product-category-table')
    </x-slot>

</x-backend.card>

@endsection
