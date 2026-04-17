<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    {{-- resources\views\livewire\backend\chart\store-transaction-chart-component.blade.php --}}

    <div class="mb-3">
        <h4 class="text-center">
            Total Selling : <strong>{{ price_format($totalSelling) }}</strong>
            <small>
                @if ($daily)
                    <span class="bg-warning badge">@lang('Showing Daily Result') {{ $daily }}</span>
                @else
                    <span class="bg-warning badge">@lang('Showing Monthly Result')</span>
                @endif
            </small>
        </h4>
    </div>

    <div
        id="chart-store"
        wire:ignore
        class="d-flex justify-content-center"
    ></div>
</div>

@assets

{{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}

@endassets

@script

<script>


$wire.on('chartDataFilled', () => {
    console.log('Chart Data Filled');
    let options = $wire.chartData;

    options.tooltip = {
        y: {
            formatter: function (val) {
                return "Rp " + val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }
        }
    };

    chartReport = new ApexCharts($wire.$el.querySelector("#chart-store"), options);
    chartReport.render();
});

$wire.$watch('month', () => {

    if (chartReport) {
        chartReport.destroy();
    }

   $wire.validateReactiveProp();
});


</script>

@endscript
