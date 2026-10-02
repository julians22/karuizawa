<x-utils.view-button :href="route('admin.online-sales.order.show', ['order' => $order])" />

<div class="d-inline-block">
    <a class="btn btn-sm btn-secondary dropdown-toggle" id="moreMenuLink" href="#" role="button" data-coreui-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false">
        @lang('More')
    </a>

    <div class="dropdown-menu" aria-labelledby="moreMenuLink">

        @if ($order->shipment && !$order->shipment->biteship_order_id)
            <x-utils.form-button
                :action="route('admin.online-sales.order.retry-shipment', [$order])"
                method="post"
                name="confirm-item"
                data-title="{{ __('Retry creating the Biteship shipment for this order?') }}"
                button-class="dropdown-item"
            >
                @lang('Retry Biteship Shipment')
            </x-utils.form-button>
        @endif

        @if ($order->shipment && $order->shipment->waybill_id)
            <x-utils.form-button
                :action="route('admin.online-sales.order.track-shipment', [$order])"
                method="post"
                name="confirm-item"
                button-class="dropdown-item"
            >
                @lang('Track Shipment')
            </x-utils.form-button>
        @endif
    </div>
</div>
