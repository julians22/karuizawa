@extends('backend.layouts.app')

@section('title', __('Online Order Details'))

@section('breadcrumb-links')
@endsection

@section('content')

<x-backend.card>
    <x-slot name="header">
        @lang('Online Order Details')
    </x-slot>

    <x-slot name="body">
        <div class="row">

            <div class="col-md-4">
                <x-backend.card>
                    <x-slot name="body">
                        <p class="card-title fw-bold">@lang('Order Information')</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped">
                                <tbody>
                                    <tr>
                                        <th>@lang('Order ID')</th>
                                        <td>{{ $order->order_number }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Name')</th>
                                        <td>{{ $order->customer->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Phone')</th>
                                        <td>{{ $order->customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Email')</th>
                                        <td>{{ $order->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Total Price')</th>
                                        <td>{{ price_format($order->total_price) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Status')</th>
                                        <td>{{ ucfirst($order->status) }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Order Date')</th>
                                        <td>{{ $order->order_date }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </x-slot>
                </x-backend.card>
            </div>

            <div class="col-md-4">
                <x-backend.card>
                    <x-slot name="body">
                        <p class="card-title fw-bold">@lang('Shipment Information')</p>
                        @if ($order->shipment)
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm table-striped">
                                    <tbody>
                                        <tr>
                                            <th>@lang('Recipient')</th>
                                            <td>{{ $order->shipment->recipient_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Phone')</th>
                                            <td>{{ $order->shipment->recipient_phone }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Address')</th>
                                            <td>{{ $order->shipment->recipient_address }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Courier')</th>
                                            <td>{{ strtoupper($order->shipment->courier_company) }} {{ $order->shipment->courier_service_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Shipping Cost')</th>
                                            <td>{{ price_format($order->shipment->shipping_cost) }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Biteship Order ID')</th>
                                            <td>{{ $order->shipment->biteship_order_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Waybill ID')</th>
                                            <td>{{ $order->shipment->waybill_id ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <th>@lang('Shipment Status')</th>
                                            <td>{{ str($order->shipment->status)->headline() }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p>@lang('No shipment record found for this order.')</p>
                        @endif
                    </x-slot>
                </x-backend.card>
            </div>

            <div class="col-md-4">
                <x-backend.card>
                    <x-slot name="body">
                        <p class="card-title fw-bold">@lang('Products')</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>@lang('Product')</th>
                                        <th>@lang('Qty')</th>
                                        <th>@lang('Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            <td>{{ $item->product->product_name ?? $item->product->name ?? '-' }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ price_format($item->price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-slot>
                </x-backend.card>
            </div>
        </div>

        @if ($order->shipment)
            <x-backend.card>
                <x-slot name="body">
                    <p class="card-title fw-bold">@lang('Shipment History')</p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-striped">
                            <thead>
                                <tr>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Note')</th>
                                    <th>@lang('Date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->shipment->histories as $history)
                                    <tr>
                                        <td>{{ str($history->status)->headline() }}</td>
                                        <td>{{ $history->note }}</td>
                                        <td>{{ $history->created_at }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">@lang('No history yet.')</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </x-slot>
            </x-backend.card>
        @endif
    </x-slot>
</x-backend.card>

@endsection
