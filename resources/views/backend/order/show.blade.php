@extends('backend.layouts.app')

@section('title', __('Order Details'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')


<x-backend.card>
    <x-slot name="header">
        @lang('Order Details')
    </x-slot>

    <x-slot name="body">
        <div class="row">

            {{-- Order Inforation --}}
            <div class="col-md-3">
                <x-backend.card>
                    <x-slot name="body">
                        <p class="card-title fw-bold">@lang('Order Information')</p>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <tbody>
                                    <tr>
                                        <th>@lang('Order ID')</th>
                                        <td>{{ $order->id }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Store')</th>
                                        <td>{{ $order->store->name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Name')</th>
                                        <td>{{ $order->customer->full_name }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Email')</th>
                                        <td>{{ $order->customer->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Customer Phone')</th>
                                        <td>{{ $order->customer->phone }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Order Status')</th>
                                        <td>{{ $order->status }}</td>
                                    </tr>
                                    <tr>
                                        <th>@lang('Total Price')</th>
                                        <td>{{ price_format( $order->total_price) }}</td>
                                    </tr>

                                    <tr>
                                        <th colspan="2">@lang('Crew Incharge')</th>
                                    </tr>
                                    <tr>
                                        <th>@lang('Crew Name')</th>
                                        <td>
                                            @if ($order->user)
                                                {{ $order->user->name }}
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>@lang('Crew Email')</th>
                                        <td>
                                            @if ($order->user)
                                                {{ $order->user->email }}
                                            @else
                                                @lang('N/A')
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </x-slot>

                    {{-- Date Created & Last Update --}}
                    <x-slot name="footer">
                        <small>@lang('Created') {{ $order->created_at->diffForHumans() }} <strong>({{$order->created_at->format('d-m-Y h:i:s')}})</strong></small> <br>
                        <small>@lang('Last Updated') {{ $order->updated_at->diffForHumans() }}</small>
                    </x-slot>
                </x-backend.card>

            </div>

            {{-- Order Items --}}
            <div class="col-md-9">
                <x-backend.card>
                    <x-slot name="body">
                        <p class="card-title fw-bold">@lang('Order Items')</p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm">
                                <thead>
                                    <tr>
                                        <th>@lang('Product Name')</th>
                                        <th>@lang('Quantity')</th>
                                        <th>@lang('Price')</th>
                                        <th>@lang('Total Price')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $item)
                                        <tr>
                                            @if ($item->product_type == 'App\Models\Product')
                                            <td>{{ $item->product->sku }} - {{ $item->product->product_name }}</td>
                                            @else
                                            <td>{{ $item->product->name }}</td>
                                            @endif
                                            <td>{{ $item->quantity }}</td>
                                            <td>{{ price_format($item->price) }}</td>
                                            <td>{{ price_format($item->total_price) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <p class="card-titel fw-bold">@lang('Payment')</p>

                        @if (!$order->isPaymentComplete())
                            <div class="alert alert-warning">
                                @lang('Payment is not completed')
                            </div>
                        @elseif ($order->isPaymentComplete())
                            <div class="alert alert-success">
                                @lang('Payment is completed')
                            </div>
                        @endif

                        @if ($order->payments)
                            {{-- Table Column --}}
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-sm">
                                    @if ($order->hasDownPayment())
                                        <tr>
                                            <th>@lang('Down Payment')</th>
                                            <td>{{ price_format($order->down_payment_amount) }}</td>
                                        </tr>
                                    @endif

                                    @if ($order->hasCompletionPayment())
                                        <tr>
                                            <th>@lang('Completion Payment')</th>
                                            <td>{{ price_format($order->completion_amount) }}</td>
                                        </tr>
                                    @endif
                                </table>
                            </div>

                            {{-- If synced to accurate --}}
                            @if ($order->isSyncedToAccurate())
                                <div class="alert alert-success">
                                    @lang('Order is synced to Accurate')
                                    {{-- Date --}}
                                    <small>{{ $order->accurate_synced_at->diffForHumans() }}</small>
                                </div>
                            @else
                            {{-- Upload to accurate --}}
                            <x-utils.link :href="route('admin.order.upload-accurate', $order)"
                                class="btn btn-warning btn-sm" :text="'Upload to Accurate'" />
                            @endif
                        @endif

                    </x-slot>
                </x-backend.card>
            </div>
        </div>
    </x-slot>

    <x-slot name="footer">
        <a href="{{ route('admin.order.index') }}" class="btn btn-secondary">
            @lang('Back')
        </a>
    </x-slot>

</x-backend.card>


@endsection
