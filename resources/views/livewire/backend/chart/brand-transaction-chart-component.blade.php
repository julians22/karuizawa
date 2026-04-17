<div>
    {{-- Stop trying to control. --}}

<div class="mb-3">
        <h5 class="text-center">
            Total Selling {{ $brand->name }}: <strong>{{ price_format($totalSelling) }}</strong>
            <small>
                @if ($daily)
                    <span class="bg-warning badge">@lang('Daily') {{ $daily }}</span>
                @else
                    <span class="bg-warning badge">@lang('Monthly')</span>
                @endif
            </small>
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
