<div>
    {{-- The whole world belongs to you. --}}
    <table class="table table-striped-columns">
        <thead>
            <tr>
                <th class="text-center" width="19%">Category</th>
                <th class="text-center">Actual</th>
                <th class="text-center" width="10%">QTY</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td class="text-center">{{ $key }}</td>
                    <td class="text-center">{{ price_format($item['value']) }}</td>
                    <td class="text-center">{{ $item['qty'] }}</td>
                </tr>
            @endforeach
        </tbody>

    </table>

</div>
