@extends('backend.layouts.app')

@section('title', __('Orders by Customer Details'))

@section('content')

    <x-backend.card>

        <x-slot name="header">
            @lang('Orders by Customer Details')
        </x-slot>

        <x-slot name="body">
            <h4 class="mb-2">@lang('Customer Name') : <label for="" class="bg-info badge">{{ $customer->full_name }}</label></h4>

            <div class="row">
                <div class="col-sm-3">

                    {{-- Create widget cards --}}
                    @foreach ($stats as $item)
                        <div class="callout callout-primary">
                            <div class="text-body-secondary text-truncate small" data-coreui-i18n="{{ $item['label'] }}">{{ $item['label'] }}</div>
                            <div class="fs-5 fw-semibold">{{ $item['value'] }}</div>
                        </div>
                    @endforeach

                </div>

                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">@lang('Order List')</h5>
                            <livewire:backend.customer.order-table :customer="$customer" />
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>
    </x-backend.card>

@endsection
