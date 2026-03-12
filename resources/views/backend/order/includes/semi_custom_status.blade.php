<div>

    @if (!$order->hasSemiCustom())
        <label for="" class="bg-danger badge">Not found</label>

    @else

        @php
            $semiCustomProducts = $order->semi_custom_products;
            $scCount = $semiCustomProducts->count();
            $scSuccessCount = 0;

            foreach ($semiCustomProducts as $scItem)
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
