<div class="d-flex">
    <span>
        @if ($row->orders_count > 0)
            <label for="" class="bg-success badge">{{ $row->orders_count }}</label>
        @else
            <label for="" class="bg-danger badge">No orders</label>
        @endif
    </span>

    {{-- Link to detail --}}
    <a href="{{ route('admin.customer.orders', $row) }}" class="ms-2 btn btn-sm btn-primary">
        <i class="cil-list"></i> View Orders
    </a>
</div>
