<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}

    <div>

        @if ($this->requrementIsMet)
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-center h6">{{ $this->storeName }} ({{ $month }})</th>
                        </tr>
                        <tr>
                            <th class="text-center" width="20%">Crew</th>
                            <th class="text-center">Target</th>
                            <th class="text-center" width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->crewData as $crew)
                            <tr>
                                <td>{{ $crew->name }}</td>
                                <td class="text-center">
                                    <livewire:backend.report.performance.target-field-component :crew="$crew" :month="$month" :store="$store" key="target-field-component-{{$crew->id}}-{{$store}}-{{$month}}" />
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-primary" wire:click="editTarget({{ $crew->id }})">Edit</button>
                                </td>
                            </tr>
                        @endforeach
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
