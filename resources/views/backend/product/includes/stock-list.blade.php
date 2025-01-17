@if (count($product->productActualStocks) > 0)
    @foreach ($product->productActualStocks as $stock)
        <span class="badge text-bg-info">{{ $stock->store->code }}: {{ $stock->stock_quantity }}
    @endforeach
@else
    <span class="badge text-bg-danger">No Stock Found
@endif
