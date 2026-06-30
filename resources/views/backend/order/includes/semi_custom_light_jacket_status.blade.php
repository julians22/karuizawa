<div>

    @if (!$order->hasSemiCustomLightJacket())
        <label for="" class="bg-danger badge">Not found</label>

    @else

        @php
            $semiCustomLightJacketProducts = $order->semi_custom_light_jacket_products;
            $scCount = $semiCustomLightJacketProducts->count();
            $scSuccessCount = 0;

            foreach ($semiCustomLightJacketProducts as $scItem)
            if ($scItem->product_sclj->status == 'finish') {
                $scSuccessCount++;
            }

        @endphp

        {{ $scSuccessCount }} finished / {{ $scCount }} items

        @if ($scCount == $scSuccessCount)
            <label for="" class="bg-success badge">All finished</label>
        @endif

    @endif

</div>
