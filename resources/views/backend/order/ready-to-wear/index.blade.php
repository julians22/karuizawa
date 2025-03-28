@extends('backend.layouts.app')

@section('title', __('Order Ready to Wear Management'))

@section('content')

<x-backend.card>

    <x-slot name="header">
        @lang('Order Ready to Wear Management')
    </x-slot>

    <x-slot name="body">
        <div class="row mb-2">
            <div class="col-12">
                <x-utils.alert type="info" class="header-message" :dismissable="false">
                    Only orders that are successfully paid will be shown here.
                </x-utils.alert>
            </div>
        </div>

        @livewire('backend.order-ready-to-wear-table')
    </x-slot>

</x-backend.card>

@endsection
