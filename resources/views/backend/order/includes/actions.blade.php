<x-utils.view-button :href="route('admin.order.show', ['order' => $order])" />

<div class="d-inline-block">
    <a class="btn btn-sm btn-secondary dropdown-toggle" id="moreMenuLink" href="#" role="button" data-coreui-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false">
        @lang('More')
    </a>

    <div class="dropdown-menu" aria-labelledby="moreMenuLink">

        @if ($order->isPending() || $order->isCancelled())
            <x-utils.form-button
                :action="route('admin.order.approve', [$order])"
                method="patch"
                name="confirm-item"
                button-class="dropdown-item"
            >
                @lang('Approve')
            </x-utils.form-button>
        @endif

        @if ($order->isPending() || $order->isCompleted())
            <x-utils.form-button
                :action="route('admin.order.cancel', [$order])"
                method="patch"
                name="confirm-item"
                button-class="dropdown-item"
            >
                @lang('Reject')
            </x-utils.form-button>
        @endif

        {{-- UnSync Data --}}
        @if (!empty($order->accurate_sync_date))

            <x-utils.form-button
                :action="route('admin.order.unsync', [$order])"
                method="patch"
                name="confirm-item"
                data-title="{{ __('Are you sure you want to UnSync this order?') }}"
                button-class="dropdown-item"
            >
                @lang('UnSync')
            </x-utils.form-button>

        @endif

    </div>

</div>
