<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}

    <div class="alert alert-danger" role="alert"  wire:offline>
        <strong>Offline!</strong> Please check your internet connection.
    </div>

    <span class="badge bg-info" wire:loading.class="bg-secondary" wire:loading.class.remove="bg-info">Selected Date {{ $month }}</span>

    <div class="row g-2 mt-2">
        @foreach ($stores as $item)
        <div class="col">
            <button
                class="btn btn-primary btn-sm"
                wire:key="{{$month}}-{{ $item->id }}-product-daily-report"
                wire:loading.attr="disabled"
                wire:click='generateReportData({{ $item->id }})'
                wire:target="month, generateReportData({{ $item->id }})"
                >
                Download Report Data for {{ $item->name }}
            </button>
        </div>
        @endforeach
    </div>
</div>
