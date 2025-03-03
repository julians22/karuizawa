@extends('backend.layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Welcome :Name', ['name' => $logged_in_user->name])
        </x-slot>

        <x-slot name="body">
            @lang('Welcome to the Dashboard')

            <div class="row mt-2">
                {{-- Total Sales (Today) --}}
                <div class="col-sm-2">
                    <div class="callout callout-success">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="totalSalesToday">Total Sales (Today)</div>
                        <div class="fs-5 fw-semibold">{{ price_format($totalTodayPrice) }}</div>
                    </div>
                </div>

                {{-- Total Sales (This Month) --}}
                <div class="col-sm-2">
                    <div class="callout callout-warning">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="totalSalesThisMonth">Total Sales (Last 30 Days)</div>
                        <div class="fs-5 fw-semibold">{{ price_format($totalLast30DaysPrice) }}</div>
                    </div>
                </div>

                {{-- Total Customer --}}
                <div class="col-sm-2">
                    <div class="callout callout-primary">
                        <div class="small text-body-secondary text-truncate" data-coreui-i18n="totalCustomer">Total Customer</div>
                        <div class="fs-5 fw-semibold">{{ $totalCustomer }}</div>
                    </div>
                </div>

                {{-- New Customer --}}
                <div class="col-sm-2">
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
                <div class="col-sm-2">
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

            <div class="row mt-2">

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <strong class="card-title">Selling by Store</strong>
                        </div>
                        <div class="card-body">
                            <div id="chart"></div>
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

        const chart = new ApexCharts(document.querySelector("#chart"), options);

        chart.render();
    </script>

    <script>
        const tooltipTriggerList = document.querySelectorAll('[data-coreui-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new coreui.Tooltip(tooltipTriggerEl));
    </script>
@endpush
