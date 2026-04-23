<div>

    @if (!$order->hasSemiCustom())
        <label for="" class="bg-danger badge">Not found</label>

    @else

        @php
            $semiCustomOuterProducts = $order->semi_custom_outer_products;
            $scCount = $semiCustomOuterProducts->count();
            $scSuccessCount = 0;

            foreach ($semiCustomOuterProducts as $scItem)
            if ($scItem->product_sc->status == 'finish') {
                $scSuccessCount++;
            }

        @endphp

        {{ $scSuccessCount }} finished / {{ $scCount }} items

        @if ($scCount == $scSuccessCount)
            <label for="" class="bg-success badge">All finished</label>
        @endif

    @endif

</div>
