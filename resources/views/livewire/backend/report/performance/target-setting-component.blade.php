<div class="row mt-4">

    <div class="col-md-8">
        <div class="d-flex">
            <h4 class="card-subtitle mb-3 me-4">Target Setting </h4>
            <div x-data="{ isCreateOpen: @entangle('isCreate') }">
                <button x-show="!isCreateOpen" class="btn btn-sm btn-primary" wire:click="createTarget">Insert Target</button>
            </div>
        </div>
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
                                <th class="text-center" width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if (count($crewData) == 0)
                                <tr>
                                    <td colspan="3" class="text-center">No data available</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <button
                                            wire:click="getTargetFromTransactions"
                                            wire:loading.attr="disabled"
                                            class="btn btn-sm btn-warning">Get target from transactions </button>
                                    </td>
                                </tr>
                            @else

                                @foreach ($crewData as $crew)
                                    <tr>
                                        <td>{{ $crew['name'] }}</td>
                                        <td class="text-center">
                                            <livewire:backend.report.performance.target-field-component
                                                :crew="$crew"
                                                :month="$month"
                                                :store="$store"
                                                key="target-field-component-{{$crew['id']}}-{{$store}}-{{$month}}" />
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-primary"
                                                wire:loading.attr="disabled"
                                                wire:click="editTarget({{ $crew['target_id'] }})">Edit</button>

                                            <button class="btn btn-sm btn-danger"
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

    <div class="col-md-4">
        <div x-data="{ isEditOpen: @entangle('isEdit'), isCreateOpen: @entangle('isCreate') }">
            <div x-show="isEditOpen">
                <div class="card">
                    <div class="card-body">
                        @if (!empty($editTargetId) && $isEdit)
                            <h6 class="card-title mb-2">Edit Target</h6>
                            <livewire:backend.report.performance.form.edit :targetId="$editTargetId" />
                        @endif
                    </div>
                </div>
            </div>

            <div x-show="isCreateOpen">
                <div class="card-body">
                    @if ($isCreate)

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title my-2">Insert New Target</h6>
                                </div>
                                <div class="card-body">
                                    <livewire:backend.report.performance.form.create :$crewData :$month :$store/>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- Close your eyes. Count to one. That is how long forever feels. --}}



</div>
