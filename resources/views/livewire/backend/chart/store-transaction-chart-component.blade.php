<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}

    <div class="mb-3">
        <h4 class="text-center">
            Total Selling : <strong>{{ price_format($totalSelling) }}</strong>
        </h4>
    </div>

    <div
        id="chart"
        wire:ignore
    ></div>
</div>

@assets

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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

    chartReport = new ApexCharts($wire.$el.querySelector("#chart"), options);
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
