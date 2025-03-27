<tr class="border border-light-subtle">
    {{-- Stop trying to control. --}}
    <th class="text-center align-middle">{{ $this->storeModel->name }}</th>
    <td>

        <table class="table-sm table table-bordered mb-0">

            <thead>
                <tr>
                    <th width="150">Target</th>
                    <th width="150">Actual</th>
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


