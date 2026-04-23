<div class="d-flex">
    <span>
        @if ($row->orders_count > 0)
            <label for="" class="bg-success badge">{{ price_format($row->total_spent, 2) }}</label>
        @else
            <label for="" class="bg-danger badge">No orders</label>
        @endif
    </span>
</div>
