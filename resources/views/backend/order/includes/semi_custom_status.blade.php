<div>

    @if (!$order->hasSemiCustom())
        <label for="" class="badge bg-danger">Not found</label>

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
            <label for="" class="badge bg-success">All finished</label>
        @endif

    @endif

</div>
