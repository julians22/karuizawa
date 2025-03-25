<div>
    {{-- The whole world belongs to you. --}}
    <table class="table table-striped-columns table-sm" wire:loading.remove wire:target="generateReportData">
        <thead>
            <tr>
                <th class="text-center" width="19%">Category</th>
                <th class="text-center">Actual</th>
                <th class="text-center" width="10%">QTY</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($reportData as $key => $item)
                @php
                    $slugCat = Str::slug($key);

                    $keyPrefix = "{$slugCat}-{$key}";

                @endphp
                <tr
                    wire:key="store-monthly-report-{{ $keyPrefix }}"
                >
                    <td class="text-center">{{ $key }}</td>
                    <td class="text-center">{{ price_format($item['value']) }}</td>
                    <td class="text-center">{{ $item['qty'] }}</td>
                </tr>
            @endforeach
            <tr>
                <th class="text-end">Total</th>
                @php
                    $total = collect($reportData)->sum('value');
                    $totalQty = collect($reportData)->sum('qty');
                @endphp
                <th class="text-center">{{ price_format($total) }}</th>
                <th class="text-center">{{ $totalQty }}</th>
            </tr>
        </tbody>

    </table>

</div>
