<div class="row">

    <div class="col-md-10">
        <div>
            @if ($this->requrementIsMet)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th colspan="5" class="text-center h6">{{ $this->storeName }} ({{ $month }}) | Semi Custom</th>
                            </tr>
                            <tr>
                                <th class="text-center" width="20%">Crew</th>
                                <th class="text-center">Target</th>
                                <th class="text-center">Actual</th>
                                <th class="text-center">Index</th>
                                <th class="text-center" width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($crews) == 0)
                                <tr>
                                    <td colspan="5" class="text-center">No data available</td>
                                </tr>
                            @else
                                @foreach ($crews as $crew)
                                    <tr
                                        wire:key="table-semi-custom-{{ $crew['target_id'] }}-{{ $store }}-{{ $month }}">
                                        <td>{{ $crew['name'] }}</td>
                                        <td class="text-center">
                                            {{ price_format($crew['target']) }}
                                        </td>
                                        <td>
                                            {{ price_format($crew['actual']) }}
                                        </td>
                                        <td class="text-center">
                                            {{ $crew['percent'] }}%
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary"
                                                wire:key="edit-{{ $crew['target_id'] }}"
                                                wire:loading.attr="disabled"
                                                wire:click="editTarget({{ $crew['target_id'] }})">Edit</button>

                                            <button class="btn btn-sm btn-danger"
                                                wire:key="edit-{{ $crew['target_id'] }}"
                                                wire:loading.attr="disabled"
                                                wire:click="deleteTarget({{ $crew['target_id'] }})">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @else

                {{-- alert --}}
                <div class="alert alert-danger" role="alert">
                    <p class="mb-0">There is no target setting on selected store & month.</p>
                </div>

            @endif
        </div>
    </div>

</div>
