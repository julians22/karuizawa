<tr>
    {{-- Stop trying to control. --}}
    <td>{{ $this->storeModel->name }}</td>
    <td>

        <table class="table-sm table table-bordered">

            <thead>
                <tr>
                    <th>Target</th>
                    <th>Actual</th>
                    <th>Remaining</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>{{ price_format($this->target) }}</td>
                    <td>{{ price_format($this->actualSelling) }}</td>
                    <td>

                        {{ price_format($this->remainingTarget) }}

                    </td>
                </tr>
            </tbody>

        </table>

    </td>
</tr>


