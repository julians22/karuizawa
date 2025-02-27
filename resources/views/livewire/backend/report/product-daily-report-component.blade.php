<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <span class="badge bg-info">Selected Date {{ $month }}</span>

    <div class="row g-2 mt-2">
        @foreach ($stores as $item)
        <div class="col">
            <button
                wire:key="{{$month}}-{{ $item->id }}"
                wire:loading.attr="disabled"
                wire:target="generateReportData"
                class="btn btn-primary btn-sm btn-block"
                wire:click='generateReportData({{ $item->id }})'>
                Download Report Data for {{ $item->name }}
            </button>
        </div>
        @endforeach
    </div>

</div>
