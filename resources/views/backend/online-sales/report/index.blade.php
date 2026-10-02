@extends('backend.layouts.app')

@section('title', __('Online Sales Reporting'))

@section('breadcrumb-links')
@endsection

@section('content')

<div class="row">
    <div class="col-md-3">
        <x-backend.card>
            <x-slot name="body">
                <p class="mb-1 text-medium-emphasis">@lang('Total Orders')</p>
                <h4>{{ number_format($totalOrders) }}</h4>
            </x-slot>
        </x-backend.card>
    </div>
    <div class="col-md-3">
        <x-backend.card>
            <x-slot name="body">
                <p class="mb-1 text-medium-emphasis">@lang('Total Revenue (Completed)')</p>
                <h4>{{ price_format($totalRevenue) }}</h4>
            </x-slot>
        </x-backend.card>
    </div>
    <div class="col-md-3">
        <x-backend.card>
            <x-slot name="body">
                <p class="mb-1 text-medium-emphasis">@lang('Pending Orders')</p>
                <h4>{{ number_format($pendingOrders) }}</h4>
            </x-slot>
        </x-backend.card>
    </div>
    <div class="col-md-3">
        <x-backend.card>
            <x-slot name="body">
                <p class="mb-1 text-medium-emphasis">@lang('Cancelled Orders')</p>
                <h4>{{ number_format($cancelledOrders) }}</h4>
            </x-slot>
        </x-backend.card>
    </div>
</div>

<x-backend.card>
    <x-slot name="header">
        @lang('Online Sales Reporting')
    </x-slot>

    <x-slot name="body">
        <form method="GET" class="mb-3 row g-2">
            <div class="col-auto">
                <input type="date" name="from" class="form-control" value="{{ $from->format('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="to" class="form-control" value="{{ $to->format('Y-m-d') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">@lang('Filter')</button>
            </div>
        </form>

        <div class="row">
            <div class="col-md-6">
                <p class="card-title fw-bold">@lang('Orders by Status')</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped">
                        <thead>
                            <tr>
                                <th>@lang('Status')</th>
                                <th>@lang('Total Orders')</th>
                                <th>@lang('Revenue')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ordersByStatus as $row)
                                <tr>
                                    <td>{{ ucfirst($row->status) }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td>{{ price_format($row->revenue) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3">@lang('No data.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-6">
                <p class="card-title fw-bold">@lang('Orders by Courier')</p>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped">
                        <thead>
                            <tr>
                                <th>@lang('Courier')</th>
                                <th>@lang('Total Orders')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ordersByCourier as $row)
                                <tr>
                                    <td>{{ strtoupper($row->courier_company ?? '-') }}</td>
                                    <td>{{ $row->total }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">@lang('No data.')</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <p class="mt-3 card-title fw-bold">@lang('Daily Sales (Completed Orders)')</p>
        <div class="table-responsive">
            <table class="table table-bordered table-sm table-striped">
                <thead>
                    <tr>
                        <th>@lang('Date')</th>
                        <th>@lang('Total Orders')</th>
                        <th>@lang('Revenue')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dailySales as $row)
                        <tr>
                            <td>{{ $row->date }}</td>
                            <td>{{ $row->total }}</td>
                            <td>{{ price_format($row->revenue) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">@lang('No data.')</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-slot>
</x-backend.card>

@endsection
