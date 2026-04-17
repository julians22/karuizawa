<div>
    {{-- Be like water. --}}
    {{-- Total Selling ({{price_format($totalSelling)}}) --}}
    <div class="mb-3">
        <h5 class="text-center">
            Total Selling: <strong>{{ price_format($totalSelling) }}</strong>
        </h5>
    </div>

    <div
        id="chart-brand-{{ $brand->id }}"
        wire:ignore
        class="d-flex justify-content-center"
    ></div>

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

            const chartBrand = new ApexCharts($wire.$el.querySelector("#chart-brand-{{ $brand->id }}"), options);
            chartBrand.render();
        });
    </script>
    @endscript

</div>
