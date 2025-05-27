<div class="d-inline-block">
    <a class="btn btn-sm btn-secondary dropdown-toggle" id="moreMenuLink" href="#" role="button" data-coreui-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false">
        @lang('More')
    </a>

    <div class="dropdown-menu" aria-labelledby="moreMenuLink">

        @if (!$orderitem->product_sc->isFinish())
            <x-utils.form-button
                :action="route('admin.order.semi-custom.approve', [ 'semiCustomProduct' => $orderitem->product_sc])"
                method="patch"
                name="confirm-item"
                button-class="dropdown-item"
            >
                @lang('Complete')
            </x-utils.form-button>
        @endif

        @if ($orderitem->product_sc->isFinish())
            <x-utils.form-button
                :action="route('admin.order.semi-custom.cancel', [ 'semiCustomProduct' => $orderitem->product_sc])"
                method="patch"
                name="confirm-item"
                button-class="dropdown-item"
            >
                @lang('Revert')
            </x-utils.form-button>
        @endif

    </div>

</div>
