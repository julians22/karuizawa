@extends('backend.layouts.app')

@section('title', __('Coupon Management'))

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Promo Management')
    </x-slot>

    @if ($logged_in_user->hasAllAccess())
        <x-slot name="headerActions">
            <x-utils.link
                icon="c-icon cil-plus"
                class="card-header-action"
                :href="route('admin.promo.create')"
                :text="__('Create Promo')"
            />
        </x-slot>
    @endif

    <x-slot name="body">
        @livewire('backend.promo-table')
    </x-slot>

</x-backend.card>

@endsection
