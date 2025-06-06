@extends('backend.layouts.app')

@section('title', __('Order Details'))

@section('breadcrumb-links')
    {{-- @include('backend.auth.user.includes.breadcrumb-links') --}}
@endsection

@section('content')


<x-forms.patch :action="route('admin.order.update', ['order' => $order])">

    <x-backend.card>

        <x-slot name="header">
            @lang('Edit Order') - {{ $order->order_number }}
        </x-slot>

        <x-slot name="body">

            <div class="row">
                <div class="col-md-6">
                    <div class="row mb-3">
                        <label for="order_number" class="col-md-12 mb-2">@lang('Order Number')</label>
                        <div class="col-md-12">
                            <input type="text" disabled name="order_number" class="form-control disabled" readonly value="{{$order->order_number}}">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="order_date" class="col-md-12 mb-2">@lang('Order Date')</label>
                        <div class="col-md-12">
                            <input type="datetime-local" name="order_date" class="form-control" value="{{$order->order_date->format('Y-m-d h:i')}}">
                        </div>
                    </div>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <button class="btn btn-sm btn-primary float-right" type="submit">@lang('Save')</button>
        </x-slot>

    </x-backend.card>

</x-forms.patch>


@endsection
