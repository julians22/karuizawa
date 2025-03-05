@extends('backend.layouts.app')

@section('title', __('Customer Management'))

@section('content')

    <x-backend.card class="mb-2">
        <x-slot name="body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="callout callout-primary">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="totalCustomer">Total Customer</div>
                        <div class="fs-5 fw-semibold">{{ $totalCustomer }}</div>

                        {{-- Download Excel Link --}}
                        <a href="{{ route('admin.customer.export') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="cil-file-excel"></i> Export Excel
                        </a>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="callout callout-secondary">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="newCustomer">
                            New Customer
                            {{-- tooltips --}}
                            <a href="#" class="badge rounded-pill text-bg-light" data-coreui-toggle="tooltip" title="New Customer, who registered in the last 30 days">
                                <i class="cil-info"></i>
                            </a>
                        </div>
                        <div class="fs-5 fw-semibold">{{ $newCustomer }}</div>
                    </div>
                </div>

                {{-- repeat Customer --}}
                <div class="col-sm-3">
                    <div class="callout callout-secondary">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="repeatCustomer">
                            Repeat Order Customer
                            {{-- tooltips --}}
                            <a href="#" class="badge rounded-pill text-bg-light" data-coreui-toggle="tooltip" title="Repeat Order Customer, repeating order in last 30 days">
                                <i class="cil-info"></i>
                            </a>
                        </div>
                        <div class="fs-5 fw-semibold">{{ $repeatCustomer }}</div>
                    </div>
                </div>
            </div>
        </x-slot>
    </x-backend.card>


    <x-backend.card>

        <x-slot name="header">
            @lang('Customer Management')
        </x-slot>

        <x-slot name="body">

            <div class="row">
                @livewire('backend.customer-table')
            </div>

        </x-slot>

    </x-backend.card>

@endsection

@push('after-scripts')
    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl));
    </script>
@endpush
