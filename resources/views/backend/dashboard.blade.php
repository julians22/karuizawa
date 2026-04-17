@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            @lang('Welcome to the Dashboard')

            <div class="mt-2 row">
                {{-- Total Sales (Today) --}}
                <div class="col-sm-2">
                    <div class="callout callout-success">
                        <div class="text-body-secondary text-truncate small" data-coreui-i18n="totalSalesToday">Total Sales (Today)</div>
                        <div class="fs-5 fw-semibold">{{ price_format($totalTodayPrice) }}</div>
                    </div>
                </div>

                {{-- Total Sales (This Month) --}}
                <div class="col-sm-2">
                    <div class="callout callout-warning">
                        <div class="text-body-secondary text-truncate small" data-coreui-i18n="totalSalesThisMonth">Total Sales (Last 30 Days)</div>
                        <div class="fs-5 fw-semibold">{{ price_format($totalLast30DaysPrice) }}</div>
                    </div>
                </div>

                {{-- Total Customer --}}
                <div class="col-sm-2">
                    <div class="callout callout-primary">
                        <div class="text-body-secondary text-truncate small" data-coreui-i18n="totalCustomer">Total Customer</div>
                        <div class="fs-5 fw-semibold">{{ $totalCustomer }}</div>
                    </div>
                </div>

                {{-- New Customer --}}
                <div class="col-sm-2">
                    <div class="callout callout-secondary">
                        <div class="text-body-secondary text-truncate small" data-coreui-i18n="newCustomer">
                            New Customer
                            {{-- tooltips --}}
                            <a href="#" class="rounded-pill text-bg-light badge" data-coreui-toggle="tooltip" title="New Customer, who registered in the last 30 days">
                                <i class="cil-info"></i>
                            </a>
                        </div>
                        <div class="fs-5 fw-semibold">{{ $newCustomer }}</div>
                    </div>
                </div>

                {{-- repeat Customer --}}
                <div class="col-sm-2">
                    <div class="callout callout-secondary">
                        <div class="text-body-secondary text-truncate small" data-coreui-i18n="repeatCustomer">
                            Repeat Order Customer
                            {{-- tooltips --}}
                            <a href="#" class="rounded-pill text-bg-light badge" data-coreui-toggle="tooltip" title="Repeat Order Customer, repeating order in last 30 days">
                                <i class="cil-info"></i>
                            </a>
                        </div>
                        <div class="fs-5 fw-semibold">{{ $repeatCustomer }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-2 row">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Selling by Store</strong>
                        </div>
                        <div class="card-body">
                            <div id="chart-selling-by-store"></div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Brands Breakdown Transactions --}}
            <div class="mt-2 row">

                <div class="col-md-12">

                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Selling by Brands</strong>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                @foreach ($brands as $brand)
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header">
                                                <strong>
                                                    {{$brand->name}}
                                                </strong>
                                            </div>

                                            <div class="card-body">
                                                @livewire('backend.report.dashboard-breakdown-brand-component', ['brand' => $brand], key($brand->id))
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </x-slot>
    </x-backend.card>
@endsection

@push('after-scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        const options = @json($sellingByStoreData);

        options.tooltip = {
            y: {
                formatter: function (val) {
                    return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#chart-selling-by-store"), options);

        chart.render();
    </script>

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl));
    </script>
@endpush
